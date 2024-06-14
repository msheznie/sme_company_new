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
use App\Models\ErpDocumentAttachments;
use App\Repositories\ContractHistoryRepository;
use App\Utilities\ContractManagementUtils;
use AWS\CRT\Log;
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
            'App\\Models\\ContractMaster' => 'companySystemID',
            'App\\Models\\ContractHistory' => 'company_id',
            'App\\Models\\ContractUserAssign' => '',
            'App\\Models\\ContractSettingMaster' => '',
            'App\\Models\\ContractSettingDetail' => '',
            'App\\Models\\ContractDocument' => 'companySystemID',
            'App\\Models\\ContractAdditionalDocuments' => 'companySystemID',
            'App\\Models\\ContractBoqItems' => 'companyId',
            'App\\Models\\ContractMilestone' => 'companySystemID',
            'App\\Models\\ContractDeliverables' => 'companySystemID',
            'App\\Models\\ContractOverallRetention' => 'companySystemId',
            'App\\Models\\ContractMilestoneRetention' => 'companySystemId',
        ];

        foreach ($modelsToDelete as $modelName => $companyColumn)
        {
            $model = new $modelName();
            $table = $model->getTable();
            $columns = \Schema::getColumnListing($table);

            $contractColumn = (
            $modelName === 'App\\Models\\ContractMaster' ? 'id' :
                $this->contractHistoryRepository->getContractColumn($columns));

            if ($contractColumn)
            {
                $query = $model::where($contractColumn, $contractId);

                if ($modelName === 'App\\Models\\ContractHistory')
                {
                    $query->where('category', $categoryId);
                }

                if ($companyColumn !== '')
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
    public function getAllAddendumData($input)
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
                    throw new ContractCreationException("Contract does not exist.");
                }

                $contractId = $getContractId->id;
                $cloningContractId = $cloneContractId->id;

                self::updateContractMaster($contractId, $companyId,$categoryId);
                self::updateContractMaster($cloningContractId, $companyId,-1);
                self::updateContractHistory($cloningContractId, $companyId);

            });
        }catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update status: " . $e->getMessage());
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

    public function updateContractHistory($contractId, $companyId)
    {
        try
        {
            $data = [
                'date'  => carbon::now()->format('Y-m-d'),
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
                General::currentEmployeeId()
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
                $contractEndDate = $input['contractEndDate'];
                $companyId = $input['selectedCompanyID'];
                $categoryId = $input['category'];
                $getContractId = ContractManagementUtils::checkContractExist($contractId, $companyId);

                if (!$getContractId)
                {
                    throw new ContractCreationException("Contract does not exist.");
                }

                $contractId = $getContractId->id;
                self::updateContractMasterEndDate($contractId, $companyId,$categoryId,$contractEndDate);
            });
        }catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update status: " . $e->getMessage());
        }
    }

    public function updateContractMasterEndDate($contractId, $companyId,$status,$contractEndDate)
    {
        try
        {
            $data = [
                'endDate'  => ContractManagementUtils::convertDate($contractEndDate),
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
}
