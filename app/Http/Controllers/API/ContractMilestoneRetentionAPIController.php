<?php

namespace App\Http\Controllers\API;

use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractMilestoneRetentionAPIRequest;
use App\Http\Requests\API\UpdateContractMilestoneRetentionAPIRequest;
use App\Models\Company;
use App\Models\ContractMilestoneRetention;
use App\Repositories\ContractMilestoneRetentionRepository;
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
        $contractMilestoneRetention = $this->contractMilestoneRetentionRepository->createMilestoneRetention($request);

        if (!$contractMilestoneRetention['status']) {
            return $this->sendError($contractMilestoneRetention['message']);
        } else {
            $this->sendResponse([], 'Contract Milestone Retention created successfully.');
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
            return $this->sendError('Contract Milestone Retention not found');
        }

        return $this->sendResponse(new ContractMilestoneRetentionResource($contractMilestoneRetention), 'Contract Milestone Retention retrieved successfully');
    }

    /**
     * Update the specified ContractMilestoneRetention in storage.
     * PUT/PATCH /contractMilestoneRetentions/{id}
     *
     * @param int $id
     * @param UpdateContractMilestoneRetentionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMilestoneRetentionAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractMilestoneRetention $contractMilestoneRetention */
        $contractMilestoneRetention = $this->contractMilestoneRetentionRepository->find($id);

        if (empty($contractMilestoneRetention)) {
            return $this->sendError('Contract Milestone Retention not found');
        }

        $contractMilestoneRetention = $this->contractMilestoneRetentionRepository->update($input, $id);

        return $this->sendResponse(new ContractMilestoneRetentionResource($contractMilestoneRetention), 'ContractMilestoneRetention updated successfully');
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
    public function destroy($id)
    {
        $milestoneRetention = ContractMilestoneRetention::select('id')->where('uuid', $id)->first();
        $contractMilestoneRetention = $this->contractMilestoneRetentionRepository->find($milestoneRetention->id);

        if (empty($contractMilestoneRetention)) {
            return $this->sendError('Contract Milestone Retention not found');
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
        $milestoneRetention = $this->contractMilestoneRetentionRepository->updateMilestoneRetention($request);
        if($milestoneRetention['status'])
        {
            return $this->sendResponse([], $milestoneRetention['message']);
        } else
        {
            $statusCode = $milestoneRetention['code'] ?? 404;
            return $this->sendError($milestoneRetention['message'], $statusCode);
        }
    }

    public function updateRetentionPercentage(Request $request){
        $retentionPercentage = $this->contractMilestoneRetentionRepository->updateRetentionPercentage($request);
        return $this->sendResponse($retentionPercentage, trans('common.retention_percentage_updated_successfully'));
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
}
