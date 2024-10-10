<?php

namespace App\Console\Commands;

use App\Jobs\ReminderDocumentExpiryJob;
use App\Services\CommonJobService;
use Illuminate\Console\Command;

class ReminderDocumentExpiry extends Command
{
    protected $signature = 'ReminderDocumentExpiry';
    protected $description = 'Reminder Document Expiry';

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
            ReminderDocumentExpiryJob::dispatch($tenantDatabase);
        }
        return true;
    }

}
