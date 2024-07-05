<?php

namespace App\Jobs;

use App\Services\ActivateContractService;
use App\Services\CommonJobService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReminderContractExpiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dispatchDB;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dispatchDB)
    {
        if (env('IS_MULTI_TENANCY', false))
        {
            self::onConnection('database_main_cm');
        } else
        {
            self::onConnection('database_cm');
        }
        $this->dispatchDB = $dispatchDB;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $db = $this->dispatchDB;
        CommonJobService::dbSwitch($db);
        ActivateContractService::reminderContractExpiry();
        ActivateContractService::reminderContractExpiryAfter();
    }
}
