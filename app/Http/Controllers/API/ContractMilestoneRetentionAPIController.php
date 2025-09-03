<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractMilestoneRetentionAPIRequest;
use App\Http\Requests\API\UpdateContractMilestoneRetentionAPIRequest;
use App\Models\Company;
use App\Models\ContractMilestoneRetention;
use App\Repositories\ContractMilestoneRetentionRepository;
use App\Services\ContractAmendmentOtherService;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMilestoneRetentionResource;
use Response;

/**
 * Class ContractMilestoneRetentionController
 * @package App\Http\Controllers\API
 */

class ContractMilestoneRetentionAPIController extends AppBaseController
{
    /** @var  ContractMilestoneRetentionRepository */
    private $contractMilestoneRetentionRepository;

    public function __construct(ContractMilestoneRetentionRepository $contractMilestoneRetentionRepo)
    {
        $this->contractMilestoneRetentionRepository = $contractMilestoneRetentionRepo;
    }

    /**
     * Display a listing of the ContractMilestoneRetention.
     * GET|HEAD /contractMilestoneRetentions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMilestoneRetentions = $this->contractMilestoneRetentionRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMilestoneRetentionResource::collection($contractMilestoneRetentions), 'Contract Milestone Retentions retrieved successfully');
    }

    /**
     * Store a newly created ContractMilestoneRetention in storage.
     * POST /contractMilestoneRetentions
     *
     * @param CreateContractMilestoneRetentionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractMilestoneRetentionAPIRequest $request)
    {
        try
        {
            $this->contractMilestoneRetentionRepository->createMilestoneRetention($request);
            return $this->sendResponse([], trans('common.contract_milestone_retention_created_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError(trans('common.failed_to_create_milestone_retention') . $ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError(trans('common.failed_to_create_milestone_retention') . $ex->getMessage());
        }
    }

    /**
     * Display the specified ContractMilestoneRetention.
     * GET|HEAD /contractMilestoneRetentions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractMilestoneRetention $contractMilestoneRetention */
        $contractMilestoneRetention = $this->contractMilestoneRetentionRepository->find($id);

        if (empty($contractMilestoneRetention)) {
            return $this->sendError(trans('common.contract_milestone_retention_not_found'));
        }

        return $this->sendResponse(new ContractMilestoneRetentionResource($contractMilestoneRetention), trans('common.contract_milestone_retention_retrieved_successfully'));
    }

    /**
     * Update the specified ContractMilestoneRetention in storage.
     * PUT/PATCH /contractMilestoneRetentions/{id}
     *
     * @param int $id
     * @param UpdateContractMilestoneRetentionAPIRequest $request
     *Contract milestone retention updated successfully
     * @return Response
     */
    public function update($id, UpdateContractMilestoneRetentionAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractMilestoneRetention $contractMilestoneRetention */
        $contractMilestoneRetention = $this->contractMilestoneRetentionRepository->find($id);

        if (empty($contractMilestoneRetention)) {
            return $this->sendError(trans('common.contract_milestone_retention_not_found'));
        }

        $contractMilestoneRetention = $this->contractMilestoneRetentionRepository->update($input, $id);

        return $this->sendResponse(new ContractMilestoneRetentionResource($contractMilestoneRetention), trans('common.contract_milestone_retention_updated_successfully'));
    }

    /**
     * Remove the specified ContractMilestoneRetention from storage.
     * DELETE /contractMilestoneRetentions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $input = $request->all();
        $amendment = $input['amendment'] ?? false;
        $contractHistoryUuid = $input['contractHistoryUuid'] ?? null;

        $contractMilestoneRetention = $amendment ? ContractAmendmentOtherService::getMilestoneRetentionAmd(
            $contractHistoryUuid, $id) : $this->contractMilestoneRetentionRepository->findByUuid($id);

        if (empty($contractMilestoneRetention))
        {
            return $this->sendError(trans('common.contract_milestone_retention_not_found'));
        }
        if($amendment)
        {
            $contractMilestoneRetention->deleted_by = General::currentEmployeeId();
            $contractMilestoneRetention->save();
        }
        $contractMilestoneRetention->delete();

        return $this->sendSuccess(trans('common.milestone_retention_deleted_successfully'));
    }

    public function getContractMilestoneRetentionData(Request $request)
    {
        return $this->contractMilestoneRetentionRepository->getContractMilestoneRetentionData($request);
    }

    public function updateMilestoneRetention(Request $request)
    {
        try
        {
            $this->contractMilestoneRetentionRepository->updateMilestoneRetention($request);
            return $this->sendResponse([], 'Milestone retention updated successfully');
        } catch (CommonException $ex)
        {
            return $this->sendError(trans('common.failed_to_update_milestone_retention') . $ex->getMessage());
        } catch(\Exception $ex)
        {
            return $this->sendError(trans('common.failed_to_update_milestone_retention') . $ex->getMessage());
        }
    }

    public function updateRetentionPercentage(Request $request)
    {
        try
        {
            $retentionPercentage = $this->contractMilestoneRetentionRepository->updateRetentionPercentage($request);
            return $this->sendResponse($retentionPercentage, trans('common.retention_percentage_updated_successfully'));
        } catch(CommonException $ex)
        {
            return $this->sendError(trans('common.failed_to_update_milestone_retention_percentage') . $ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError(trans('common.failed_to_update_milestone_retention_percentage') . $ex->getMessage());
        }

    }

    public function exportMilestoneRetention(Request $request)
    {
        $type = $request->type;
        $disk = $request->disk;
        $docName = $request->doc_name;
        $companySystemID = $request->selectedCompanyID ?? 0;
        $contractMaster = $this->contractMilestoneRetentionRepository->exportMilestoneRetention($request);
        $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) ?? 'common' : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($contractMaster);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '') {
            return $this->sendError('unable_to_export_excel');
        } else {
            return $this->sendResponse($basePath, trans('success_export'));
        }
    }

    public function checkMilestoneRetention(Request $request)
    {
        try
        {
            return $this->contractMilestoneRetentionRepository->checkMilestoneRetention($request);
        } catch (\Exception $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
