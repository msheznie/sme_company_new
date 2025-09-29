<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\CMContractBoqItemsAmd;
use App\Services\GeneralService;
use App\Models\Company;
use App\Models\ContractBoqItems;
use App\Models\CurrencyMaster;
use App\Models\TimeMaterialConsumption;
use App\Models\TimeMaterialConsumptionAmd;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\CrudOperations;
use Illuminate\Support\Facades\Log;

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
    public function createTimeMaterialConsumption($contractUuid, $selectedCompanyID, $historyUuid, $amendment)
    {
        return DB::transaction(function () use ($contractUuid, $selectedCompanyID, $historyUuid, $amendment)
        {
            $model = $amendment ? TimeMaterialConsumptionAmd::class : TimeMaterialConsumption::class;

            $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID)
                ?? GeneralService::sendException(trans('common.contract_code_not_found'));

            $historyID = $amendment
                ? ContractManagementUtils::getContractHistoryData($historyUuid)['id']
                ?? GeneralService::sendException(trans('common.contract_history_not_found'))
                : 0;

            $checkPreviousEmptyValues = $amendment ? $model::checkExistRecordEmpty($contract['id'],
                $historyID) : $model::checkExistRecordEmpty($contract['id']);

            if($checkPreviousEmptyValues)
            {
                GeneralService::sendException(trans('common.please_fill_all_required_fields'));
            }

            $postData = [
                'uuid' => ContractManagementUtils::generateUuid(),
                'contract_id' => $contract['id'],
                'currency_id' => Company::getLocalCurrencyID($selectedCompanyID),
                'company_id' => $selectedCompanyID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now(),
            ];
            if ($amendment) {
                $postData += [
                    'contract_history_id' => $historyID,
                    'level_no' => 1,
                ];
            }
            $model::insert($postData);
        });
    }
    public function getAllTimeMaterialConsumption($contractUuid, $selectedCompanyID, $historyUuid, $amendment)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID)
            ?? GeneralService::sendException(trans('common.contract_code_not_found'));

        $contractID = $contract['id'];

        if ($amendment)
        {
            $contractHistory = ContractManagementUtils::getContractHistoryData($historyUuid)
                ?? GeneralService::sendException(trans('common.contract_history_not_found'));

            return TimeMaterialConsumptionAmd::getAllTimeMaterialConsumptionAmd($contractHistory['id'], $contractID);
        }

        return TimeMaterialConsumption::getAllTimeMaterialConsumption($contractID);
    }
    public function updateTimeMaterialConsumption($input, $consumptionID)
    {
        return DB::transaction(function () use ($input, $consumptionID)
        {
            $amendment = $input['amendment'] ?? false;
            $model = $amendment ? TimeMaterialConsumptionAmd::class : TimeMaterialConsumption::class;

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
            $model::when($amendment, function ($q) use ($consumptionID)
            {
                $q->where('amd_id', $consumptionID);
            })->when(!$amendment, function ($q) use ($consumptionID)
            {
                $q->where('id', $consumptionID);
            })->update($postData);
        });

    }
    public function pullItemsFromBOQ($selectedCompanyID, $contractUuid, $formData, $amendment, $historyUuid)
    {
        return DB::transaction(function () use ($selectedCompanyID, $contractUuid, $formData, $amendment, $historyUuid)
        {
            $model = $amendment ? TimeMaterialConsumptionAmd::class : TimeMaterialConsumption::class;

            $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID)
                ?? GeneralService::sendException(trans('common.contract_code_not_found'));

            $historyID = $amendment
                ? (ContractManagementUtils::getContractHistoryData($historyUuid)['id']
                    ?? GeneralService::sendException(trans('common.contract_history_not_found')))
                : 0;

            $currencyId = Company::getLocalCurrencyID($selectedCompanyID);

            foreach ($formData as $data)
            {
                $boq = $amendment
                    ? CMContractBoqItemsAmd::getBoqItemData($historyID, $data['uuid'])
                    : ContractBoqItems::getBoqItemDetails($data['uuid']);

                if (empty($boq))
                {
                    GeneralService::sendException(trans('common.boq_not_found'));
                }

                $amount = ($boq['qty'] ?? 0) * ($boq['price'] ?? 0);
                $uuid = ContractManagementUtils::generateUuid();

                if ($model::checkUuidExists($uuid))
                {
                    GeneralService::sendException(trans('common.uuid_already_exists'));
                }

                $postData = [
                    'uuid' => $uuid,
                    'contract_id' => $contract['id'],
                    'item' => $boq['origin'] == 1
                        ? $boq['itemMaster']['primaryCode'] ?? null
                        : $boq['boqItem']['item_name'],
                    'description' => $boq['origin'] == 1
                        ? $boq['description'] ?? null
                        : $boq['boqItem']['description'],
                    'min_quantity' => $boq['minQty'] ?? 0,
                    'max_quantity' => $boq['maxQty'] ?? 0,
                    'price' => $boq['price'] ?? 0,
                    'quantity' => $boq['qty'] ?? 0,
                    'uom_id' => $boq['origin'] == 1
                        ? $boq['itemMaster']['unit'] ?? null
                        : $boq['boqItem']['Unit']['UnitID'],
                    'amount' => $amount,
                    'boq_id' => $amendment ? $boq['amd_id'] : $boq['id'],
                    'currency_id' => $currencyId ?? null,
                    'company_id' => $selectedCompanyID,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now(),
                ];

                if ($amendment)
                {
                    $postData += [
                        'id' => null,
                        'contract_history_id' => $historyID,
                        'level_no' => 1,
                    ];
                }
                $model::insert($postData);
            }
        });
    }
    public function getTimeMaterialConsumptionToAmd($contractID)
    {
        return $this->model->getTimeMaterialConsumptionToAmd($contractID);
    }
}
