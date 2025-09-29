<?php

namespace App\Http\Controllers\API;

use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateCMContractTypesAPIRequest;
use App\Http\Requests\API\UpdateCMContractTypesAPIRequest;
use App\Models\CMContractTypes;
use App\Repositories\CMContractTypesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractTypesResource;
use Response;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\Company;
/**
 * Class CMContractTypesController
 * @package App\Http\Controllers\API
 */

class CMContractTypesAPIController extends AppBaseController
{
    /** @var  CMContractTypesRepository */
    private $cMContractTypesRepository;

    public function __construct(CMContractTypesRepository $cMContractTypesRepo)
    {
        $this->cMContractTypesRepository = $cMContractTypesRepo;
    }

    /**
     * Display a listing of the CMContractTypes.
     * GET|HEAD /cMContractTypes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractTypes = $this->cMContractTypesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractTypesResource::collection($cMContractTypes), 'Contract Types retrieved successfully');
    }

    /**
     * Store a newly created CMContractTypes in storage.
     * POST /cMContractTypes
     *
     * @param CreateCMContractTypesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractTypesAPIRequest $request)
    {
        $input = $request->all();

        $cMContractTypes = $this->cMContractTypesRepository->create($input);

        return $this->sendResponse(new CMContractTypesResource($cMContractTypes), 'Contract Types saved successfully');
    }

    /**
     * Display the specified CMContractTypes.
     * GET|HEAD /cMContractTypes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractTypes $cMContractTypes */
        $cMContractTypes = $this->cMContractTypesRepository->find($id);

        if (empty($cMContractTypes)) {
            return $this->sendError('Contract Types not found');
        }

        return $this->sendResponse(new CMContractTypesResource($cMContractTypes), 'Contract Types retrieved successfully');
    }

    /**
     * Update the specified CMContractTypes in storage.
     * PUT/PATCH /cMContractTypes/{id}
     *
     * @param int $id
     * @param UpdateCMContractTypesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractTypesAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractTypes $cMContractTypes */
        $cMContractTypes = $this->cMContractTypesRepository->find($id);

        if (empty($cMContractTypes)) {
            return $this->sendError('Contract Types not found');
        }

        $cMContractTypes = $this->cMContractTypesRepository->update($input, $id);

        return $this->sendResponse(new CMContractTypesResource($cMContractTypes), 'CMContractTypes updated successfully');
    }

    /**
     * Remove the specified CMContractTypes from storage.
     * DELETE /cMContractTypes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractTypes $cMContractTypes */
        $cMContractTypes = $this->cMContractTypesRepository->find($id);

        if (empty($cMContractTypes)) {
            return $this->sendError('Contract Types not found');
        }

        $cMContractTypes->delete();

        return $this->sendSuccess('Contract Types deleted successfully');
    }

    public function saveContractType(Request $request)
    {

        $contractType = $this->cMContractTypesRepository->saveContractType($request);

        if ($contractType['status']) {
            return $this->sendResponse([], $contractType['message']);
        } else {
            $statusCode = $contractType['code'] ?? 404;
            return $this->sendError($contractType['message'], $statusCode);
        }
    }

    public function getContractType(Request $request){
        return $this->cMContractTypesRepository->retrieveContractTypes($request);
    }

    public function deleteContractType(Request $request)
    {
        $contractType = $this->cMContractTypesRepository->deleteContractType($request);

        if ($contractType['status']) {
            return $this->sendResponse([], trans('common.contract_type_deleted'));
        } else {
            $statusCode = $catMaster['code'] ?? 404;
            return $this->sendError($contractType['message'], $statusCode);
        }
    }

    public function getAllContractFilters(Request $request)
    {
        $contractFilters = $this->cMContractTypesRepository->getAllContractFilters($request);
        return $this->sendResponse($contractFilters, 'Retrieved successfully');
    }

    public function exportContractTypes(Request $request){
        $type = $request->type;
        $disk = $request->disk;
        $docName = $request->doc_name;
        $companySystemID = $request->selectedCompanyID ?? 0;
        $contractTypes = $this->cMContractTypesRepository->exportContractTypesReport($request);
        $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) ?? 'common' : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($contractTypes);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '') {
            return $this->sendError('common.unable_to_export_excel');
        } else {
            return $this->sendResponse($basePath, trans('common.success_export'));
        }
    }

    public function deleteFileFromAws(Request $request)
    {
        $path = $request->path;
        $disk = $request->disk;
        DeleteFileFromS3Job::dispatch($path, $disk)->delay(now()->addMinutes(5));
    }

    public function getSectionsFilterDrop(Request $request){
        $contractSecs = $this->cMContractTypesRepository->getSectionsFilterDrop($request);
        if(!$contractSecs['status']) {
            $statusCode = $contractSecs['code'] ?? 404;
            return $this->sendError($contractSecs['message'], $statusCode);
        } else {
            return $this->sendResponse($contractSecs['data'], $contractSecs['message']);
        }
    }

    public function updateDynamicFieldDetail(Request $request){
        $updateDynamicField = $this->cMContractTypesRepository->updateDynamicFieldDetail($request);
        if ($updateDynamicField['status']) {
            return $this->sendResponse([], trans('common.successfully_updated'));
        } else {
            $statusCode = $updateDynamicField['code'] ?? 404;
            return $this->sendError($updateDynamicField['message'], $statusCode);
        }
    }
}
