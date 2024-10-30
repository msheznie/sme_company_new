<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\Company;
use App\Models\ContractOverallPenalty;
use App\Models\CurrencyMaster;
use App\Models\PeriodicBillings;
use App\Repositories\BaseRepository;
use App\Traits\CrudOperations;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ContractOverallPenaltyRepository
 * @package App\Repositories
 * @version July 23, 2024, 1:36 pm +04
*/

class ContractOverallPenaltyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'minimum_penalty_percentage',
        'minimum_penalty_amount',
        'maximum_penalty_percentage',
        'maximum_penalty_amount',
        'actual_percentage',
        'actual_penalty_amount',
        'penalty_circulation_start_date',
        'actual_penalty_start_date',
        'penalty_circulation_frequency',
        'due_in',
        'due_penalty_amount',
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
        return ContractOverallPenalty::class;
    }

    public function createOverallPenalty($input, $contractUuid, $companyId)
    {
        return DB::transaction(function () use ($input, $contractUuid, $companyId)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if(empty($contract))
            {
                throw new CommonException('Contract Code not found.');
            }
            $postData = [
                'uuid' => ContractManagementUtils::generateUuid(),
                'contract_id' => $contract['id'],
                'minimum_penalty_percentage' => (float) $input['minimum_penalty_percentage'],
                'minimum_penalty_amount' => (float) $input['minimum_penalty_amount'],
                'maximum_penalty_percentage' => (float) $input['maximum_penalty_percentage'],
                'maximum_penalty_amount' => (float) $input['maximum_penalty_amount'],
                'actual_percentage' => (float) $input['actual_percentage'],
                'actual_penalty_amount' => (float) $input['actual_penalty_amount'],
                'penalty_circulation_start_date' => $input['penalty_circulation_start_date'] ?
                    Carbon::parse($input['penalty_circulation_start_date']) : null,
                'actual_penalty_start_date' => Carbon::parse($input['actual_penalty_start_date']),
                'penalty_circulation_frequency' => $input['penalty_circulation_frequency'],
                'due_in' => $input['due_in'] ?? 0,
                'status' => 0,
                'company_id' => $companyId,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            ContractOverallPenalty::insert($postData);
        });
    }

    public function getOverallPenaltyData($uuid, $companySystemID)
    {
        $contract = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $overallPenalty = ContractOverallPenalty::getOverallPenalty($contract['id'], $companySystemID);
        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = CurrencyMaster::getCurrencyCode($currencyId);
        $billingFrequencies = ContractManagementUtils::getBillingFrequencies();

        if($overallPenalty)
        {
            $duePenaltyAmount = $this->duePenaltyAmountCalculation($overallPenalty);
        }

        return [
            'billing_frequencies' => $billingFrequencies,
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces,
            'edit_data' => $overallPenalty,
            'startDate' => $contract['startDate'],
            'duePenaltyAmount' => $duePenaltyAmount ?? null
        ];
    }

    private function duePenaltyAmountCalculation($overallPenalty)
    {
        $penaltyStartDate = Carbon::parse($overallPenalty['actual_penalty_start_date']);
        $today = Carbon::now();
        $newPenaltyStartDate = (new \DateTime($today))->format('Y-m-d');
        $newPenaltyEndDate = (new \DateTime($penaltyStartDate))->format('Y-m-d');
        $daysDifference = $penaltyStartDate->diffInDays($today);
        $penaltyCirculationFrequency = $overallPenalty['penalty_circulation_frequency'];

        if($penaltyCirculationFrequency == 1)
        {
            $noOfInstallments = $daysDifference / 14;
        }
        if($penaltyCirculationFrequency == 2)
        {
            $noOfInstallments = $daysDifference / 7;
        }
        if($penaltyCirculationFrequency == 3)
        {
            $noOfInstallments = $daysDifference / 30;
        }
        if($penaltyCirculationFrequency == 4)
        {
            $noOfInstallments = $daysDifference / 365;
        }
        if($penaltyCirculationFrequency == 5)
        {
            $noOfInstallments = $daysDifference / 90;
        }
        if($penaltyCirculationFrequency == 6)
        {
            $noOfInstallments = $daysDifference / 180;
        }
        if($penaltyCirculationFrequency == 7)
        {
            $noOfInstallments = $daysDifference / $overallPenalty['due_in'];
        }

        $noOfInstallments = floor($noOfInstallments);
        $calculatedAmount = $noOfInstallments * $overallPenalty['actual_penalty_amount'];
        $status = $overallPenalty['status'];


        if($status == 1)
        {
            $duePenaltyAmount = $overallPenalty['due_penalty_amount'];
        } else
        {
            if($overallPenalty['maximum_penalty_amount'] == $calculatedAmount)
            {
                $duePenaltyAmount = $calculatedAmount;
            }
            if($overallPenalty['maximum_penalty_amount'] < $calculatedAmount)
            {
                $duePenaltyAmount = $overallPenalty['maximum_penalty_amount'];
            }
            if($overallPenalty['maximum_penalty_amount'] > $calculatedAmount)
            {
                $duePenaltyAmount = $calculatedAmount;
            }
            if ($newPenaltyStartDate < $newPenaltyEndDate)
            {
                $duePenaltyAmount = 0;
            }
        }

        return $duePenaltyAmount;
    }

    public function updateOverallPenalty($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
            $postData = [
                'minimum_penalty_percentage' => (float) $input['minimum_penalty_percentage'],
                'minimum_penalty_amount' => (float) $input['minimum_penalty_amount'],
                'maximum_penalty_percentage' => (float) $input['maximum_penalty_percentage'],
                'maximum_penalty_amount' => (float) $input['maximum_penalty_amount'],
                'actual_percentage' => (float) $input['actual_percentage'],
                'actual_penalty_amount' => (float) $input['actual_penalty_amount'],
                'penalty_circulation_start_date' => $input['penalty_circulation_start_date'] ?
                    Carbon::parse($input['penalty_circulation_start_date']) : null,
                'actual_penalty_start_date' => Carbon::parse($input['actual_penalty_start_date']),
                'penalty_circulation_frequency' => $input['penalty_circulation_frequency'],
                'due_in' => ($input['penalty_circulation_frequency'] == 7) ? ($input['due_in'] ?? null) : null,
                'actual_due_penalty_amount' => (float) $input['actual_due_penalty_amount'],
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            ContractOverallPenalty::where('id', $id)->update($postData);
        });
    }

    public function findByUuid($id)
    {
        return ContractOverallPenalty::where('uuid', $id)->first();
    }

    public function updatePenaltyStatus($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
            $postData = [
                'due_penalty_amount' => $input['duePenaltyAmount'],
                'status' => $input['status'],
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            ContractOverallPenalty::where('id', $id)->update($postData);
        });
    }


}
