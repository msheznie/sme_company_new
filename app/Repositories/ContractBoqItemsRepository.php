<?php

namespace App\Repositories;

use App\Helpers\inventory;
use App\Models\CMContractBoqItemsAmd;
use App\Models\CMContractMasterAmd;
use App\Models\ContractBoqItems;
use App\Models\ContractMaster;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

/**
 * Class ContractBoqItemsRepository
 * @package App\Repositories
 * @version April 24, 2024, 8:31 am +04
*/

class ContractBoqItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contractId',
        'itemId',
        'description',
        'minQty',
        'maxQty',
        'qty',
        'companyId',
        'created_by',
        'updated_by'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return ContractBoqItems::class;
    }

    public function getBoqItems(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $companyId = $input['companyId'];
        $uuid = $input['uuid'];
        $contractId = ContractMaster::select('id')->where('uuid', $uuid)->first();

        $amedment = $input['amendment'];
        $model = $amedment ? CMContractBoqItemsAmd::class : ContractBoqItems::class;
        $colName = $amedment ? 'contract_history_id' : 'contractId';
        $col =  $amedment ? 'amd_id' : 'id';
        $id = $amedment ? self::getHistoryId($uuid) : $contractId->id;

        $query = $model::select('uuid', 'minQty', 'maxQty', 'qty', 'companyId', 'itemId','price', 'origin')
            ->with(['itemMaster.unit' => function ($query)
            {
                $query->select('UnitShortCode');
            }, 'itemMaster.itemAssigned.local_currency', 'boqItem', 'boqItem.unit' => function ($query)
            {
                $query->select('UnitShortCode');
            }])
            ->where('companyId', $companyId)
            ->where($colName, $id)
            ->orderBy($col, 'desc');

        return DataTables::eloquent($query)
            ->addColumn('itemDescription', function ($row)
            {
                if($row->origin == 2)
                {
                    return $row->boqItem->description;
                } else
                {
                    return $row->itemMaster->itemDescription;
                }
            })
            ->addColumn('minQty', function ($row)
            {
                return $row->minQty;
            })
            ->addColumn('maxQty', function ($row)
            {
                return $row->maxQty;
            })
            ->addColumn('qty', function ($row)
            {
                return $row->qty;
            })
            ->addColumn('unitShortCode', function ($row)
            {
                if($row->origin == 2)
                {
                    return $row->boqItem->Unit->UnitShortCode;
                } else
                {
                    return $row->itemMaster->Unit->UnitShortCode;
                }

            })
            ->addColumn('primaryCode', function ($row)
            {
                if($row->origin == 2)
                {
                    return $row->boqItem->item_name;
                } else
                {
                    return $row->itemMaster->primaryCode;
                }
            })
            ->addColumn('local', function ($row)
            {
                $data = [
                    'companySystemID' => $row->companyId,
                    'itemCodeSystem' => $row->itemId,
                    'wareHouseId' => null
                ];
                $itemCurrentCostAndQty = Inventory::itemCurrentCostAndQty($data);

                return $itemCurrentCostAndQty['wacValueLocal'];
            })
            ->addColumn('uuid', function ($row)
            {
                return $row->uuid;
            })
            ->make(true);
    }

    public function findByUuid($id)
    {
        return ContractBoqItems::where('uuid', $id)->first();
    }

    public function copyIdsRange($row)
    {
        return ContractBoqItems::select('id')
            ->where('id', '<', $row->id)
            ->where('companyId', $row->companyId)
            ->where('contractId', $row->contractId)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public function copySameQty($id, $arr, $amentmend): array
    {
        $model = $amentmend ? CMContractBoqItemsAmd::class : ContractBoqItems::class;
        $col =  $amentmend ? 'amd_id' : 'id';

        try
        {
            $model::whereIn($col, $id)
                ->update($arr);

            return ['status' => true, 'message' => trans('BoqItems updated successfully')];

        } catch (\Exception $ex)
        {
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }

    public function exportBoqItemsReport(Request $request)
    {
        define('COL_ITEM', 'Item');
        define('COL_DESCRIPTION', 'Description');
        define('COL_MIN_QTY', 'Min Qty');
        define('COL_MAX_QTY', 'Max Qty');
        define('COL_UOM', 'UOM');
        define('COL_QUANTITY', 'Quantity');
        define('COL_PRICE', 'Price');
        define('COL_AMOUNT', 'Amount');
        define('COL_ORIGIN', 'Origin');

        $input = $request->all();
        $companyId = $input['selectedCompanyID'];
        $uuid = $input['uuid'];
        $contractId = ContractMaster::select('id')->where('uuid', $uuid)->first();
        $lotData = ContractBoqItems::with(['itemMaster.unit', 'itemMaster.itemAssigned.local_currency',
            'boqItem', 'boqItem.unit' => function ($query) {
            $query->select('UnitShortCode');
        }])
            ->where('companyId', $companyId)
            ->where('contractId', $contractId->id)
            ->get();
        $lotData = $lotData->map(function ($item)
        {
            $data = [
                'companySystemID' => $item->companyId,
                'itemCodeSystem' => $item->itemId,
                'wareHouseId' => null
            ];

            $itemCurrentCostAndQty = Inventory::itemCurrentCostAndQty($data);
            $item->current = [
                'local' => $itemCurrentCostAndQty['wacValueLocal']
            ];
            return $item;
        });

        $data[0] = [
            COL_ITEM => "Item",
            COL_DESCRIPTION => "Description",
            COL_MIN_QTY => "Min Qty",
            COL_MAX_QTY => "Max Qty",
            COL_UOM => "UOM",
            COL_QUANTITY => "Quantity",
            COL_PRICE => "Price",
            COL_AMOUNT => "Amount",
            COL_ORIGIN => "Added From"
        ];

        if ($lotData)
        {
            $count = 1;
            foreach ($lotData as $value)
            {
                $decimalCount = $value['itemMaster']['itemAssigned']['local_currency']['DecimalPlaces'] ?? 2;

                if($value['origin'] == 1)
                {
                    $data[$count][COL_ITEM] = isset($value['itemMaster']['primaryCode'])
                        ? preg_replace('/^=/', '-', $value['itemMaster']['primaryCode'])
                        : '-';
                }

                if($value['origin'] == 2)
                {
                    $data[$count][COL_ITEM] = isset($value['boqItem']['item_name'])
                        ? preg_replace('/^=/', '-', $value['boqItem']['item_name'])
                        : '-';
                }

                if($value['origin'] == 1)
                {
                    $data[$count][COL_DESCRIPTION] = isset($value['description'])
                        ? preg_replace('/^=/', '-', $value['description'])
                        : '-';
                }

                if($value['origin'] == 2)
                {
                    $data[$count][COL_DESCRIPTION] = isset($value['boqItem']['description'])
                        ? preg_replace('/^=/', '-', $value['boqItem']['description'])
                        : '-';
                }

                $data[$count][COL_MIN_QTY] = isset($value['minQty'])
                    ? preg_replace('/^=/', '-', $value['minQty'])
                    : '-';

                $data[$count][COL_MAX_QTY] = isset($value['maxQty'])
                    ? preg_replace('/^=/', '-', $value['maxQty'])
                    : '-';

                if($value['origin'] == 1)
                {
                    $data[$count][COL_UOM] = isset($value['itemMaster']['Unit']['UnitShortCode'])
                        ? preg_replace('/^=/', '-', $value['itemMaster']['Unit']['UnitShortCode'])
                        : '-';
                }

                if($value['origin'] == 2) {
                    $data[$count][COL_UOM] = isset($value['boqItem']['Unit']['UnitShortCode'])
                        ? preg_replace('/^=/', '-', $value['boqItem']['Unit']['UnitShortCode'])
                        : '-';
                }

                $data[$count][COL_QUANTITY] = isset($value['qty'])
                    ? preg_replace('/^=/', '-', $value['qty'])
                    : '-';

                $data[$count][COL_PRICE] = isset($value['price'])
                    ? number_format(
                        $value['price'],
                        $decimalCount,
                        '.',
                        ''
                    )
                    : '-';

                $data[$count][COL_AMOUNT] = isset($value['price']) &&
                is_numeric($value['price']) &&
                is_numeric($value['qty'])
                    ? number_format(
                        $value['price'] * $value['qty'],
                        $decimalCount,
                        '.',
                        ''
                    )
                    : '-';
                $origin = $value['origin'] == 1 ? 'Item Master' : 'Tender';
                $data[$count][COL_ORIGIN] = isset($value['origin'])
                    ? preg_replace('/^=/', '-', $origin)
                    : '-';
                $count++;
            }
        }
        return $data;
    }

    public function getBoqData($id)
    {
        return $this->model->getBoqData($id);
    }

    public function getHistoryId($id)
    {
        $data = ContractManagementUtils::getContractHistoryData($id);
        return $data->id;
    }

}
