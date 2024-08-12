<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Interfaces\AdditionalDocumentRepositoryInterface;
use App\Models\CMContractMasterAmd;
use App\Models\ContractAdditionalDocumentAmd;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractMaster;
use App\Models\DocumentMaster;
use App\Models\ErpDocumentAttachments;
use App\Repositories\BaseRepository;
use App\Services\ContractAmendmentService;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Traits\CrudOperations;
use Exception;
/**
 * Class ContractAdditionalDocumentsRepository
 * @package App\Repositories
 * @version May 15, 2024, 5:23 am +04
*/

class ContractAdditionalDocumentsRepository extends BaseRepository implements AdditionalDocumentRepositoryInterface
{
    /**
     * @var array
     */
    use CrudOperations;

    protected $erpDocumentAttachmentsRepository;
    public function __construct(Application $app, ErpDocumentAttachmentsRepository  $erpDocumentAttachmentsRepository)
    {
        parent::__construct($app);
        $this->erpDocumentAttachmentsRepository = $erpDocumentAttachmentsRepository;
    }

    protected $fieldSearchable = [
        'uuid',
        'contractID',
        'documentMasterID',
        'documentType',
        'documentName',
        'documentDescription',
        'expiryDate',
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
        return ContractAdditionalDocuments::class;
    }
    protected function getModel()
    {
        return new ContractAdditionalDocuments();
    }
    public function additionalDocumentList(Request $request){
        $input = $request->all();
        $selectedCompanyID =  $input['selectedCompanyID'];
        $contractUUid = $input['contractUUid'] ?? null;

        $amendment = $input['amendment'] ?? false;

        if($amendment)
        {
            $languages = ContractAmendmentService::additionalDocumentList
            (
                $selectedCompanyID,$contractUUid,$input['contractHistoryUuid']
            );


        }else
        {
            $contractMaster = ContractManagementUtils::checkContractExist($contractUUid, $selectedCompanyID);
            $contractID = $contractMaster['id'] ?? 0;
            $languages =  $this->model->additionalDocumentList($selectedCompanyID, $contractID);
        }




        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }

    public function createAdditionalDocument($request)
    {
        try
        {
            DB::transaction(function () use ($request)
            {
                $input = $request->all();
                $companyId = $input['selectedCompanyID'];
                $documentTypeByUuid = DocumentMaster::documentMasterByUuid($input['documentTypes']);
                $contractData = ContractManagementUtils::checkContractExist(
                    $input['contractUUid'], $companyId
                );

                if (!$contractData)
                {
                    GeneralService::sendException('Contract Not Found');
                }

                $insertData = [
                    'uuid' => ContractManagementUtils::generateUuid(),
                    'contractID' => $contractData->id,
                    'documentMasterID' => $input['documentMasterID'],
                    'documentType' => $documentTypeByUuid->id,
                    'documentName' => $input['documentName'],
                    'companySystemID' => $companyId,
                ];

                if (isset($input['documentExpiryDate']) && $input['documentExpiryDate'])
                {
                    $insertData['expiryDate'] = new Carbon($input['documentExpiryDate']);
                }


                $insert = $this->model->create($insertData);

                if (isset($input['file']))
                {
                    $attachment = $this->erpDocumentAttachmentsRepository->saveDocumentAttachments
                    (
                        $request, $insert->id
                    );

                    if (!$attachment['status'])
                    {
                        GeneralService::sendException($attachment['message']);
                    }
                }
            });
        }
        catch (Exception $e)
        {
            GeneralService::sendException('Failed to create Document tracing', $e);
        }
    }
    private function getInsertData($contractMaster, $documentTypeId, $selectedCompanyID,$historyId): array {
        $insertAdditionalDoc = [];
        $count = 0;
        foreach($documentTypeId as $id){
            $uuid = bin2hex(random_bytes(16));
            $documentType = DocumentMaster::select('id')->where('uuid', $id)
                ->where('companySystemID', $selectedCompanyID)->first();

            if($documentType) {
                $insertAdditionalDoc[$count] = [
                    'uuid' => $uuid,
                    'contractID' => $contractMaster['id'],
                    'documentMasterID' => 122,
                    'documentType' => $documentType['id'],
                    'companySystemID' => $selectedCompanyID,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];
                if($historyId > 0)
                {
                    $insertAdditionalDoc[$count]['contract_history_id'] = $historyId;
                }
                $count++;
            }
        }
        return $insertAdditionalDoc;
    }
    public function updateAdditionalDocumentHeader($formData, $additionalDocID): array{
        $documentTypeUuid = $formData['documentTypeUuid'] ?? 1;
        $expiryDate = $formData['formattedExpiryDate'] ?? null;
        $selectedCompanyID = $formData['selectedCompanyID'] ?? null;
        $contractUuid = $formData['contractUuid'] ?? null;
        $amendment = $formData['amendment'];
        $model = $amendment ? ContractAdditionalDocumentAmd::class : ContractAdditionalDocuments::class;
        $documentTypeID = DocumentMaster::select('id')
            ->where('uuid', $documentTypeUuid)
            ->where('companySystemID', $selectedCompanyID)->first();
        if(empty($documentTypeID)) {
            return [
                'status' => false,
                'message' => trans('common.document_type_not_found')
            ];
        }

        if($amendment)
        {
            $getHistoryData = ContractManagementUtils::getContractHistoryData($formData['contractHistoryUuid']);
            $contractMaster =  CMContractMasterAmd::getContractMasterData($getHistoryData->id);
        }else
        {
            $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
                ->where('companySystemID', $selectedCompanyID)
                ->first();
        }




        if(empty($contractMaster)) {
            return [
                'status' => false,
                'message' => trans('common.contract_not_found')
            ];
        }

        if(!$amendment)
        {
            $checkHeaderValid = self::checkHeaderValid($formData, $contractMaster['id'], $additionalDocID);
            if(!$checkHeaderValid['status']){
                return $checkHeaderValid;
            }
        }


        DB::beginTransaction();
        try{
            $updateData = [
                'documentName' => $formData['documentName'] ?? null,
                'documentDescription' => $formData['documentDescription'] ?? null,
                'expiryDate' => $expiryDate != null ? Carbon::parse($expiryDate)->format('Y-m-d'): null,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            $model::where('id', $additionalDocID)->update($updateData);
            DB::commit();
            return [
                'status' => true,
                'message' => trans('common.contract_additional_document_updated_successfully')
            ];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }

    private function checkHeaderValid($formData, $contractID, $additionalDocID){
        $documentDescription = $formData['documentDescription'] ?? null;
        $documentName = $formData['documentName'] ?? null;
        if($documentDescription != null &&
            ContractAdditionalDocuments::where('documentDescription', $documentDescription)
                ->where('contractID', $contractID)
                ->where('id', '!=', $additionalDocID)
                ->exists()
        ) {
            return [
                'status' => false,
                'message' => trans('common.document_description_already_exists')
            ];
        }
        if($documentName != null &&
            ContractAdditionalDocuments::where('documentName', $documentName)
                ->where('contractID', $contractID)
                ->where('id', '!=', $additionalDocID)
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
    public function deleteFile($documentMasterID, $additionalDocumentID): array{
        $deleteAttachment = ErpDocumentAttachments::deleteAttachment($documentMasterID,
            $additionalDocumentID);
        if(!$deleteAttachment['status']) {
            return [
                'status' => false,
                'message' =>  $deleteAttachment['message'],
                'code' => 404
            ];
        }
        return [
            'status' => true,
            'message' =>  trans('common.attachment_successfully_deleted')
        ];
    }

    public function additionalDocumentData($contractId)
    {
        return $this->model->additionalDocumentData($contractId);
    }

    public function getAdditionalDocumentByUuid($request)
    {
        try
        {
            $input = $request->all();
            $contractDocument = ContractAdditionalDocuments::fetchByUuid($input['additionalDocumentUuid']);
            if (!$contractDocument)
            {
                GeneralService::sendException('Contract Document Not Found');
            }

            return $contractDocument;
        }
        catch (\Exception $ex)
        {
            GeneralService::sendException('Failed to get contract document data', $ex);
        }
    }

    public function deleteContractDocument($request)
    {
        try
        {
            DB::transaction(function () use ($request)
            {
                $input = $request->all();
                $additionalDocumentuuid = $input['contractDocumentUuid'];

                $additionalDocument = $this->model()::where('uuid', $additionalDocumentuuid)->first();

                if (!$additionalDocument)
                {
                    GeneralService::sendException('Contract document not found.'. $additionalDocumentuuid);
                }

                $additionalDocument->update([
                    'expiryDate' => null,
                ]);

                $this->erpDocumentAttachmentsRepository->deleteDocumentAttachment(
                    $input['attachmentID'], $input['path']
                );
            });

        } catch (\Exception $e)
        {
            GeneralService::sendException('Failed to Delete Document tracing data', $e);
        }
    }

    public function updateAdditionalDoc($request)
    {
        try
        {
            DB::transaction(function () use ($request)
            {
                $input = $request->all();
                $documentTypeByUuid = DocumentMaster::documentMasterByUuid($input['documentTypes']);

                $contractDocument = $this->model()::where('uuid', $input['uuid'])->first();

                if (isset($input['documentExpiryDate']) && $input['documentExpiryDate'])
                {
                    $contractDocument['expiryDate'] = new Carbon($input['documentExpiryDate']);
                }
                else
                {
                    $contractDocument['expiryDate']  = null;
                }

                $contractDocument->update([
                    'documentType' => $documentTypeByUuid->id,
                    'documentName' => $input['documentName'],
                    'documentDescription' => $input['documentDescription']
                ]);


                if(isset($input['fileUpload']) && $input['fileUpload'])
                {
                    $attachment = $this->erpDocumentAttachmentsRepository->saveDocumentAttachments
                    (
                        $request, $contractDocument->id
                    );

                    if (!$attachment['status'])
                    {
                        GeneralService::sendException($attachment['message']);
                    }
                }
            });
        }
        catch (Exception $e)
        {
            GeneralService::sendException('Failed to update Document Contract ', $e);
        }
    }
}
