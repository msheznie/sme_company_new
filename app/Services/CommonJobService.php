<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommonJobService
{
    public static function dbSwitch( $db )
    {
        if(!$db || $db == '')
        {
            Log::info("db name is empty");
            return false;
        }

        Config::set("database.connections.mysql.database", $db);
        DB::reconnect('mysql');
        return true;
    }
    public static function tenantList()
    {
        return Tenant::getTenantList();
    }
}
