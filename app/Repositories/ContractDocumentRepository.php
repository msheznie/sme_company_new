<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractDocumentAmd;
use App\Models\ContractDocument;
use App\Models\ContractMaster;
use App\Models\DocumentMaster;
use App\Models\ErpDocumentAttachments;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Repositories\BaseRepository;
use App\Services\ContractAmendmentService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\CrudOperations;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
/**
 * Class ContractDocumentRepository
 * @package App\Repositories
 * @version May 8, 2024, 5:23 pm +04
*/

class ContractDocumentRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;

    protected $fieldSearchable = [
        'uuid',
        'documentType',
        'documentName',
        'documentDescription',
        'attachedDate',
        'followingRequest',
        'status',
        'receivedBy',
        'receivedDate',
        'receivedFormat',
        'documentVersionNumber',
        'documentResponsiblePerson',
        'documentExpiryDate',
        'returnedBy',
        'returnedDate',
        'returnedTo',
        'companySystemID',
        'created_by',
        'updated_by'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContractDocument::class;
    }

    protected function getModel()
    {
        return new ContractDocument();
    }

    public function createContractDocument($formData): array {
        $documentTypes = $formData['documentTypes'] ?? [];
        $contractUUid = $formData['contractUUid'] ?? null;
        $selectedCompanyID = $formData['selectedCompanyID'] ?? 0;
        $documentTypeId = General::getArrayIds($documentTypes);
        $amendment = $formData['amendment'];
        $model = $amendment ? CMContractDocumentAmd::class : ContractDocument::class;
        $historyId = 0;

        if($amendment)
        {
            $historyData = ContractManagementUtils::getContractHistoryData($formData['contractHistoryUuid']);
            $historyId = $historyData->id;
        }

        $contractMaster = $amendment
            ?
            ContractAmendmentService::getContractAmendment($contractUUid, $historyId)
            :
            ContractManagementUtils::checkContractExist($contractUUid, $selectedCompanyID);

        if(empty($contractMaster)){
            return [
                'status' => false,
                'message' => trans('common.contract_not_found')
            ];
        }

        DB::beginTransaction();
        try{
            $insertDocument = self::getInsertData($contractMaster, $documentTypeId, $selectedCompanyID, $historyId);

            if(count($insertDocument) > 0)
            {
                $model::insert($insertDocument);
                DB::commit();
                return [
                    'status' => true,
                    'message' => trans('common.contract_document_created_successfully')
                ];
            } else{
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => trans('common.something_went_wrong')
                ];
            }

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }
    private function getInsertData($contractMaster, $documentTypeId, $selectedCompanyID, $historyId): array {
        $insertDocument = [];
        $count = 0;
        foreach($documentTypeId as $id){
            $uuid = bin2hex(random_bytes(16));
            $documentType = DocumentMaster::select('id')->where('uuid', $id)
                ->where('companySystemID', $selectedCompanyID)->first();

            if($documentType) {
                $insertDocument[$count] = [
                    'uuid' => $uuid,
                    'contractID' => $contractMaster['id'],
                    'documentMasterID' => 121,
                    'documentType' => $documentType['id'],
                    'companySystemID' => $selectedCompanyID,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];
                if($historyId > 0)
                {
                    $insertDocument[$count]['contract_history_id'] = $historyId;
                }
                $count++;
            }
        }
        return $insertDocument;
    }
    public function getContractDocumentList(Request $request) {
        $input  = $request->all();
        $selectedCompanyID =  $input['selectedCompanyID'];
        $contractUUid = $input['contractUUid'] ?? null;
        $contractMaster = ContractManagementUtils::checkContractExist($contractUUid, $selectedCompanyID);
        $contractID = $contractMaster['id'] ?? 0;
        $amenment = $input['amendment'];
        $languages =  $amenment
            ?
            CMContractDocumentAmd::contractDocuments($selectedCompanyID, $contractID, $input['contractHistoryUuid'])
            :
            $this->model->contractDocuments($selectedCompanyID, $contractID);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }
    public function updateDocumentHeader($formData, $contractDocumentID): array {
        $documentTypeUuid = $formData['documentTypeUuid'] ?? 1;
        $attachedDate = $formData['formattedAttachedDate'] ?? null;
        $selectedCompanyID = $formData['selectedCompanyID'] ?? null;
        $contractUuid = $formData['contractUuid'] ?? null;
        $amendment = $formData['amendment'];
        $model = $amendment ? CMContractDocumentAmd::class : ContractDocument::class;

        $documentTypeID = DocumentMaster::select('id')
            ->where('uuid', $documentTypeUuid)
            ->where('companySystemID', $selectedCompanyID)->first();
        if(empty($documentTypeID))
        {
            return [
                'status' => false,
                'message' => trans('common.document_type_not_found')
            ];
        }
        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $selectedCompanyID)
            ->first();
        if(empty($contractMaster)) {
            return [
                'status' => false,
                'message' => trans('common.contract_not_found')
            ];
        }

        if(!$amendment)
        {
            $checkHeaderValid = self::checkHeaderValid($formData, $contractMaster['id'], $contractDocumentID);
            if(!$checkHeaderValid['status']){
                return $checkHeaderValid;
            }
        }


        DB::beginTransaction();
        try{
            $updateData = [
                'documentName' => $formData['documentName'] ?? null,
                'documentDescription' => $formData['documentDescription'] ?? null,
                'attachedDate' => $attachedDate != null ? Carbon::parse($attachedDate)->format('Y-m-d'): null,
                'followingRequest' => $formData['followingRequest'] ?? 1,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            $model::where('id', $contractDocumentID)->update($updateData);
            DB::commit();
            return [
                'status' => true,
                'message' => trans('common.contract_document_updated_successfully')
            ];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }

    }

    private function checkHeaderValid($formData, $contractID, $contractDocumentID){
        $documentDescription = $formData['documentDescription'] ?? null;
        $documentName = $formData['documentName'] ?? null;
        if($documentDescription != null &&
            ContractDocument::where('documentDescription', $documentDescription)
                ->where('contractID', $contractID)
                ->where('id', '!=', $contractDocumentID)
                ->exists()
        )

        {
            return [
                'status' => false,
                'message' => trans('common.document_description_already_exists')
            ];
        }
        if($documentName != null &&
            ContractDocument::where('documentName', $documentName)
                ->where('contractID', $contractID)
                ->where('id', '!=', $contractDocumentID)
                ->exists()
        ) {
            return [
                'status' => false,
                'message' => trans('common.document_name_already_exists')
            ];
        }
        return [
            'status' => true,
            'message' => trans('common.validation_checked_successfully')
        ];
    }

    public function updateDocumentReceived(Request $request): array{
        $formData = $request->all();
        $file = $formData['file'] ?? null;
        $amendment = $formData['amendment'];
        $checkValidation = self::checkValidation($formData);

        if(!$checkValidation['status'])
        {
            return [
                'status' => false,
                'message' =>  $checkValidation['message'],
                'code' => $checkValidation['code'] ?? 422
            ];
        }
        $companySystemID = $formData['selectedCompanyID'] ?? 0;
        $historyId =0;

        $modelName = ContractDocument::class;

        if($amendment)
        {
            $historyData = ContractManagementUtils::getContractHistoryData($formData['contractHistoryUuid']);
            $historyId = $historyData->id;
        }

        $contractDocument = $amendment
            ?
                 ContractAmendmentService::getcontractDocumentDataAmd($historyId,$formData['uuid'])
            :
            ContractDocument::select('id')
            ->where('uuid', $formData['uuid'])
            ->where('companySystemID', $companySystemID)->first();



        if(empty($contractDocument))
        {
            return [
                'status' => false,
                'message' =>  trans('common.contract_document_not_found'),
                'code' => 404
            ];
        }

        if(!empty($file))
        {

            if($amendment)
            {
                $deleteAttachment = ErpDocumentAttachmentsAmd::deleteAttachment($formData['documentMasterID'],
                    $contractDocument['id'],$historyId);
                $modelName = CMContractDocumentAmd::class;
            }else
            {
                $deleteAttachment = ErpDocumentAttachments::deleteAttachment($formData['documentMasterID'],
                    $contractDocument['id']);
                $modelName = ContractDocument::class;
            }

            if(!$deleteAttachment['status']) {
                return [
                    'status' => false,
                    'message' =>  $deleteAttachment['message'],
                    'code' => 404
                ];
            }
        }

        DB::beginTransaction();
        try{
            $updateData = self::getDocumentReceivedUpdateData($formData);
            $modelName::where('id', $contractDocument['id'])->update($updateData);
            DB::commit();
            return [
                'status' => true,
                'message' => trans('common.contract_document_updated_successfully')
            ];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }
    private function getDocumentReceivedUpdateData($formData): array {
        $expiryDate = $formData['formattedDocumentExpiryDate'] ?? null;
        return [
            'receivedBy' => $formData['receivedBy'],
            'receivedDate' => Carbon::parse($formData['formattedReceivedDate'])->format('Y-m-d H:i:s'),
            'receivedFormat' => $formData['receivedFormat'] ?? null,
            'documentVersionNumber' => $formData['documentVersionNumber'] ?? 0,
            'documentResponsiblePerson' => $formData['documentResponsiblePerson'] ?? null,
            'documentExpiryDate' => $expiryDate ? Carbon::parse($expiryDate)->format('Y-m-d') : null,
            'status' => 1,
            'updated_by' => General::currentEmployeeId(),
            'updated_at' => Carbon::now()
        ];
    }
    private function checkValidation($formData): array {
        $messages = [
            'uuid.required' => trans('common.contract_document_id_not_found'),
            'receivedBy.required' => trans('common.received_by_is_required'),
            'formattedReceivedDate.required' => trans('common.received_date_and_time_is_required')
        ];

        $validator = Validator::make($formData, [
            'uuid' => 'required',
            'receivedBy' => 'required',
            'formattedReceivedDate' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => $validator->errors(),
                'code' => 422
            ];
        }

        if(!$formData['attachment'] && !$formData['file']) {
            return [
                'status' => false,
                'message' => trans('common.attach_document_is_required'),
                'code' => 404
            ];
        }

        return [
          'status' => true,
          'message' => trans('common.document_received_validation_success')
        ];
    }

    public function getEditData($id)
    {
        return ContractDocument::select('uuid', 'documentMasterID',
            'receivedBy', 'receivedDate', 'receivedFormat', 'documentVersionNumber', 'documentResponsiblePerson',
            'documentExpiryDate', 'documentName', 'documentDescription', 'returnedBy', 'returnedDate', 'returnedTo')
            ->with([
                'attachment' => function ($q) use ($id){
                    $q->select('attachmentID', 'myFileName', 'documentSystemID', 'documentSystemCode')
                    ->where('documentSystemCode', $id);
                }
            ])
            ->where('id', $id)
            ->first();
    }
    public function updateDocumentReturned(Request $request): array {
        $formData = $request->all();
        $amendment = $formData['amendment'];
        $model = $amendment ? CMContractDocumentAmd::class : COntractDocument::class;
        $historyId = 0;
        if(!$amendment)
        {
            $returnValidation = self::checkReturnValidation($formData);

            if(!$returnValidation['status']) {
                return [
                    'status' => false,
                    'message' =>  $returnValidation['message'],
                    'code' => $returnValidation['code'] ?? 422
                ];
            }
        }


        $companySystemID = $formData['selectedCompanyID'] ?? 0;

        if($amendment)
        {
            $contractHistoryData = ContractManagementUtils::getContractHistoryData($formData['contractHistoryUuid']);
            $historyId = $contractHistoryData->id;
        }



        $contractDocument = $amendment

            ?
            ContractAmendmentService::getcontractDocumentDataAmd($historyId,$formData['uuid'])
            :
            ContractDocument::select('id')
            ->where('uuid', $formData['uuid'])
            ->where('companySystemID', $companySystemID)->first();


        if(empty($contractDocument)) {
            return [
                'status' => false,
                'message' =>  trans('common.contract_document_not_found'),
                'code' => 404
            ];
        }
        DB::beginTransaction();
        try{
            $updateData = [
                'returnedBy' => $formData['returnedBy'],
                'returnedTo' => $formData['returnedTo'] ?? null,
                'returnedDate' => Carbon::parse($formData['formattedReturnedDate'])->format('Y-m-d H:i:s'),
                'status' => 2
            ];
            $model::where('id', $contractDocument['id'])->update($updateData);
            DB::commit();
            return [
                'status' => true,
                'message' => trans('common.contract_document_updated_successfully')
            ];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }
    private function checkReturnValidation($formData): array {
        $messages = [
            'uuid.required' => trans('common.contract_document_id_not_found'),
            'returnedBy.required' => trans('common.returned_by_is_required'),
            'formattedReturnedDate.required' => trans('common.returned_date_time_is_required'),
        ];

        $validator = Validator::make($formData, [
            'uuid' => 'required',
            'returnedBy' => 'required',
            'formattedReturnedDate' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => $validator->errors(),
                'code' => 422
            ];
        }
        return [
            'status' => true,
            'message' => trans('common.document_received_validation_success')
        ];
    }

    public function getcontractDocumentData($contractId)
    {
        return $this->model->getcontractDocumentData($contractId);
    }
}
