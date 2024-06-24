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
                $getContractId = ContractManagementUtils::checkContractExist($uuid, $companyId);
                $contractDocuments = ContractDocument::getContractDocuments($getContractId['id'], $companyId);
                $contractAdditionalDocuments = ContractAdditionalDocuments::getContractAdditionalDocuments(
                    $getContractId['id'], $companyId
                );
                $this->deleteRelatedModels($getContractId['id'], $companyId, $categoryId);
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
                $cloneContractId = $input['cloneContractId'];
                $companyId = $input['selectedCompanyID'];
                $categoryId = $input['category'];
                $getContractId = ContractManagementUtils::checkContractExist($contractId, $companyId);
                $cloneContractId = ContractManagementUtils::checkContractExist($cloneContractId, $companyId);

                if (!$getContractId)
                {
                    throw new ContractCreationException(trans('common.contract_does_not_exist'));
                }

                $contractId = $getContractId->id;
                $cloningContractId = $cloneContractId->id;

                self::updateContractMaster($contractId, $companyId,$categoryId);
                self::updateContractMaster($cloningContractId, $companyId,-1);
                self::updateContractHistory($cloningContractId, $companyId, $categoryId);
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

    public function updateContractHistory($contractId, $companyId, $categoryId = null)
    {
        try
        {
            $data = [
                'date'  => carbon::now()->format('Y-m-d'),
                'status'  => $categoryId,
            ];

            ContractHistory::where('company_id', $companyId)
                ->where('contract_id', $contractId)
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

                if (!$getContractId)
                {
                    throw new ContractCreationException(trans('common.contract_does_not_exist'));
                }

                $contractId = $getContractId->id;
                self::updateContractMasterEndDate
                ($contractId, $companyId,$categoryId,$contractEndDate,$newContractTermPeriod,$contractHistoryUuid);
            });
        }catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update status: " . $e->getMessage());
        }
    }

    public function updateTerminateStatus($input)
    {
        try
        {
            return DB::transaction(function () use ($input)
            {
                $contractId = $input['contractId'];
                $companyId = $input['selectedCompanyID'];
                $getContractId = ContractManagementUtils::checkContractExist($contractId, $companyId);

                if (!$getContractId)
                {
                    throw new ContractCreationException(trans('common.contract_does_not_exist'));
                }

                $contractId = $getContractId->id;
                ContractMaster::where('companySystemID', $companyId)
                    ->where('id', $contractId)
                    ->update(['is_termination'  => 1]);
            });
        }catch (\Exception $e)
        {
            throw new ContractCreationException(trans('common.failed_to_update_status: ' . $e->getMessage()));
        }
    }

    public function updateContractMasterEndDate(
        $contractId, $companyId,$status,$contractEndDate,$newContractTermPeriod,$contractHistoryUuid)

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
                $historyId = ContractHistory::select('id')->where('uuid', $contractHistoryUuid)->first();

                if (!empty($historyId))
                {
                    ContractHistory::where('id', $historyId->id)->delete();
                }
            });
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete contract history: " . $e->getMessage());
        }
    }
}
