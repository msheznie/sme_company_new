<?php
namespace App\Services;

use App\Exceptions\CommonException;
use App\Helpers\ApproveDocument;
use App\Helpers\ConfirmDocument;
use App\Helpers\General;
use App\Helpers\RejectDocument;
use App\Models\CMContractMasterAmd;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Models\ContractMaster;
use App\Models\contractStatusHistory;
use App\Models\ErpDocumentApproved;
use App\Models\ErpDocumentAttachments;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Repositories\ContractHistoryRepository;
use App\Repositories\contractStatusHistoryRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ContractCreationException;
use Illuminate\Support\Facades\Log;
class ContractHistoryService
{
    protected $contractHistoryRepository;
    protected $contractStatusHistoryRepository;
    protected $contractHistoryModel = 'App\\Models\\ContractHistory';

    public function __construct(ContractHistoryRepository $contractHistoryRepository,
                                contractStatusHistoryRepository $contractStatusHistoryRepository)
    {
        $this->contractHistoryRepository = $contractHistoryRepository;
        $this->contractStatusHistoryRepository = $contractStatusHistoryRepository;
    }
    public function deleteContractHistory($input)
    {
        try
        {
            return DB::transaction(function () use ($input)
            {
                $uuid = $input['contractUuid'];
                $companyId = $input['selectedCompanyID'];
                $categoryId = $input['category'];
                $historyUuid = $input['contractHistoryId'];
                $amendment = $input['amendment'] ?? false;
                $getContractId = ContractManagementUtils::checkContractExist($uuid, $companyId);
                $getContractHistory = ContractManagementUtils::getContractHistoryData($historyUuid);
                if($amendment)
                {
                    $contractDocuments = ContractAmendmentService::getcontractDocumentDataAmd
                    (
                        $getContractHistory->id,null,true
                    );
                    $contractAdditionalDocuments =  ContractAmendmentService::getContractAdditionalDocument
                    (
                        null,$getContractHistory->id,true
                    );

                }else
                {
                    $contractDocuments = ContractDocument::getContractDocuments($getContractId['id'], $companyId);
                    $contractAdditionalDocuments = ContractAdditionalDocuments::getContractAdditionalDocuments(
                        $getContractId['id'], $companyId
                    );

                }
                $this->deleteRelatedModels
                (
                    $getContractId['id'], $companyId, $categoryId , $amendment,$getContractHistory->id
                );
                $this->deleteContractStatus($getContractHistory['id']);
                $this->deleteDocumentAttachments($contractDocuments, $contractAdditionalDocuments, $amendment);
            });
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete contract history: " . $e->getMessage());
        }
    }

    private function deleteRelatedModels($contractId, $companyId, $categoryId, $amendment, $historyId)
    {
        $modelsToDelete = self::getModelsToDelete($categoryId);

        foreach ($modelsToDelete as $modelName)
        {
            $model = new $modelName();

            if($amendment)
            {

                $contractColumn = ($modelName === $this->contractHistoryModel) ? 'id' : 'contract_history_id';
                $colValue = $historyId;
            }else
            {
                $contractIdCol = $modelName::getContractIdColumn();
                $companyColumn = $modelName::getCompanyIdColumn();

                $contractColumn = ($modelName === 'App\\Models\\ContractMaster' ? 'id' : $contractIdCol);
                $colValue = $contractId;
            }


            if ($contractColumn)
            {
                $query = $model::where($contractColumn, $colValue);
                if ($modelName === $this->contractHistoryModel)
                {
                    $query->where('category', $categoryId);
                }

                if (!$amendment && $companyColumn !== null)
                {
                    $query->where($companyColumn, $companyId);
                }

                $query->delete();
            }
        }
    }

    private function deleteDocumentAttachments($contractDocuments, $contractAdditionalDocuments, $amendment)
    {
        $attachmentIds = collect();

        if ($contractDocuments)
        {
            $attachmentIds = $contractDocuments->pluck('id');
        }

        if ($contractAdditionalDocuments)
        {
            $attachmentIds = $attachmentIds->merge($contractAdditionalDocuments->pluck('id'));
        }

        $attachmentIdsArray = $attachmentIds->all();
        $model = $amendment ? ErpDocumentAttachmentsAmd::class : ErpDocumentAttachments::class;

        if (!empty($attachmentIdsArray))
        {
            $model::whereIn('documentSystemID', [121, 122])
                ->whereIn('documentSystemCode', $attachmentIdsArray)
                ->delete();
        }
    }
    public function getCategoryWiseContractData($input)
    {
        $uuid = $input['contractId'];
        $companyId = $input['selectedCompanyID'];
        $contractCategory = $input['contractCategory'];
        $contractData = ContractManagementUtils::checkContractExist($uuid,$companyId);
        if (!$contractData)
        {
            throw new ContractCreationException('Contract not found');
        }
        return ContractHistory::addendumData($contractData->id,$companyId,$contractCategory);
    }

    public static function convertAndFormatInputData($input,$currentContractId)
    {
        $input['id'] = $currentContractId;
        $fieldMappings = [
            'contractType' => [
                'model' => 'App\\Models\\CMContractTypes',
                'function' => 'getContractType',
                'colName' => 'contract_typeId'
            ],

            'counterPartyName' => [
                'model' => 'App\\Models\\ContractUsers',
                'function' => 'getUserData',
                'colName' => 'id'
            ],

            'contractOwner' => [
                'model' => 'App\\Models\\ContractUsers',
                'function' => 'getUserData',
                'colName' => 'id'
            ]
        ];

        $convertedIds = [];

        foreach ($fieldMappings as $inputField => $mapping)
        {
            if (isset($input[$inputField]))
            {
                $id =  self::getIdFromUuid(
                    $mapping['model'], $input[$inputField],$mapping['function'], $mapping['colName']
                );
                $convertedIds[$inputField] = $id;

            }
        }

        foreach ($convertedIds as $inputField => $id)
        {
            $input[$inputField] = $id;
        }

        if (isset($input['startDate']))
        {
            $input['startDate'] = ContractManagementUtils::convertDate($input['startDate'],true);
        }

        if (isset($input['endDate']))
        {
            $input['endDate'] =  ContractManagementUtils::convertDate($input['endDate'],true);
        }

        if (isset($input['agreementSignDate']))
        {
            $input['agreementSignDate'] =  ContractManagementUtils::convertDate($input['agreementSignDate'],true);
        }

        return $input;
    }

    private static function getIdFromUuid($model, $uuid, $function, $colName)
    {
        $data =  new $model();
        $result = $data->$function($uuid);
        return $result ? $result->$colName : null;
    }

    public function updateContractStatus($input)
    {
        try
        {
            return DB::transaction(function () use ($input)
            {
                $contractId = $input['contractId'];
                $companyId = $input['selectedCompanyID'];
                $categoryId = $input['category'];
                $contractHistoryUuid = $input['contractHistoryId'];
                $contractCloneUuid = $input['cloneContractId'];
                $getContractId = ContractManagementUtils::checkContractExist($contractId, $companyId);
                $getContractCloneData = ContractManagementUtils::checkContractExist($contractCloneUuid, $companyId);
                $getContractHistoryData = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
                if (!$getContractId)
                {
                    throw new ContractCreationException(trans('common.contract_does_not_exist'));
                }

                $contractId = $getContractId->id;
                $contractHistoryId  = $getContractHistoryData->id;

                $endDate = Carbon::parse($getContractCloneData['endDate'])
                    ->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                $cloneStatus = self::checkContractDateBetween
                (
                    $getContractCloneData['startDate'], $endDate
                );


                $cloneStatus = ($categoryId == 6) ? $categoryId : $cloneStatus;
                self::updateContractMaster($contractId, $companyId,$categoryId);

                if($categoryId == 2 || $categoryId == 3 || $categoryId == 5)
                {
                     ContractHistoryService::updateOrInsertStatus
                    (
                         $getContractCloneData->id, $cloneStatus, $getContractCloneData->companySystemID,
                         $contractHistoryId
                    );
                }
                else
                {
                    self::updateContractHistoryStatus($getContractCloneData['id'],$contractHistoryId,$cloneStatus);

                }

                self::updateContractMaster($getContractCloneData['id'], $companyId,$cloneStatus);
                self::updateContractHistory($contractHistoryId, $companyId, $categoryId);
                self::insertHistoryStatus($contractId,$categoryId,$companyId, $contractHistoryId);

                if($categoryId === 6)
                {
                    contractStatusHistory::updateTerminatedAddendum($contractId, $companyId,$categoryId);
                }

            });
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException(trans('common.failed_to_update_contract_status: ' . $e->getMessage()));
        }
    }

    public function updateContractMaster($contractId, $companyId,$status)
    {
        try
        {
            $data = [
                'status'  => $status
            ];

            ContractMaster::where('companySystemID', $companyId)
                ->where('id', $contractId)
                ->update($data);
        }

        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update ContractMaster: " . $e->getMessage());
        }
    }

    public function updateContractHistory($historyID, $companyId, $categoryId = null)
    {
        try
        {
            $data = [
                'date'  => carbon::now()->format('Y-m-d'),
                'status'  => $categoryId,
            ];

            ContractHistory::where('company_id', $companyId)
                ->where('id', $historyID)
                ->update($data);
        }

        catch (\Exception $e)
        {
            throw new ContractCreationException(trans('common.failed_to_update_contract: ' . $e->getMessage()));
        }

    }

    public function confirmHistoryDocument($historyId,$contractId,$companySystemId,$contractCategoryId)
    {
        return DB::transaction( function() use ($historyId,$contractId,$companySystemId,$contractCategoryId)
        {
            if($contractCategoryId == 4)
            {
                $documentSystemID = 125;
            }
            if($contractCategoryId == 6)
            {
                $documentSystemID = 124;
            }
            if($contractCategoryId == 1)
            {
                $documentSystemID = 126;
            }


            if ($contractCategoryId == 1)
            {
                $contractMaster = CMContractMasterAmd::where('contract_history_id', $historyId)
                    ->where('id', $contractId)
                    ->where('companySystemID', $companySystemId)
                    ->first();
            } else
            {
                $contractMaster = ContractMaster::where('id', $contractId)
                    ->where('companySystemID', $companySystemId)
                    ->first();
            }

            $historyMaster = ContractHistory::where('id', $historyId)
                ->where('company_id', $companySystemId)
                ->first();

            if(!$contractMaster)
            {
                throw new CommonException(trans('common.contract_not_found'));
            }

            if(!$historyMaster)
            {
                throw new CommonException(trans('common.contract_history_not_found'));
            }

            if($contractMaster['contractAmount'] == 0)
            {
                throw new CommonException(trans('common.contract_amount_is_a_mandatory_field'));
            }

            $insertData = [
                'autoID' => $historyId,
                'company' => $companySystemId,
                'document' => $documentSystemID,
                'documentCode' => $contractMaster['contractCode'] ?? null,
                'amount' => $contractMaster['contractAmount'] ?? 0
            ];
            return ConfirmDocument::confirmDocument($insertData, $historyMaster);

        });
    }

    public function getContractApprovals(Request $request)
    {
        $search = $request->input('search.value');
        $isPending = $request->input('isPending') ?? 0;
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        $categoryId = $request->input('categoryId') ?? null;
        $approvals = $this->getApprovalData($isPending, $selectedCompanyID, $search, $categoryId);

        return \DataTables::of($approvals)
            ->addIndexColumn()
            ->make(true);
    }

    public function getApprovalData($isPending, $selectedCompanyID, $search, $categoryId)
    {
        if($categoryId ==  1)
        {
            $historyMaster = ContractHistory::getExtendContractApprovals(
                $isPending,
                $selectedCompanyID,
                $search,
                General::currentEmployeeId(),
                126
            );
        } elseif($categoryId ==  4)
        {
            $historyMaster = ContractHistory::getExtendContractApprovals(
                $isPending,
                $selectedCompanyID,
                $search,
                General::currentEmployeeId(),
                125
            );
        } else
        {
            $historyMaster = ContractHistory::getExtendContractApprovals(
                $isPending,
                $selectedCompanyID,
                $search,
                General::currentEmployeeId(),
                124
            );
        }

        $checkEmployeeDischarged = General::checkEmployeeDischargedYN();
        if ($checkEmployeeDischarged && $isPending == 1)
        {
            $historyMaster = [];
        }
        return $historyMaster;
    }

    public function approveContract($request)
    {
        $input = $request->all();

        return DB::transaction(function () use ( $input )
        {
            $contractHistoryUuid = $input['contractHistoryUuid'] ?? null;
            $contractHistoryMaster = ContractHistory::where('uuid', $contractHistoryUuid)->first();
            if(empty($contractHistoryMaster))
            {
                throw new CommonException(trans('common.contract_not_found'));
            }
            return ApproveDocument::approveDocument($input, $contractHistoryMaster);
        });
    }

    public function rejectContract(Request $request)
    {
        $input = $request->all();

        return DB::transaction(function () use ($input)
        {
            $contractHistoryUuid = $input['contractHistoryUuid'] ?? null;
            $contractHistoryMaster = ContractHistory::where('uuid', $contractHistoryUuid)->first();
            if (empty($contractHistoryMaster))
            {
                throw new CommonException(trans('common.contract_not_found'));
            }

            return RejectDocument::rejectDocument($input, $contractHistoryMaster);
        });
    }

    public function updateExtendStatus($input)
    {
        try
        {
            return DB::transaction(function () use ($input)
            {
                $contractId = $input['contractId'];
                $contractHistoryUuid = $input['contractHistoryId'];
                $companyId = $input['selectedCompanyID'];
                $getContractId = ContractManagementUtils::checkContractExist($contractId, $companyId);
                $checkHistoryExists = ContractHistory::getContractHistory($contractHistoryUuid, $companyId);
                if(empty($checkHistoryExists))
                {
                    throw new ContractCreationException('Contract history not found');
                }
                if (!$getContractId)
                {
                    throw new ContractCreationException(trans('common.contract_does_not_exist'));
                }

                $contractId = $getContractId->id;
                self::updateContractMasterEndDate
                ($contractId, $input, $checkHistoryExists['id'],$getContractId['endDate']);
            });
        }catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update status: " . $e->getMessage());
        }
    }

    public function updateContractMasterEndDate
    ($contractId, $input, $id, $endDate)
    {
        try
        {
            $contractHistoryUuid = $input['contractHistoryId'];
            $contractEndDate = $input['contractEndDate'];
            $companyId = $input['selectedCompanyID'];
            $status = $input['category'];
            $newContractTermPeriod = $input['newContractTermPeriod'];
            $data = [
                'endDate'  => ContractManagementUtils::convertDate($contractEndDate),
                'status'  => $status,
                'is_extension'  => 1,
                'contractTermPeriod'  => $newContractTermPeriod,
            ];

            ContractMaster::where('companySystemID', $companyId)
                ->where('id', $contractId)
                ->update($data);

            $status = [
                'status'  => 4,
                'end_date'  => $endDate,
            ];

            ContractHistory::where('uuid', $contractHistoryUuid)
                ->update($status);
            ContractHistoryService::insertHistoryStatus($contractId,4,$companyId, $id);
        }

        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update ContractMaster: " . $e->getMessage());
        }
    }

    public function getCategoryWiseContractCount($contractMasterData,$comapnyId)
    {
        $category = self::getContractCategory($contractMasterData);

        if($category == 0)
        {
            return 0;
        }

        $data =
            [
                'contractId' => $contractMasterData->parent_id,
                'category' => $category,
                'companyId'=>$comapnyId
            ];

        return $this->contractHistoryRepository->getCategoryWiseData($data);
    }

    protected function getContractCategory($contractMaster) : int
    {
        if ($contractMaster['is_addendum'])
        {
            return 2;
        }

        return 0;
    }
    public function contractHistoryDelete($input)
    {
        try
        {
            return DB::transaction(function () use ($input)
            {
                $contractHistoryUuid = $input['contractHistoryUuid'];
                $companyId = $input['selectedCompanyID'];
                $historyId = ContractHistory::getContractHistory($contractHistoryUuid, $companyId);

                if (!empty($historyId))
                {
                    ContractHistory::where('id', $historyId->id)->delete();
                }

                if($historyId->category == 4)
                {
                    $documentSystemID = 125;
                }
                if($historyId->category == 6)
                {
                    $documentSystemID = 124;
                }

                $deleteFiles = ErpDocumentAttachments::getAttachmentData($documentSystemID, $historyId->id);

                if($deleteFiles->isNotEmpty())
                {
                    $attachmentIDs = $deleteFiles->pluck('attachmentID')->toArray();
                    ErpDocumentAttachments::whereIn('attachmentID', $attachmentIDs)->delete();
                }
            });
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete contract history: " . $e->getMessage());
        }
    }

    public static function getCategories()
    {
        return  [
            1 => 'is_amendment',
            2 => 'is_addendum',
            3 => 'is_renewal',
            4 => 'is_extension',
            5 => 'is_revision',
            6 => 'is_termination',
        ];

    }

    public static function insertHistoryStatus($contractId, $status, $companySystemID, $contractHistoryId = null,
                                               $systemUser=false)
    {
        try
        {
            return DB::transaction(function () use ($contractId,$status, $companySystemID, $contractHistoryId,
                $systemUser)
            {
                $insert = [
                    'contract_id' => $contractId,
                    'status' => $status,
                    'company_id' => $companySystemID,
                    'created_at' => Carbon::now()
                ];

                if($systemUser)
                {
                    $insert['system_user'] = 1;
                }else
                    $insert['created_by'] = General::currentEmployeeId();

                if ($contractHistoryId!=null)
                {
                    $insert['contract_history_id'] = $contractHistoryId;
                }

                contractStatusHistory::create($insert);
            });
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to insert contract statuss: " . $e->getMessage());
        }
    }
    public static function checkContractDateBetween($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $currentDate = Carbon::now();

        if ($currentDate->lt($startDate))
        {
            return 0;
        } elseif ($currentDate->between($startDate, $endDate))
        {
            return -1;
        } else
        {
            return 7;
        }
    }

    public static function deleteContractStatus($id)
    {
        try
        {
            contractStatusHistory::where('contract_history_id',$id)
                ->delete();
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete contract status history: " . $e->getMessage());
        }

    }

    public static function updateContractHistoryStatus($contractId,$contractHistoryId, $cloneStatus)
    {
        try
        {
            $data = [
                'status'  => $cloneStatus,
            ];

            contractStatusHistory::where('contract_id',$contractId)
                ->where('contract_history_id',$contractHistoryId)
                ->update($data);
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete contract status history: " . $e->getMessage());
        }

    }

    public function getModelsToDelete($categoryId)
    {
        $defaultModels = [
            'App\\Models\\ContractMaster',
            $this->contractHistoryModel,
            'App\\Models\\ContractUserAssign',
            'App\\Models\\ContractSettingMaster',
            'App\\Models\\ContractSettingDetail',
            'App\\Models\\ContractDocument',
            'App\\Models\\ContractAdditionalDocuments',
            'App\\Models\\ContractBoqItems',
            'App\\Models\\ContractMilestone',
            'App\\Models\\ContractDeliverables',
            'App\\Models\\ContractOverallRetention',
            'App\\Models\\ContractMilestoneRetention',
        ];

        if ($categoryId == 1)
        {
            return [
                'App\\Models\\CMContractMasterAmd',
                $this->contractHistoryModel,
                'App\\Models\\CMContractUserAssignAmd',
                'App\\Models\\CMContractDocumentAmd',
                'App\\Models\\CMContractBoqItemsAmd',
                'App\\Models\\CMContractMileStoneAmd',
                'App\\Models\\CMContractDeliverableAmd',
                'App\\Models\\CMContractOverallRetentionAmd',
                'App\\Models\\ContractAmendmentArea',
                'App\\Models\\ContractAdditionalDocumentAmd',
            ];
        }

        return $defaultModels;
    }

    public static function getLatestRecordHistory($id)
    {
        return contractStatusHistory::select('status','id')
            ->where('contract_id',$id)
            ->orderBy('id', 'desc')
            ->first();
    }


    public static function updateHistoryStatus($id)
    {
        try
        {
            return DB::transaction(function () use ($id)
            {
                $data = ['updated_at' => Carbon::now()];
                contractStatusHistory::where('id', $id)
                ->update($data);
            });
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to insert contract statuss: " . $e->getMessage());
        }
    }

    public static function updateOrInsertStatus($id, $status, $selectedCompanyID, $contractHistoryId = null,
                                                $systemUser = false)
    {
        $latestRecordStatus = ContractHistoryService::getLatestRecordHistory($id);
        $alreadyActiveContract = false;
        if($status == -1)
        {
            $alreadyActiveContract = self::checkAlreadyActiveContract($id);
        }

        if ($latestRecordStatus)
        {
            if ($latestRecordStatus->status == $status)
            {
                self::updateHistoryStatus($latestRecordStatus->id);
            }
            else
            {
                if(!$alreadyActiveContract)
                {
                    self::insertHistoryStatus($id, $status, $selectedCompanyID, $contractHistoryId, $systemUser);
                }
            }
        }
        else
        {
            self::insertHistoryStatus($id, $status, $selectedCompanyID, $contractHistoryId , $systemUser);
        }
    }

    public  function getContractStatusHistory($request)
    {
        return $this->contractStatusHistoryRepository->getContractStatusHistory($request);
    }

    public function checkAlreadyActiveContract($contractID)
    {
        return contractStatusHistory::where('contract_id', $contractID)->where('status', -1)->exists();
    }

    public function getContractStatusData($masterRecord)
    {
        return ContractMaster::select('uuid', 'id', 'companySystemID', 'parent_id')
            ->with(['parent:id,uuid', 'history' => function ($query) use ($masterRecord)
            {
                $query->select('uuid', 'category', 'cloning_contract_id', 'company_id', 'contract_id')
                    ->where('cloning_contract_id', $masterRecord['parent_id'])
                    ->where('company_id', $masterRecord['companySystemID']);
            }])
            ->whereHas('history', function ($query) use ($masterRecord)
            {
                $query->where('cloning_contract_id', $masterRecord['parent_id'])
                    ->where('company_id', $masterRecord['companySystemID']);
            })
            ->where('uuid', $masterRecord['uuid'])
            ->first();
    }

    public function setContractStatusData($masterRecord, $result = null)
    {
        $contract = ContractMaster::getExistingContractType($masterRecord['company_id'], $masterRecord['contract_id']);
        $input = [
                'contractId' => $result->parent->uuid ?? $contract['uuid'],
                'cloneContractId' => $result ? $masterRecord['uuid'] : $contract['uuid'],
                'category' => $result->history->category ?? $masterRecord['category'],
                'contractHistoryId' => $result->history->uuid ?? $masterRecord['uuid'],
                'selectedCompanyID' => $result ? $masterRecord['companySystemID'] : $masterRecord['company_id'],
        ];

        if(in_array($input['category'], [2, 3, 5, 6]))
        {
            ContractHistoryService::updateContractStatus($input);
        }
        if($input['category'] == 1)
        {
            ContractAmendmentService::updateContractStatusAmendment($input);
        }

    }

    public function setExtendContractData($masterRecord)
    {
        $contract = ContractMaster::getExistingContractType($masterRecord['company_id'], $masterRecord['contract_id']);
        $getContractId = ContractManagementUtils::checkContractExist($contract['uuid'], $masterRecord['company_id']);

        $startDate = Carbon::parse($getContractId['startDate']);
        $endDate = Carbon::parse($masterRecord['date']);

        $diff = $startDate->diff($endDate);
        $newContractTermPeriod = ContractHistoryService::formatContractTermPeriod($diff);

        $input = [
            'contractId' => $contract['uuid'],
            'contractHistoryId' => $masterRecord['uuid'],
            'contractEndDate' => Carbon::parse($masterRecord['date'])->format('d-m-Y'),
            'selectedCompanyID' => $masterRecord['company_id'],
            'category' => $masterRecord['category'],
            'newContractTermPeriod' => $newContractTermPeriod,
        ];

        ContractHistoryService::updateExtendStatus($input);
    }

    public function getExtensionApprovalData($contractId, $companyId)
    {
        return ContractHistory::where('contract_id', $contractId)
            ->where('company_id', $companyId)
            ->where('cloning_contract_id', $contractId)
            ->where('status', 4)
            ->where('approved_yn', 1)
            ->count();
    }

    private function formatContractTermPeriod($diff)
    {
        $termPeriod = [];

        if ($diff->y)
        {
            $termPeriod[] = "{$diff->y} Years";
        }
        if ($diff->m)
        {
            $termPeriod[] = "{$diff->m} Months";
        }
        if ($diff->d)
        {
            $termPeriod[] = "{$diff->d} Days";
        }

        return implode(', ', $termPeriod);
    }
}
