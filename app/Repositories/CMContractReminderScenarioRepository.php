<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Models\CMContractDropValue;
use App\Models\CMContractReminderScenario;
use App\Models\CMContractScenarioAssign;
use App\Models\CMContractScenarioSetting;
use App\Models\CMContractsMaster;
use App\Models\ContractMaster;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CMContractReminderScenarioRepository
 * @package App\Repositories
 * @version July 1, 2024, 8:01 pm +04
*/

class CMContractReminderScenarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'title',
        'description'
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
        return CMContractReminderScenario::class;
    }

    public function showReminders($request)
    {
        return CMContractReminderScenario::showReminders();
    }

    public function showRemindersDropValues($request)
    {
        return CMContractDropValue::showRemindersAfterTheScenarioEvery();
    }
    public function showRemindersValues($input)
    {
        $contractUuid = $input['contractUuid'];
        $companyId = $input['selectedCompanyID'];
        $scenarioTypeId = $input['typeId'];
        $currentContractDetails = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
        return CMContractScenarioAssign::getContractScenarioIsActive(
            $currentContractDetails->id, $companyId, $scenarioTypeId);
    }
    public function showRemindersSettingValue($input, $type)
    {
        $contractUuid = $input['contractUuid'];
        $companyId = $input['selectedCompanyID'];
        $scenarioTypeId = $input['typeId'];
        $currentContractDetails = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
        $scenarioId =  CMContractScenarioAssign::getContractScenarioId(
            $currentContractDetails->id, $companyId, $scenarioTypeId);
        return CMContractScenarioSetting::getValue($scenarioId, $type);
    }

    public function createContractReminderConfiguration($request)
    {
        $input = $request->all();
        return DB::transaction(function () use ($input)
        {
            $contractUuid = $input['contractUuid'];
            $companyId = $input['companySystemID'];
            $currentContractDetails = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if (!$currentContractDetails)
            {
                throw new ContractCreationException('Contract not found');
            }
            return $this->createScenarioAssign($input, $currentContractDetails);

        });
    }

    public function createScenarioAssign($data, $currentContractDetails)
    {
        try
        {
            $isScenarioAssignRecord = $this->findExistingRecord($data, $currentContractDetails->id);

            if ($isScenarioAssignRecord)
            {
                $result = $this->updateRecord($isScenarioAssignRecord, $data);
                $this->storeScenarioSetting($data, $result->id);
                return $result;
            }

            $result =  $this->createRecord($data, $currentContractDetails->id);
            return $this->storeScenarioSetting($data, $result->id);
        }
        catch (Exception $e)
        {
            throw new ContractCreationException('Failed to create scenarios assign: ' . $e->getMessage());
        }
    }

    /**
     * Find existing record by scenario_id, contract_id, and company_id.
     */
    private function findExistingRecord($data, $contractId)
    {
        return CMContractScenarioAssign::where('scenario_id', $data['scenario_id'])
            ->where('contract_id', $contractId)
            ->where('company_id', $data['companySystemID'])
            ->first();
    }

    /**
     * Update an existing record with new data.
     */
    private function updateRecord($record, $data)
    {
        $record->is_active = $data['status'];
        $record->updated_at = now();

        if (!$record->save())
        {
            throw new ContractCreationException('Something went wrong during the update');
        }

        return $record;
    }

    /**
     * Create a new record with the given data.
     */
    private function createRecord($data, $contractId)
    {
        $insert = [
            'uuid' => ContractManagementUtils::generateUuid(),
            'scenario_id' => $data['scenario_id'],
            'is_active' => $data['status'],
            'contract_id' => $contractId,
            'company_id' => $data['companySystemID'],
            'created_at' => now()
        ];

        return CMContractScenarioAssign::create($insert);
    }

    private function storeScenarioSetting($data, $id)
    {
        $recordBefore = $this->findExistingScenarioSettingRecord($id, 1);

        if ($recordBefore)
        {
            $this->updateScenarioSetting($recordBefore, $data, 1);
        }

        $recordAfter = $this->findExistingScenarioSettingRecord($id, 2);

        if ($recordAfter)
        {
            $this->updateScenarioSetting($recordAfter, $data, 2);
        }

        if(!($recordBefore || $recordAfter))
        {
            return  $this->createScenarioSetting($data, $id);
        } else
        {
            return true;
        }
    }

    private function findExistingScenarioSettingRecord($id, $type)
    {
        return CMContractScenarioSetting::select(
            'id',
            'scenario_assign_id',
            'value',
            'scenario_type',
             )
            ->where('scenario_assign_id', $id)
            ->where('scenario_type', $type)
            ->first();
    }

    private function updateScenarioSetting($record, $data, $type)
    {
        $value = ($type == 1) ? $data['before'] : $data['after'];
        $record->value = $value;
        $record->updated_at = now();

        if (!$record->save())
        {
            throw new ContractCreationException('Something went wrong during the update');
        }

        return $record;
    }

    private function createScenarioSetting($data, $id)
    {
        $scenarioSettings = [];

        if (isset($data['before']))
        {
            $scenarioSettings[] = [
                'scenario_assign_id' => $id,
                'value' => $data['before'],
                'scenario_type' => 1,
                'created_at' => now()
            ];
        }

        if (isset($data['after']))
        {
            $scenarioSettings[] = [
                'scenario_assign_id' => $id,
                'value' => $data['after'],
                'scenario_type' => 2,
                'created_at' => now()
            ];
        }

        if (!empty($scenarioSettings))
        {
            CMContractScenarioSetting::insert($scenarioSettings);
        }

        return true;
    }

}
