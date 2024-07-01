<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\Company;
use App\Models\ContractBoqItems;
use App\Models\CurrencyMaster;
use App\Models\TimeMaterialConsumption;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\CrudOperations;

/**
 * Class TimeMaterialConsumptionRepository
 * @package App\Repositories
 * @version June 29, 2024, 10:04 pm +04
*/

class TimeMaterialConsumptionRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'item',
        'description',
        'min_quantity',
        'max_quantity',
        'price',
        'amount',
        'boq_id',
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
        return TimeMaterialConsumption::class;
    }
    protected function getModel()
    {
        return new TimeMaterialConsumption();
    }
    public function getTimeConsumptionFormData($companyID)
    {
        $currencyId = Company::getLocalCurrencyID($companyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = CurrencyMaster::getCurrencyCode($currencyId);
        return  [
            'uom_list' => ContractManagementUtils::getUomList(),
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces
        ];
    }
    public function createTimeMaterialConsumption($contractUuid, $selectedCompanyID)
    {
        return DB::transaction(function () use ($contractUuid, $selectedCompanyID)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
            if(empty($contract))
            {
                throw new CommonException(trans('common.contract_id_not_found'));
            }
            $checkPreviousEmptyValues = TimeMaterialConsumption::checkExistRecordEmpty($contract['id']);
            if($checkPreviousEmptyValues)
            {
                throw new CommonException('Please fill all required fields.');
            }
            $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
            $postData = [
                'uuid' => ContractManagementUtils::generateUuid(),
                'contract_id' => $contract['id'],
                'currency_id' => $currencyId,
                'company_id' => $selectedCompanyID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            TimeMaterialConsumption::insert($postData);
        });
    }
    public function getAllTimeMaterialConsumption($contractUuid, $selectedCompanyID)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_id_not_found'));
        }
        return TimeMaterialConsumption::getAllTimeMaterialConsumption($contract['id']);
    }
    public function updateTimeMaterialConsumption($input, $consumptionID)
    {
        return DB::transaction(function () use ($input, $consumptionID)
        {
            $postData = [
                'item' => $input['item'] ?? null,
                'description' => $input['description'] ?? null,
                'min_quantity' => $input['minQty'] ?? 0,
                'max_quantity' => $input['maxQty'] ?? 0,
                'price' => $input['price'] ?? 0,
                'quantity' => $input['quantity'] ?? 0,
                'uom_id' => $input['uomID'] ?? null,
                'amount' => $input['amount'] ?? 0,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            TimeMaterialConsumption::where('id', $consumptionID)->update($postData);
        });

    }
    public function pullItemsFromBOQ($selectedCompanyID, $contractUuid, $formData)
    {
        return DB::transaction(function () use ($selectedCompanyID, $contractUuid, $formData)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
            if(empty($contract))
            {
                throw new CommonException('Contract ID not found.');
            }
            $postData = [];
            $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
            foreach($formData as $data)
            {
                $uuid = $data['uuid'];
                $boq = ContractBoqItems::getBoqItemDetails($uuid);
                if(empty($boq))
                {
                    throw new CommonException('BOQ not found.');
                }
                $qty = $boq['qty'] ?? 0;
                $price = $boq['itemMaster']['itemAssigned']['wacValueLocal'] ?? 0;
                $amount = $qty * $price;
                $postData[] = [
                    'uuid' => ContractManagementUtils::generateUuid(),
                    'contract_id' => $contract['id'],
                    'item' => $boq['itemMaster']['primaryCode'] ?? null,
                    'description' => $boq['description'] ?? null,
                    'min_quantity' => $boq['minQty'] ?? 0,
                    'max_quantity' => $boq['maxQty'] ?? 0,
                    'price' => $price,
                    'quantity' => $qty,
                    'uom_id' => $boq['itemMaster']['unit'] ?? null,
                    'amount' => $amount,
                    'boq_id' => $boq['id'],
                    'currency_id' => $currencyId ?? null,
                    'updated_by' => General::currentEmployeeId(),
                    'updated_at' => Carbon::now()
                ];
            }
            TimeMaterialConsumption::insert($postData);
        });
    }
}
