<?php

namespace App\Http\Controllers\API;

use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Http\Requests\API\CreateContractMasterAPIRequest;
use App\Http\Requests\API\UpdateContractMasterAPIRequest;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\Company;
use App\Models\ContractMaster;
use App\Repositories\ContractMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMasterResource;
use Response;

/**
 * Class ContractMasterController
 * @package App\Http\Controllers\API
 */

class ContractMasterAPIController extends AppBaseController
{
    /** @var  ContractMasterRepository */
    private $contractMasterRepository;

    public function __construct(ContractMasterRepository $contractMasterRepo)
    {
        $this->contractMasterRepository = $contractMasterRepo;
    }

    /**
     * Display a listing of the ContractMaster.
     * GET|HEAD /contractMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMasters = $this->contractMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMasterResource::collection($contractMasters), 'Contract Masters retrieved successfully');
    }

    /**
     * Store a newly created ContractMaster in storage.
     * POST /contractMasters
     *
     * @param CreateContractMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $contractMaster = $this->contractMasterRepository->createContractMaster($input);
        return $this->sendResponse($contractMaster, 'Contract Master created successfully');
    }

    /**
     * Display the specified ContractMaster.
     * GET|HEAD /contractMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contractMaster = $this->contractMasterRepository->findByUuid($id,
            ['uuid', 'contractCode', 'title', 'contractType', 'counterParty', 'counterPartyName', 'referenceCode', 'startDate', 'endDate', 'status', 'contractOwner',
                'contractAmount', 'description', 'primaryCounterParty', 'primaryEmail', 'primaryPhoneNumber', 'secondaryCounterParty', 'secondaryEmail', 'secondaryPhoneNumber', 'agreementSignDate',
                'startDate', 'endDate', 'notifyDays', 'contractTimePeriod'
            ],
            [
                "contractTypes" => ['contract_typeId', 'uuid', 'cm_type_name'],
                "contractUsers" => ['id', 'uuid'],
                "contractOwners" => ['id', 'uuid']
            ]
        );
        if (empty($contractMaster)) {
            return $this->sendError( trans('common.contract_not_found'));
        }
        $contractMaster = $this->contractMasterRepository->unsetValues($contractMaster);
        $editData = $contractMaster;
        $response = $this->contractMasterRepository->getEditFormData($editData['counterParty']);
        $response['editData'] = $editData;

        return $this->sendResponse($response, trans('common.contract_retrieved_successfully'));
    }

    /**
     * Update the specified ContractMaster in storage.
     * PUT/PATCH /contractMasters/{id}
     *
     * @param int $id
     * @param UpdateContractMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMasterAPIRequest $request)
    {
        $input = $request->all();
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;

        /** @var ContractMaster $contractMaster */
        $contractMaster = $this->contractMasterRepository->findByUuid($id, ['id']);

        if (empty($contractMaster)) {
            return $this->sendError(trans('common.contract_not_found'));
        }

        $contractMasterUpdate = $this->contractMasterRepository->updateContract($input, $contractMaster['id'], $selectedCompanyID);

        if($contractMasterUpdate['status']){
            return $this->sendResponse(['id' => $id], $contractMasterUpdate['message']);
        } else{
            $statusCode = $contractMasterUpdate['code'] ?? 404;
            return $this->sendError($contractMasterUpdate['message'], $statusCode);
        }
    }

    /**
     * Remove the specified ContractMaster from storage.
     * DELETE /contractMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractMaster $contractMaster */
        $contractMaster = $this->contractMasterRepository->find($id);

        if (empty($contractMaster)) {
            return $this->sendError(trans('common.contract_not_found'));
        }

        $contractMaster->delete();

        return $this->sendSuccess(trans('common.contract_deleted_successfully'));
    }

    public function getContractMaster(Request $request)
    {
        return $this->contractMasterRepository->getContractMaster($request);
    }

    public function getAllContractMasterFilters(Request $request)
    {
        $contractMasterFilters = $this->contractMasterRepository->getAllContractMasterFilters($request);
        return $this->sendResponse($contractMasterFilters, 'Retrieved successfully');
    }

    public function getCounterPartyNames(Request $request)
    {
        $counterPartyNames = $this->contractMasterRepository->getCounterPartyNames($request);
        return $this->sendResponse($counterPartyNames, 'Retrieved successfully');
    }

    public function exportContractMaster(Request $request)
    {
        $input = $request->all();
        $type = $request->type;
        $disk = $request->disk;
        $docName = $request->doc_name;
        $contractMaster = $this->contractMasterRepository->exportContractMasterReport($request);
        $companyMaster = Company::find(isset($input['companySystemID']) ? $input['companySystemID'] : null);
        $companyCode = isset($companyMaster->CompanyID) ? $companyMaster->CompanyID : 'common';
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

    public function deleteFileFromAws(Request $request)
    {
        $path = $request->path;
        $disk = $request->disk;
        DeleteFileFromS3Job::dispatch($path, $disk)->delay(now()->addMinutes(5));
    }

    public function getUserFormDataContractEdit(Request $request) {
        $contractType = $request->input('search');
        $fromContractType = $request->input('fromContractType');
        $value = $contractType['value'];

        $fromData = $this->contractMasterRepository->userFormData($value, $fromContractType);

        if($fromData['status']){
            return $this->sendResponse($fromData['data'], $fromData['message']);
        } else{
            $statusCode = $fromData['code'] ?? 404;
            return $this->sendError($fromData['message'], $statusCode);
        }

    }

    public function getContractTypeSectionData(Request $request){
        $contractSecs = $this->contractMasterRepository->getContractTypeSectionData($request);
        return $this->sendResponse($contractSecs, 'Retrieved successfully');
    }

    public function updateContractSettingDetails(Request $request)
    {
        $updateContractSetting = $this->contractMasterRepository->updateContractSettingDetails($request);

        if($updateContractSetting['status']){
            return $this->sendResponse([], $updateContractSetting['message']);
        } else{
            $statusCode = $updateContractSetting['code'] ?? 404;
            return $this->sendError($statusCode['message'], $statusCode);
        }
    }


}
