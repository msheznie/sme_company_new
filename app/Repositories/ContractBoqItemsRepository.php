<?php

namespace App\Repositories;

use App\Helpers\inventory;
use App\Models\ContractBoqItems;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

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
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContractBoqItems::class;
    }

    public function getBoqItems(Request $request)
    {
        $input = $request->all();
        $companyId = $input['companyId'];

        $query = ContractBoqItems::select('uuid', 'minQty', 'maxQty', 'qty', 'companyId', 'itemId')
            ->with( ['itemMaster.unit' => function ($query) {
                $query->select('UnitShortCode');
            }])
            ->where('companyId', $companyId);

        if ($request->has('order')) {
            if ($input['order'][0]['column'] == 0) {
                $query->orderBy('id', $input['order'][0]['dir']);
            }
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
            })->addColumn('primaryCode', function ($row) {
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


    public function findByUuid($id){
       return ContractBoqItems::where('uuid', $id)->first();
    }

    public function copyIdsRange($row){
       return ContractBoqItems::select('id')
           ->where('id', '<',  $row->id)
           ->where('companyId', $row->companyId)
           ->where('contractId', $row->contractId)
           ->get()
           ->pluck('id')
           ->toArray();
    }

    public function copySameQty($id, $arr): array
    {
        try{
            ContractBoqItems::whereIn('id', $id)
                ->update($arr);

            return ['status' => true, 'message' => trans('BoqItems updated successfully')];

        } catch(\Exception $ex) {
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }

    public function exportBoqItemsReport(Request $request)
    {
        $input = $request->all();
        $search = false;
        $companyId = $input['companySystemID'];
        $lotData = ContractBoqItems::with(['itemMaster.unit'])->where('companyId', $companyId)->get();
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

        $data[0]['Item'] = "Item";
        $data[0]['Description'] = "Description";
        $data[0]['Min Qty'] = "Min Qty";
        $data[0]['Max Qty'] = "Max Qty";
        $data[0]['UOM'] = "UOM";
        $data[0]['Quantity'] = "Quantity";
        $data[0]['Price'] = "Price";
        $data[0]['Amount'] = "Amount";
        if ($lotData) {
            $count = 1;
            foreach ($lotData as $value) {
                $data[$count]['Item'] = isset($value['itemMaster']['primaryCode']) ? preg_replace('/^=/', '-', $value['itemMaster']['primaryCode']) : '-';
                $data[$count]['Description'] = isset($value['description']) ? preg_replace('/^=/', '-', $value['description']) : '-';
                $data[$count]['Min Qty'] = isset($value['minQty']) ? preg_replace('/^=/', '-', $value['minQty']) : '-';
                $data[$count]['Max Qty'] = isset($value['maxQty']) ? preg_replace('/^=/', '-', $value['maxQty']) : '-';
                $data[$count]['UOM'] = isset($value['itemMaster']['Unit']['UnitShortCode']) ? preg_replace('/^=/', '-', $value['itemMaster']['Unit']['UnitShortCode']) : '-';
                $data[$count]['Quantity'] = isset($value['qty']) ? preg_replace('/^=/', '-', $value['qty']) : '-';
                $data[$count]['Price'] = isset($value['current']['local']) ? preg_replace('/^=/', '-', $value['current']['local']) : '-';
                $data[$count]['Amount'] = isset($value['current']['local']) ? preg_replace('/^=/', '-', $value['current']['local'] *  $value['qty']) : '-';
                $count++;
            }
        }
        return $data;
    }

}
