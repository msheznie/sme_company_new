<?php

namespace App\Jobs;

use App\Services\ActivateContractService;
use App\Services\CommonJobService;
use App\Services\TerminateContractService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TerminateContractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dispatchDB;

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

    public function handle()
    {
        $db = $this->dispatchDB;
        CommonJobService::dbSwitch($db);
        TerminateContractService::terminateContract();
    }

}
