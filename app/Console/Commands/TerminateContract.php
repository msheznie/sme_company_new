<?php

namespace App\Console\Commands;

use App\Jobs\TerminateContractJob;
use App\Services\CommonJobService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TerminateContract extends Command
{
    protected $signature = 'terminateContract';
    protected $description = 'Terminate Contract';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tenants = CommonJobService::tenantList();
        foreach ($tenants as $tenant)
        {
            $tenantDatabase = $tenant->database ?? '';
            TerminateContractJob::dispatch($tenantDatabase);
        }
        return true;
    }
}
