<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use App\Models\PermissionsModel;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Exception;

class NavigationAuthendication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $role = (Auth::user()) ? Auth::user()->fetchUserRole->role : null;
        $provider = $request->input('request');
        $route_name = \Route::current()->getName();

        if($provider) {
            $permission = PermissionsModel::where('provider',$provider)->first();
        }else {
            $permission = PermissionsModel::where('name',str_replace('api.', '', $route_name))->first();
        }


       /* if($role!==null && $permission && $role->hasPermissionTo($permission->name) != 1)
        {
            return errorMsgs($role);
        }*/

        return $next($request);
    }
}

function errorMsgs($messsage){
    return response()->json([
        'success' => false,
        'message' => $messsage
    ], 403);
}
