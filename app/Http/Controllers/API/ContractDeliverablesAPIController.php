<?php

namespace App\Http\Controllers\API;

use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractDeliverablesAPIRequest;
use App\Http\Requests\API\UpdateContractDeliverablesAPIRequest;
use App\Models\Company;
use App\Models\ContractDeliverables;
use App\Models\ContractMaster;
use App\Repositories\ContractDeliverablesRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractDeliverablesResource;
use Response;

/**
 * Class ContractDeliverablesController
 * @package App\Http\Controllers\API
 */

class ContractDeliverablesAPIController extends AppBaseController
{
    /** @var  ContractDeliverablesRepository */
    private $contractDeliverablesRepository;

    public function __construct(ContractDeliverablesRepository $contractDeliverablesRepo)
    {
        $this->contractDeliverablesRepository = $contractDeliverablesRepo;
    }

    /**
     * Display a listing of the ContractDeliverables.
     * GET|HEAD /contractDeliverables
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractDeliverables = $this->contractDeliverablesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );
        return $this->sendResponse(ContractDeliverablesResource::
        collection($contractDeliverables), 'Contract Deliverables retrieved successfully');
    }

    /**
     * Store a newly created ContractDeliverables in storage.
     * POST /contractDeliverables
     *
     * @param CreateContractDeliverablesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractDeliverablesAPIRequest $request)
    {
        $contractDeliverable = $this->contractDeliverablesRepository->createDeliverables($request);

        if (!$contractDeliverable['status'])
        {
            return $this->sendError($contractDeliverable['message']);
        } else
        {
            return $this->sendResponse([], trans('common.deliverable_created_successfully'));
        }
    }

    /**
     * Display the specified ContractDeliverables.
     * GET|HEAD /contractDeliverables/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractDeliverables $contractDeliverables */
        $contractDeliverables = $this->contractDeliverablesRepository->find($id);

        if (empty($contractDeliverables))
        {
            return $this->sendError(trans('common.deliverable_not_found'));
        }

        return $this->sendResponse(new ContractDeliverablesResource($contractDeliverables),
            trans('common.deliverable_retrieved_successfully'));
    }

    /**
     * Update the specified ContractDeliverables in storage.
     * PUT/PATCH /contractDeliverables/{id}
     *
     * @param int $id
     * @param UpdateContractDeliverablesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractDeliverablesAPIRequest $request)
    {
        $uuid = $request->input('uuid') ?? null;

        $contractDeliverables = $this->contractDeliverablesRepository->findByUuid($uuid, ['id']);

        if (empty($contractDeliverables))
        {
            return $this->sendError(trans('common.deliverable_not_found'));
        }

        $updateDeliverableResp = $this->contractDeliverablesRepository
            ->updateDeliverable($request, $contractDeliverables['id']);

        if(!$updateDeliverableResp['status'])
        {
            return $this->sendError($updateDeliverableResp['message']);
        } else
        {
            return $this->sendResponse([],
                trans('common.deliverable_updated_successfully'));
        }
    }

    /**
     * Remove the specified ContractDeliverables from storage.
     * DELETE /contractDeliverables/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractDeliverables $contractDeliverables */
        $contractDeliverables = $this->contractDeliverablesRepository->findByUuid($id, ['id']);

        if (empty($contractDeliverables))
        {
            return $this->sendError(trans('common.deliverable_not_found'));
        }

        $contractDeliverables->delete();

        return $this->sendSuccess(trans('common.deliverable_deleted_successfully'));
    }

    public function getContractDeliverables(Request $request)
    {
        $contractUuid = $request->input('contractUuid');
        $companySystemID = $request->input('companySystemID');

        $contractMaster = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);
        if(empty($contractMaster))
        {
            return $this->sendError(trans('common.contract_not_found'));
        }
        $response['contract_deliverables'] = $this->contractDeliverablesRepository
            ->getDeliverables($contractMaster['id'], $companySystemID);
        $response['contract_milestones'] = ContractManagementUtils::getContractMilestones($contractMaster['id'],
            $companySystemID);
        $response['contract_master'] = $contractMaster;
        return $this->sendResponse($response, trans('common.deliverable_form_data_retrieved_successfully'));
    }

    public function exportDeliverables(Request $request)
    {
        $type = $request->input('type');
        $disk = $request->input('disk');
        $docName = $request->input('doc_name');
        $companySystemID = $request->input('selectedCompanyID') ?? 0;

        $getDeliverables = $this->contractDeliverablesRepository->getDeliverablesExcelData($request);
        if(!$getDeliverables['status'])
        {
            return $this->sendError($getDeliverables['message']);
        }

        $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) ?? 'common' : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($getDeliverables['deliverable']);
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
