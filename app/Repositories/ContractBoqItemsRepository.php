<?php

namespace App\Repositories;

use App\Helpers\inventory;
use App\Models\ContractBoqItems;
use App\Models\ContractMaster;
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

        $query = ContractBoqItems::select('uuid', 'minQty', 'maxQty', 'qty', 'companyId', 'itemId')
            ->with(['itemMaster.unit' => function ($query) {
                $query->select('UnitShortCode');
            }, 'itemMaster.itemAssigned.local_currency'])
            ->where('companyId', $companyId)
            ->where('contractId', $contractId->id);

        if ($request->has('order') && $input['order'][0]['column'] == 0) {
            $query->orderBy('id', $input['order'][0]['dir']);
        }

        return DataTables::eloquent($query)
            ->addColumn('itemDescription', function ($row) {
                return $row->itemMaster->itemDescription;
            })
            ->addColumn('minQty', function ($row) {
                return $row->minQty;
            })
            ->addColumn('maxQty', function ($row) {
                return $row->maxQty;
            })
            ->addColumn('qty', function ($row) {
                return $row->qty;
            })
            ->addColumn('unitShortCode', function ($row) {
                return $row->itemMaster->Unit->UnitShortCode;
            })
            ->addColumn('primaryCode', function ($row) {
                return $row->itemMaster->primaryCode;
            })
            ->addColumn('local', function ($row) {
                $data = [
                    'companySystemID' => $row->companyId,
                    'itemCodeSystem' => $row->itemId,
                    'wareHouseId' => null
                ];
                $itemCurrentCostAndQty = Inventory::itemCurrentCostAndQty($data);

                return $itemCurrentCostAndQty['wacValueLocal'];
            })
            ->addColumn('uuid', function ($row) {
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

    public function copySameQty($id, $arr): array
    {
        try {
            ContractBoqItems::whereIn('id', $id)
                ->update($arr);

            return ['status' => true, 'message' => trans('BoqItems updated successfully')];

        } catch (\Exception $ex) {
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

        $input = $request->all();
        $companyId = $input['companySystemID'];
        $uuid = $input['uuid'];
        $contractId = ContractMaster::select('id')->where('uuid', $uuid)->first();
        $lotData = ContractBoqItems::with(['itemMaster.unit', 'itemMaster.itemAssigned.local_currency'])
            ->where('companyId', $companyId)
            ->where('contractId', $contractId->id)
            ->get();
        $lotData = $lotData->map(function ($item) {
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
            COL_AMOUNT => "Amount"
        ];

        if ($lotData) {
            $count = 1;
            foreach ($lotData as $value) {
                $decimalCount = $value['itemMaster']['itemAssigned']['local_currency']['DecimalPlaces'];
                $data[$count][COL_ITEM] = isset($value['itemMaster']['primaryCode'])
                    ? preg_replace('/^=/', '-', $value['itemMaster']['primaryCode'])
                    : '-';
                $data[$count][COL_DESCRIPTION] = isset($value['description'])
                    ? preg_replace('/^=/', '-', $value['description'])
                    : '-';

                $data[$count][COL_MIN_QTY] = isset($value['minQty'])
                    ? preg_replace('/^=/', '-', $value['minQty'])
                    : '-';

                $data[$count][COL_MAX_QTY] = isset($value['maxQty'])
                    ? preg_replace('/^=/', '-', $value['maxQty'])
                    : '-';

                $data[$count][COL_UOM] = isset($value['itemMaster']['Unit']['UnitShortCode'])
                    ? preg_replace('/^=/', '-', $value['itemMaster']['Unit']['UnitShortCode'])
                    : '-';

                $data[$count][COL_QUANTITY] = isset($value['qty'])
                    ? preg_replace('/^=/', '-', $value['qty'])
                    : '-';

                $data[$count][COL_PRICE] = isset($value['current']['local'])
                    ? number_format(
                        $value['current']['local'],
                        $decimalCount,
                        '.',
                        ''
                    )
                    : '-';

                $data[$count][COL_AMOUNT] = isset($value['current']['local']) &&
                is_numeric($value['current']['local']) &&
                is_numeric($value['qty'])
                    ? number_format(
                        $value['current']['local'] * $value['qty'],
                        $decimalCount,
                        '.',
                        ''
                    )
                    : '-';

                $count++;
            }
        }
        return $data;
    }

}
