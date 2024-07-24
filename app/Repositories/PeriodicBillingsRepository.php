<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\Company;
use App\Models\CurrencyMaster;
use App\Models\PeriodicBillings;
use App\Repositories\BaseRepository;
use App\Traits\CrudOperations;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class PeriodicBillingsRepository
 * @package App\Repositories
 * @version June 26, 2024, 9:09 am +04
*/

class PeriodicBillingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'amount',
        'start_date',
        'end_date',
        'occurrence_type',
        'due_in',
        'no_of_installment',
        'inst_payment_amount',
        'currency_id',
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
        return PeriodicBillings::class;
    }

    protected function getModel()
    {
        return new PeriodicBillings();
    }

    public function createPeriodicBilling($input, $contractUuid, $companyId)
    {
        return DB::transaction(function () use ($input, $contractUuid, $companyId)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if(empty($contract))
            {
                throw new CommonException('Contract ID not found.');
            }
            $currencyId = Company::getLocalCurrencyID($companyId);
            $postData = [
                'uuid' => ContractManagementUtils::generateUuid(),
                'contract_id' => $contract['id'],
                'amount' => (float) $input['amount'],
                'start_date' => Carbon::parse($input['start_date']),
                'end_date' => Carbon::parse($input['end_date']),
                'occurrence_type' => $input['occurrence_type'],
                'due_in' => $input['due_in'] ?? 0,
                'no_of_installment' => $input['no_of_installment'] ?? 0,
                'inst_payment_amount' => $input['inst_payment_amount'] ? (float) $input['inst_payment_amount'] : 0,
                'currency_id' => $currencyId,
                'company_id' => $companyId,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            PeriodicBillings::insert($postData);
        });
    }
    public function getPaymentScheduleFormData($uuid, $companySystemID)
    {
        $contract = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $periodicBilling = PeriodicBillings::getContractPeriodicBilling($contract['id']);
        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = $periodicBilling && $periodicBilling->currency ? $periodicBilling->currency->DecimalPlaces :
            CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = $periodicBilling && $periodicBilling->currency ? $periodicBilling->currency->CurrencyCode :
            CurrencyMaster::getCurrencyCode($currencyId);
        $billingFrequencies = ContractManagementUtils::getBillingFrequencies();
        return [
            'billing_frequencies' => $billingFrequencies,
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces,
            'edit_data' => $periodicBilling
        ];
    }

    public function updatePeriodicBilling($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
            $postData = [
                'amount' => (float) $input['amount'],
                'start_date' => Carbon::parse($input['start_date']),
                'end_date' => Carbon::parse($input['end_date']),
                'occurrence_type' => $input['occurrence_type'],
                'due_in' => ($input['occurrence_type'] == 7) ? ($input['due_in'] ?? null) : null,
                'no_of_installment' => $input['no_of_installment'] ?? 0,
                'inst_payment_amount' => $input['inst_payment_amount'] ? (float) $input['inst_payment_amount'] : 0,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            PeriodicBillings::where('id', $id)->update($postData);
        });
    }
}
