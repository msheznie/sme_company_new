<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractMilestoneAPIRequest;
use App\Http\Requests\API\UpdateContractMilestoneAPIRequest;
use App\Models\Company;
use App\Models\ContractDeliverables;
use App\Models\ContractMilestone;
use App\Models\MilestonePaymentSchedules;
use App\Models\ContractMilestoneRetention;
use App\Repositories\ContractMilestoneRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMilestoneResource;
use Response;
use Illuminate\Support\Facades\Log;

/**
 * Class ContractMilestoneController
 * @package App\Http\Controllers\API
 */

class ContractMilestoneAPIController extends AppBaseController
{
    /** @var  ContractMilestoneRepository */
    private $contractMilestoneRepository;

    public function __construct(ContractMilestoneRepository $contractMilestoneRepo)
    {
        $this->contractMilestoneRepository = $contractMilestoneRepo;
    }

    /**
     * Display a listing of the ContractMilestone.
     * GET|HEAD /contractMilestones
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMilestones = $this->contractMilestoneRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMilestoneResource::collection($contractMilestones),
            'Contract Milestones retrieved successfully');
    }

    /**
     * Store a newly created ContractMilestone in storage.
     * POST /contractMilestones
     *
     * @param CreateContractMilestoneAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractMilestoneAPIRequest $request)
    {
        $contractMilestone = $this->contractMilestoneRepository->createMilestone($request);

        if (!$contractMilestone['status'])
        {
            return $this->sendError($contractMilestone['message']);
        } else
        {
            return $this->sendResponse([], trans('common.milestone_created_successfully'));
        }
    }

    /**
     * Display the specified ContractMilestone.
     * GET|HEAD /contractMilestones/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractMilestone $contractMilestone */
        $contractMilestone = $this->contractMilestoneRepository->findByUuid($id, ['due_date', 'status']);

        if (empty($contractMilestone))
        {
            return $this->sendError(trans('common.milestone_not_found'));
        }

        return $this->sendResponse($contractMilestone,
            trans('common.milestone_retrieved_successfully'));
    }

    /**
     * Update the specified ContractMilestone in storage.
     * PUT/PATCH /contractMilestones/{id}
     *
     * @param int $id
     * @param UpdateContractMilestoneAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMilestoneAPIRequest $request)
    {
        $input = $request->all();
        $uuid = $input['formData']['uuid'] ?? null;
        $amendment = $input['amendment'];

        $contractMilestone = $amendment ? ContractManagementUtils::getMilestonesAmd
        (
            $input['historyUuid'],$uuid
        ) : $this->contractMilestoneRepository->findByUuid($uuid, ['id', 'status']);

        if (empty($contractMilestone))
        {
            return $this->sendError(trans('common.contract_not_found'));
        }

        $contractMilestone = $this->contractMilestoneRepository->updateMilestone($input, $contractMilestone);
        if(!$contractMilestone['status'])
        {
            return $this->sendError($contractMilestone['message']);
        } else
        {
            return $this->sendResponse([], trans('common.milestone_updated_successfully'));
        }
    }

    /**
     * Remove the specified ContractMilestone from storage.
     * DELETE /contractMilestones/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractMilestone $contractMilestone */
        $contractMilestone = $this->contractMilestoneRepository->findByUuid($id, ['id']);
        try
        {
            if (empty($contractMilestone))
            {
                throw new CommonException(trans('common.milestone_not_found'));
            }

            if(ContractDeliverables::where('milestoneID', $contractMilestone['id'])->exists())
            {
                throw new CommonException(trans('common.linked_milestone_delete_error_message'));
            }
            if(MilestonePaymentSchedules::where('milestone_id', $contractMilestone['id'])->exists())
            {
                throw new CommonException(trans('common.cannot_delete_milestone_payment'));
            }
            if(ContractMilestoneRetention::where('milestoneId', $contractMilestone['id'])->exists())
            {
                throw new CommonException(trans('common.cannot_delete_milestone_retention'));
            }

            $contractMilestone->delete();

            return $this->sendSuccess(trans('common.mile_stone_deleted_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage());
        }
    }

    public function getContractMilestones($id, Request $request)
    {
        $contractMilestone = $this->contractMilestoneRepository->getContractMilestones($id, $request);
        if(!$contractMilestone['status'])
        {
            return $this->sendError($contractMilestone['message']);
        } else
        {
            return $this->sendResponse($contractMilestone, trans('common.milestone_retrieved_successfully'));
        }
    }

    public function exportMilestone(Request $request)
    {
        $type = $request->input('type');
        $disk = $request->input('disk');
        $docName = $request->input('doc_name');
        $companySystemID = $request->input('selectedCompanyID') ?? 0;

        $getMilestone = $this->contractMilestoneRepository->getMilestoneExcelData($request);
        if(!$getMilestone['status'])
        {
            return $this->sendError($getMilestone['message']);
        }

        $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) ?? 'common' : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($getMilestone['milestones']);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '')
        {
            return $this->sendError('unable_to_export_excel');
        } else
        {
            return $this->sendResponse($basePath, trans('success_export'));
        }
    }

    public function getMilestoneDueDate(Request $request)
    {
        $response = $this->contractMilestoneRepository->getMilestoneDueDate($request);
        return $this->sendResponse($response, trans('common.retrieved_successfully'));
    }
}
