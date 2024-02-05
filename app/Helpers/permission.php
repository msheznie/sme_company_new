<?php

namespace App\Helper;

use App\Services\NavigationWithPermissionService;
use InfyOm\Generator\Utils\ResponseUtil;

class Helper
{
    public static function can($permission)
    {
        $userRolePermissions = new NavigationWithPermissionService((auth()->user())->fetchUserRole->role_id);
        abort_if(! $userRolePermissions->hasAccess($permission),response()->json(ResponseUtil::makeError('Access denied'), 401));
    }
            /**
             * Paginate the given query.
             *
             * @param int|null $perPage
             * @param array $columns
             * @param string $pageName
             * @param int|null $page
             * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 
             * @throws \InvalidArgumentException
             * @static 
             */ 
    public static function paginate($perPage = null, $columns = [], $pageName = 'page', $page = null){ 
           /** @var \Illuminate\Foundation\Application $instance */
        return $instance->paginate($perPage, $columns, $pageName, $page);
    }
}
