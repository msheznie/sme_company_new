<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractMilestonePenaltyDetailAPIRequest;
use App\Http\Requests\API\UpdateContractMilestonePenaltyDetailAPIRequest;
use App\Models\ContractMilestonePenaltyDetail;
use App\Repositories\ContractMilestonePenaltyDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMilestonePenaltyDetailResource;
use Response;

/**
 * Class ContractMilestonePenaltyDetailController
 * @package App\Http\Controllers\API
 */

class ContractMilestonePenaltyDetailAPIController extends AppBaseController
{
    /** @var  ContractMilestonePenaltyDetailRepository */
    private $contractMilestonePenaltyDetailRepository;

    public function __construct(ContractMilestonePenaltyDetailRepository $contractMilestonePenaltyDetailRepo)
    {
        $this->contractMilestonePenaltyDetailRepository = $contractMilestonePenaltyDetailRepo;
    }

    /**
     * Display a listing of the ContractMilestonePenaltyDetail.
     * GET|HEAD /contractMilestonePenaltyDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMilestonePenaltyDetails = $this->contractMilestonePenaltyDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMilestonePenaltyDetailResource::collection($contractMilestonePenaltyDetails), 'Contract Milestone Penalty Details retrieved successfully');
    }

    /**
     * Store a newly created ContractMilestonePenaltyDetail in storage.
     * POST /contractMilestonePenaltyDetails
     *
     * @param CreateContractMilestonePenaltyDetailAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractMilestonePenaltyDetailAPIRequest $request)
    {
        try
        {
            $this->contractMilestonePenaltyDetailRepository->createMilestonePenaltyDetail($request);
            return $this->sendResponse([], 'Milestone penalty detail created successfully');
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }

    /**
     * Display the specified ContractMilestonePenaltyDetail.
     * GET|HEAD /contractMilestonePenaltyDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractMilestonePenaltyDetail $contractMilestonePenaltyDetail */
        $contractMilestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepository->find($id);

        if (empty($contractMilestonePenaltyDetail)) {
            return $this->sendError('Contract Milestone Penalty Detail not found');
        }

        return $this->sendResponse(new ContractMilestonePenaltyDetailResource($contractMilestonePenaltyDetail), 'Contract Milestone Penalty Detail retrieved successfully');
    }

    /**
     * Update the specified ContractMilestonePenaltyDetail in storage.
     * PUT/PATCH /contractMilestonePenaltyDetails/{id}
     *
     * @param int $id
     * @param UpdateContractMilestonePenaltyDetailAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMilestonePenaltyDetailAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $milestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepository->findByUuid($id, ['id']);

            if (empty($milestonePenaltyDetail))
            {
                throw new CommonException('Milestone Penalty Detail not found.');
            }
            $this->contractMilestonePenaltyDetailRepository->updateMilestonePenaltyDetail(
                $input, $milestonePenaltyDetail['id']);
            return $this->sendResponse([], trans('Milestone Penalty Detail updated successfully.'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified ContractMilestonePenaltyDetail from storage.
     * DELETE /contractMilestonePenaltyDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractMilestonePenaltyDetail $contractMilestonePenaltyDetail */
        $contractMilestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepository->findByUuid($id, ['id']);
        try
        {
            if (empty($contractMilestonePenaltyDetail))
            {
                throw new CommonException('Milestone Penalty Detail not found');
            }
            $contractMilestonePenaltyDetail->delete();
            return $this->sendSuccess('Milestone Penalty Detail deleted successfully');
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage());
        }
    }

    public function milestonePenaltyDetails(Request $request)
    {
        try
        {
            return $this->contractMilestonePenaltyDetailRepository->getMilestonePenaltyDetails($request);
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function getMilestonePenaltyDetailFormData(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        try
        {
            $response = $this->contractMilestonePenaltyDetailRepository->getMilestonePenaltyDetailFormData(
                $contractUuid, $companySystemID);
            return $this->sendResponse($response, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function getMilestoneAmount(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        $milestoneUuid = $request->input('milestone_title');
        try
        {
            $response = $this->contractMilestonePenaltyDetailRepository->getMilestoneAmount(
                $contractUuid, $companySystemID, $milestoneUuid);
            return $this->sendResponse($response, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function updateMilestonePenaltyStatus(Request $request)
    {
        $input = $request->all();
        $uuid = $request->input('uuid');
        try
        {
            $milestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepository->findByUuid($uuid, ['id']);

            if (empty($milestonePenaltyDetail))
            {
                throw new CommonException('Milestone penalty not found.');
            }
            $this->contractMilestonePenaltyDetailRepository->updateMilestonePenaltyStatus(
                $input, $milestonePenaltyDetail['id']);
            return $this->sendResponse(['id' => $uuid], trans('Milestone penalty status updated successfully.'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function exportMilestonePenalty(Request $request)
    {
        $type = $request->type;
        $disk = $request->disk;
        $docName = $request->doc_name;
        $companySystemID = $request->selectedCompanyID ?? 0;
        $milestonePenalty = $this->contractMilestonePenaltyDetailRepository->exportMilestonePenalty($request);
        $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) ?? 'common' : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($milestonePenalty);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '')
        {
            return $this->sendError('unable_to_export_excel');
        } else
        {
            return $this->sendResponse($basePath, trans('success_export'));
        }
    }
}
