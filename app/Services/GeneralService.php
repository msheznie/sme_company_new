<?php

namespace App\Services;

use App\Models\FormTemplateMaster;
use App\Models\{Navigation, Permission, PermissionsModel, Role, RoleHasPermissions, Tenant, UserTenant};
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class GeneralService
{
    public function __construct()
    {
    }

    public static function getTemplateMaster($tenantID)
    {
        return 0;
    }
    public function navigationInsert()
    {
        //permission assign for admin start
        $navigations = Navigation::all();
        $role = Role::findById(1);
        if ($role) {
            $role->navigations()->sync($navigations);
        }
        $rolePermissions = RoleHasPermissions::where('role_id','!=',1)->get();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::query()->truncate();

        $navigations = Navigation::with('parent')->where('path', '!=', '')
            ->orWhereNotNull('path')->get();
        $permissions = Permission::$permissions;

        $role_permissions = [];
        $rows = [];
        foreach ($navigations as $navigation) {
            foreach ($permissions as $permission => $description) {
                $parent_name = '';
                if ($navigation->parent) {
                    $parent_name = Str::of($navigation->parent->name)->lower()->slug('-')->append('.');
                }
                $name = Str::of($navigation->name)->lower()->slug('-');
                $permission_name = "{$parent_name}{$name}.{$permission}";
                $role_permissions = array_merge($role_permissions, [$permission_name => true]);
                Permission::create(['name' => $permission_name, 'description' => $description, 'navigation_id' => $navigation->id]);
            }
        }
        $role = Role::find(1);
        if ($role) {
            DB::table('roles')->where('id', 1)->update(['permissions' => $role_permissions]);
        }
        $superAdminPermissions = Permission::select('name')->get();

        foreach($rolePermissions as $val){
            $data['permission_id'] = $val['permission_id'];
            $data['role_id'] = $val['role_id'];
            RoleHasPermissions::insert($data);
        }

        foreach ($superAdminPermissions as $permission) {
            $role->givePermissionTo($permission['name']);
        }
        //roles and permission assign for admin end
    }
    public static function getTenantIdFromUserId($userId)
    {
         return UserTenant::select('tenant_id')->where('user_id',$userId)->first();
    }
    public static function dateAddTime($date)
    {
        if ($date) {

            $time = (new Carbon($date))->format('H:i:s');
            if ($time != '00:00:00') {
                return new Carbon($date);
            }

            $date = self::dateOnlyFormat($date);
            return new Carbon($date . ' ' . date("h:i:sa"));
        } else {
            return null;
        }
    }
    public static function dateOnlyFormat($date)
    {
        if ($date) {
            return (new Carbon($date))->format('Y-m-d');
        } else {
            return '';
        }
    }
    public static function tenantList()
    {
        return Tenant::select(DB::raw("id as value, name as label"))->where('status', 1)->get();
    }
}
