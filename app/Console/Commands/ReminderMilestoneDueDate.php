<?php

namespace App\Console\Commands;

use App\Jobs\ReminderMilestoneDueDateJob;
use App\Services\CommonJobService;
use Illuminate\Console\Command;

class ReminderMilestoneDueDate extends Command
{
    protected $signature = 'reminderMilestoneDueDate';
    protected $description = 'Reminder Milestone Due Date';

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
            ReminderMilestoneDueDateJob::dispatch($tenantDatabase);
        }
        return true;
    }

}
