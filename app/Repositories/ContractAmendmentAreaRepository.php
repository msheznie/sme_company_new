<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Helpers\General;
use App\Models\ContractAmendmentArea;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Exception;
/**
 * Class ContractAmendmentAreaRepository
 * @package App\Repositories
 * @version July 5, 2024, 9:58 am +04
*/

class ContractAmendmentAreaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contract_history_id',
        'section_id',
        'company_id',
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
        return ContractAmendmentArea::class;
    }

    public function save($input,$contractId,$historyId)
    {
        try
        {
            $areaList = $input['areas'];
            $companyId = $input['selectedCompanyID'];

            $areaData = array_map(function ($area) use ($contractId,$historyId,$companyId)
            {
                return [
                    'section_id' => $area['id'],
                    'contract_history_id' => $historyId,
                    'contract_id' => $contractId,
                    'company_id' => $companyId,
                    'created_by' => General::currentEmployeeId(),
                ];
            }, $areaList);

            ContractAmendmentArea::insert($areaData);

        }
        catch (Exception $e)
        {
            throw new ContractCreationException("Failed to create contract amendment: " . $e->getMessage());
        }
    }

    public function getActiveAmdSections($input)
    {
        try
        {
            $historyUuid = $input['historyUuid'];
            $contractUuid = $input['contractId'];
            $companyId = $input['selectedCompanyID'];

            $contractData = ContractManagementUtils::checkContractExist($contractUuid,$companyId);
            $historyData = ContractManagementUtils::getContractHistoryData($historyUuid);

            return $this->model->getContractAmendAreas($contractData->id,$historyData->id);

        }
        catch(Exception $e)
        {
            throw new ContractCreationException("Failed to get amendment areas: " . $e->getMessage());
        }

    }
}
