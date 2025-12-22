<?php

namespace App\Services;

use App\Utilities\ContractManagementUtils;
use Illuminate\Support\Facades\Log;

class TerminateContractService
{
    public static function terminateContract()
    {
        $history = ContractHistoryService::getInactiveTerminateContracts();

        if (!empty($history))
        {
            foreach ($history as $value)
            {
                $historyMasterRecord = ContractManagementUtils::getContractHistoryData($value->history->uuid);
                $historyMasterRecord['systemUser'] = true;
                ContractHistoryService::setContractStatusData($historyMasterRecord);
            }
        }
    }
}
