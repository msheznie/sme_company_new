<?php

namespace App\Services;

use App\Models\ContractMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\ContractHistoryService;

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

            foreach ($contractList as $contract)
            {
                $contractId = $contract->id;
                $currentStatus = -1;
                $companyId = $contract->companySystemID;

                ContractMaster::where('id', $contractId)->update(['status' => $currentStatus]);
                ContractHistoryService::insertHistoryStatus($contractId, $currentStatus, $companyId, null);
            }

        }
    }
}
