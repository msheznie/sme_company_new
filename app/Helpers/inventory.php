<?php
/**
 * =============================================
 * -- File Name : inventory.php
 * -- Project Name : ERP
 * -- Module Name :  email class
 * -- Author : Mohamed Fayas
 * -- Create date : 15 - August 2018
 * -- Description : This file contains the all the common inventory function
 * -- REVISION HISTORY
 */

namespace App\Helpers;

use App\Models\ErpItemLedger;
use App\Models\ItemAssigned;
use Response;

class inventory
{

    /**
     * get item current wac and qty
     * @param $params : accept parameters as an array
     * $array 1- companySystemID : company auto id
     * $array 2- itemCodeSystem : item Code System
     * $array 3- wareHouseId : wareHouse id
     * @return mixed
     */
    public static function itemCurrentCostAndQty($params)
    {

        $output = array('currentStockQty' => 0,
            'wacValueLocal' => 0,
            'wacValueReporting' => 0
        );

        if (array_key_exists('itemCodeSystem', $params) && array_key_exists('companySystemID', $params)) {

            $item = ItemAssigned::where('itemCodeSystem', $params['itemCodeSystem'])
                ->where('companySystemID', $params['companySystemID'])
                ->first();

            if (!empty($item)) {
                $itemLedgerRec = ErpItemLedger::selectRaw('
                                                        if(round(sum(inOutQty),2)=0,0,round((sum((inOutQty*wacLocal))/round(sum(inOutQty),2)),9)) as wacCostLocal,
                                                        if(round(sum(inOutQty),2)=0,0,round((sum((inOutQty*wacRpt))/round(sum(inOutQty),2)),9)) as wacCostRpt')
                    ->where('companySystemID', $params['companySystemID'])
                    ->where('fromDamagedTransactionYN', 0)
                    ->where('itemSystemCode', $params['itemCodeSystem'])
                    ->groupBy('companySystemID', 'itemSystemCode')->first();


                if (!empty($itemLedgerRec)) {
                    $output['wacValueLocal'] = $itemLedgerRec->wacCostLocal;
                    $output['wacValueReporting'] = $itemLedgerRec->wacCostRpt;

                }

                $output['currentStockQty'] = ErpItemLedger::where('itemSystemCode', $params['itemCodeSystem'])
                    ->where('companySystemID', $params['companySystemID'])
                    ->groupBy('itemSystemCode')
                    ->sum('inOutQty');
            }
        }
        return $output;
    }

}
