<?php

namespace App\Services;

use App\Models\ContractMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ActivateContractService
{
    public static function activateContract()
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $contractList = ContractMaster::getCurrentInactiveContract($todayDate);
        if ($contractList && $contractList->isNotEmpty())
        {
            $contractIds = $contractList->pluck('id')->toArray();
            ContractMaster::whereIn('id', $contractIds)->update(['status' => -1]);
        }
    }
}
