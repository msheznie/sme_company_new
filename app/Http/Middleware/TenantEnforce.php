<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantEnforce
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $dbRoutes = [
            'api/v1/contract/confirm-contract',
            'api/v1/approvals/contract-approvals'
        ];
        if (env('IS_MULTI_TENANCY', false))
        {
            $url = $request->fullUrl();
            $host = explode('/', $url);
            $subDomain = count($host) > 3 ? $host[3] : '';

            if ($subDomain == 'www')
            {
                $subDomain = $host[1];
            }

            if ($subDomain != 'localhost:8000')
            {
                if (!$subDomain)
                {
                    return response(['message' => $subDomain . "Not found"], 404);
                }
                $tenant = Tenant::where('sub_domain', 'like', $subDomain)->first();
                if (!empty($tenant))
                {
                    if (in_array($request->route()->uri, $dbRoutes))
                    {
                        $request->request->add(['db' => $tenant->database]);
                    }
                    Config::set("database.connections.mysql.database", $tenant->database);
                    DB::reconnect('mysql');
                } else
                {
                    return response(['message' => "Sub domain " . $subDomain . " not found"], 404);
                }
            }
        }
        else
        {
            if (in_array($request->route()->uri, $dbRoutes))
            {
                $request->request->add(['db' => ""]);
            }
        }
        return $next($request);
    }
}
