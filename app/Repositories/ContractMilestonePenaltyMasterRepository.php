<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\Company;
use App\Models\ContractMilestonePenaltyDetail;
use App\Models\ContractMilestonePenaltyMaster;
use App\Models\ContractOverallPenalty;
use App\Models\CurrencyMaster;
use App\Repositories\BaseRepository;
use App\Traits\CrudOperations;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class ContractMilestonePenaltyMasterRepository
 * @package App\Repositories
 * @version July 28, 2024, 10:50 am +04
 */

class ContractMilestonePenaltyMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'minimum_penalty_percentage',
        'minimum_penalty_amount',
        'maximum_penalty_percentage',
        'maximum_penalty_amount',
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
        return ContractMilestonePenaltyMaster::class;
    }

    protected function getModel()
    {
        return new ContractMilestonePenaltyMaster();
    }

    public function createMilestonePenalty($input, $contractUuid, $companyId)
    {
        return DB::transaction(function () use ($input, $contractUuid, $companyId)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if(empty($contract))
            {
                throw new CommonException('Contract ID not found.');
            }
            if($input['minimum_penalty_percentage'] > $input['maximum_penalty_percentage'])
            {
                throw new CommonException('Minimum penalty percentage should be less than maximum penalty percentage');
            } else
            {
                $postData = [
                    'uuid' => ContractManagementUtils::generateUuid(),
                    'contract_id' => $contract['id'],
                    'minimum_penalty_percentage' => (float) $input['minimum_penalty_percentage'],
                    'minimum_penalty_amount' => (float) $input['minimum_penalty_amount'],
                    'maximum_penalty_percentage' => (float) $input['maximum_penalty_percentage'],
                    'maximum_penalty_amount' => (float) $input['maximum_penalty_amount'],
                    'company_id' => $companyId,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];
                ContractMilestonePenaltyMaster::insert($postData);
            }
        });
    }

    public function getMilestonePenaltyMasterData($uuid, $companySystemID)
    {
        $contract = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $milestonePenaltyMaster = ContractMilestonePenaltyMaster::getMilestonePenaltyMaster(
            $contract['id'], $companySystemID);
        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = CurrencyMaster::getCurrencyCode($currencyId);
        $billingFrequencies = ContractManagementUtils::getBillingFrequencies();

        return [
            'billing_frequencies' => $billingFrequencies,
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces,
            'edit_data' => $milestonePenaltyMaster,
            'startDate' => $contract['startDate'],
        ];
    }

    public function updateMilestonePenalty($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
            $contractUuid = $input['contractUuid'];
            $companyId = $input['selectedCompanyID'];
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if(empty($contract))
            {
                throw new CommonException(trans('common.contract_not_found'));
            }

            if($input['minimum_penalty_percentage'] > $input['maximum_penalty_percentage'])
            {
                throw new CommonException('Minimum penalty percentage should be less than maximum penalty percentage');
            }

            $penaltyDetails =  ContractMilestonePenaltyDetail::getRecordsWithMilestone($contract['id'], $companyId);
            foreach ($penaltyDetails as $penaltyDetail)
            {
                $penaltyPercentage = (float) $penaltyDetail['penalty_percentage'];
                if ($penaltyPercentage < $input['minimum_penalty_percentage'] ||
                    $penaltyPercentage > $input['maximum_penalty_percentage'])
                {
                    throw new CommonException('If you want to update milestone penalty master details,
                    Please delete already added milestone penalty records first');
                }
            }

            $postData = [
                'minimum_penalty_percentage' => (float) $input['minimum_penalty_percentage'],
                'minimum_penalty_amount' => (float) $input['minimum_penalty_amount'],
                'maximum_penalty_percentage' => (float) $input['maximum_penalty_percentage'],
                'maximum_penalty_amount' => (float) $input['maximum_penalty_amount'],
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            ContractMilestonePenaltyMaster::where('id', $id)->update($postData);
        });
    }
}
