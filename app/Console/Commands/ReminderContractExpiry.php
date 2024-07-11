<?php

namespace App\Console\Commands;

use App\Services\CommonJobService;
use Illuminate\Console\Command;
use App\Jobs\ReminderContractExpiryJob;

class ReminderContractExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminderContractExpiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder Contract Expiry';

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
            ReminderContractExpiryJob::dispatch($tenantDatabase);
        }
        return true;
    }
}
