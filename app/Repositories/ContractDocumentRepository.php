<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractDocument;
use App\Models\DocumentMaster;
use App\Models\ErpDocumentAttachments;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\CrudOperations;
use Illuminate\Support\Facades\Validator;

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

        $contractMaster = ContractManagementUtils::checkContractExist($contractUUid, $selectedCompanyID);
        if(empty($contractMaster)){
            return [
                'status' => false,
                'message' => trans('common.contract_not_found')
            ];
        }


        DB::beginTransaction();
        try{
            $insertDocument = self::getInsertData($contractMaster, $documentTypeId, $selectedCompanyID);

            if(count($insertDocument) > 0) {
                ContractDocument::insert($insertDocument);
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
    private function getInsertData($contractMaster, $documentTypeId, $selectedCompanyID): array {
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
        $languages =  $this->model->contractDocuments($selectedCompanyID, $contractID);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->order(function ($query) use ($input) {
                if (request()->has('order') && $input['order'][0]['column'] == 0) {
                    $query->orderBy('id', $input['order'][0]['dir']);
                }
            })
            ->addIndexColumn()
            ->make(true);
    }
    public function updateDocumentHeader($formData, $contractDocumentID): array {
        $documentTypeUuid = $formData['documentTypeUuid'] ?? 1;
        $attachedDate = $formData['formattedAttachedDate'] ?? null;
        $selectedCompanyID = $formData['selectedCompanyID'] ?? null;

        $documentTypeID = DocumentMaster::select('id')
            ->where('uuid', $documentTypeUuid)
            ->where('companySystemID', $selectedCompanyID)->first();
        if(empty($documentTypeID)) {
            return [
                'status' => false,
                'message' => trans('common.document_type_not_found')
            ];
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

            ContractDocument::where('id', $contractDocumentID)->update($updateData);
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
    public function updateDocumentReceived(Request $request): array{
        $formData = $request->all();
        $file = $formData['file'] ?? null;
        $checkValidation = self::checkValidation($formData);
        if(!$checkValidation['status']) {
            return [
                'status' => false,
                'message' =>  $checkValidation['message'],
                'code' => $checkValidation['code'] ?? 422
            ];
        }
        $companySystemID = $formData['selectedCompanyID'] ?? 0;
        $contractDocument = ContractDocument::select('id')
            ->where('uuid', $formData['uuid'])
            ->where('companySystemID', $companySystemID)->first();
        if(empty($contractDocument)) {
            return [
                'status' => false,
                'message' =>  trans('common.contract_document_not_found'),
                'code' => 404
            ];
        }
        if(!empty($file)) {
            $deleteAttachment = ErpDocumentAttachments::deleteAttachment($formData['documentMasterID'],
                $contractDocument['id']);
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
            ContractDocument::where('id', $contractDocument['id'])->update($updateData);
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
            'formattedReceivedDate.required' => trans('common.received_date_and_time_is_required'),
            'documentVersionNumber.regex' => trans('common.document_version_number_validation_message'),
        ];

        $validator = Validator::make($formData, [
            'uuid' => 'required',
            'receivedBy' => 'required',
            'formattedReceivedDate' => 'required',
            'documentVersionNumber' => ['regex:/^\d+(\.\d{1,3})?$/']
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
        $returnValidation = self::checkReturnValidation($formData);

        if(!$returnValidation['status']) {
            return [
                'status' => false,
                'message' =>  $returnValidation['message'],
                'code' => $returnValidation['code'] ?? 422
            ];
        }
        $companySystemID = $formData['selectedCompanyID'] ?? 0;
        $contractDocument = ContractDocument::select('id')
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
            ContractDocument::where('id', $contractDocument['id'])->update($updateData);
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
}
