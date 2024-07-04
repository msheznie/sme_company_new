<?php
namespace App\Services;

use App\Exceptions\CommonException;
use App\Helpers\ApproveDocument;
use App\Helpers\ConfirmDocument;
use App\Helpers\General;
use App\Helpers\RejectDocument;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Models\ContractMaster;
use App\Models\contractStatusHistory;
use App\Models\ErpDocumentApproved;
use App\Models\ErpDocumentAttachments;
use App\Repositories\ContractHistoryRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ContractCreationException;
class ContractHistoryService
{
    protected $contractHistoryRepository;

    public function __construct(ContractHistoryRepository $contractHistoryRepository)
    {
        $this->contractHistoryRepository = $contractHistoryRepository;
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
                $getContractId = ContractManagementUtils::checkContractExist($uuid, $companyId);
                $contractDocuments = ContractDocument::getContractDocuments($getContractId['id'], $companyId);
                $contractAdditionalDocuments = ContractAdditionalDocuments::getContractAdditionalDocuments(
                    $getContractId['id'], $companyId
                );
                $getContractHistory = ContractManagementUtils::getContractHistoryData($historyUuid);
                $this->deleteRelatedModels($getContractId['id'], $companyId, $categoryId);
                $this->deleteContractStatus($getContractHistory['id']);
                $this->deleteDocumentAttachments($contractDocuments, $contractAdditionalDocuments);
            });
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete contract history: " . $e->getMessage());
        }
    }

    private function deleteRelatedModels($contractId, $companyId, $categoryId)
    {
        $modelsToDelete = [
            'App\\Models\\ContractMaster',
            'App\\Models\\ContractHistory',
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

        foreach ($modelsToDelete as $modelName)
        {
            $model = new $modelName();
            $contractIdCol = $modelName::getContractIdColumn();
            $companyColumn = $modelName::getCompanyIdColumn();
            $contractColumn = (
            $modelName === 'App\\Models\\ContractMaster' ? 'id' :
                $contractIdCol);

            if ($contractColumn)
            {
                $query = $model::where($contractColumn, $contractId);

                if ($modelName === 'App\\Models\\ContractHistory')
                {
                    $query->where('category', $categoryId);
                }

                if ($companyColumn !== null)
                {
                    $query->where($companyColumn, $companyId);
                }

                $query->delete();
            }
        }
    }

    private function deleteDocumentAttachments($contractDocuments, $contractAdditionalDocuments)
    {
        $attachmentIds = $contractDocuments->pluck('id')->merge($contractAdditionalDocuments->pluck('id'))->all();

        if (!empty($attachmentIds))
        {
            ErpDocumentAttachments::whereIn('documentSystemID', [121, 122])
                ->whereIn('documentSystemCode', $attachmentIds)
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

                $cloneStatus = self::checkContractDateBetween
                (
                    $getContractCloneData['startDate'],$getContractCloneData['endDate']
                );

                self::updateContractMaster($contractId, $companyId,$categoryId);
                self::updateContractMaster($getContractCloneData['id'], $companyId,$cloneStatus);
                self::updateContractHistory($contractHistoryId, $companyId, $categoryId);
                self::updateContractHistoryStatus($getContractCloneData['id'],$contractHistoryId,$cloneStatus);
                self::insertHistoryStatus($contractId,$categoryId,$companyId);

            });
        }catch (\Exception $e)
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

            $contractMaster = ContractMaster::where('id', $contractId)
                ->where('companySystemID', $companySystemId)
                ->first();
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
        if($categoryId ==  4)
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
                $contractEndDate = $input['contractEndDate'];
                $companyId = $input['selectedCompanyID'];
                $categoryId = $input['category'];
                $newContractTermPeriod = $input['newContractTermPeriod'];
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
                ($contractId, $companyId,$categoryId,$contractEndDate,$newContractTermPeriod,$contractHistoryUuid,
                    $checkHistoryExists['id']);
            });
        }catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update status: " . $e->getMessage());
        }
    }

    public function updateContractMasterEndDate
    ($contractId, $companyId,$status,$contractEndDate,$newContractTermPeriod,$contractHistoryUuid, $id)
    {
        try
        {
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

    public static function insertHistoryStatus($contractId, $status, $companySystemID, $contractHistoryId = null)
    {
        try
        {
            return DB::transaction(function () use ($contractId,$status, $companySystemID, $contractHistoryId)
            {
                $insert = [
                    'contract_id' => $contractId,
                    'status' => $status,
                    'company_id' => $companySystemID,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];

                if ($contractHistoryId!=null)
                {
                    $insert['contract_history_id'] = $contractHistoryId;
                }

                contractStatusHistory::create($insert);
            });
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to insert contract status: " . $e->getMessage());
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


}
