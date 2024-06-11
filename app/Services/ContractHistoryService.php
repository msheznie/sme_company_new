<?php
namespace App\Services;

use App\Models\ContractAdditionalDocuments;
use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Models\ErpDocumentAttachments;
use App\Repositories\ContractHistoryRepository;
use App\Utilities\ContractManagementUtils;
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
}
