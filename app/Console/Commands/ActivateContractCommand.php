<?php

namespace App\Console\Commands;

use App\Jobs\ActivateContractJob;
use Illuminate\Console\Command;
use App\Services\CommonJobService;

class ActivateContractCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activateContract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate Contract';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenants = CommonJobService::tenantList();
        foreach ($tenants as $tenant)
        {
            $tenantDatabase = $tenant->database ?? '';
            ActivateContractJob::dispatch($tenantDatabase);
        }
        return true;
    }
}
