<?php

namespace App\Repositories;

use App\Models\NavigationRole;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermissions;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleHasPermissionsRepository
 * @package App\Repositories
 * @version October 22, 2021, 11:20 am UTC
 */

class RoleHasPermissionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'role_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RoleHasPermissions::class;
    }
    public function updateRolePermission($permissions, int $role_id, int $tenant_id)
    {
        $role = Role::find($role_id);
        $roleID = $role->id;
        $role_permissions = [];
        $roleHasPermission = new RoleHasPermissions();
        $roleHasPermission->where('role_id', $roleID)->where('tenant_id', $tenant_id)->delete();
        $data = [];

        foreach ($permissions as $val) {
            $data[] = [
                'permission_id' => $val,
                'role_id' => $roleID,
                'tenant_id' => $tenant_id
            ];
        }
        RoleHasPermissions::insert($data);

        //Assign json format value to permission column in roles table start
        $permission_name = Permission::select('name')->with(['RoleHasPermission'])->whereHas('RoleHasPermission', function ($query) use ($roleID, $tenant_id) {
            $query->where('role_id', $roleID)->where('tenant_id', $tenant_id);
        })->get();

        foreach ($permission_name as $val) {
            $role_permissions = array_merge($role_permissions, [$val['name'] => true]);
        }
        DB::table('roles')->where('id', $roleID)->update(['permissions' => $role_permissions]);
        //Assign json format value to permission column in roles table End
    }

    public function updateRoleNavigation($menus, int $role_id, int $tenant_id)
    {
        $role = Role::find($role_id);
        $roleID = $role->id;
        NavigationRole::where('role_id', $roleID)->where('tenant_id', $tenant_id)->delete();
        $data = [];

        foreach ($menus as $val) {
            $data[] = [
                'navigation_id' => $val,
                'role_id' => $roleID,
                'tenant_id' => $tenant_id
            ];
        }
        
        NavigationRole::insert($data);
    }
    public function getFormData()
    {
        $roles = Role::select(DB::raw("id as value,name as label"))
            ->whereNotIn('id', [1, 2])
            ->orderBy('id', 'asc')
            ->get();
        $tenants = GeneralService::tenantList();
        return [
            'roles' => $roles,
            'tenants' => $tenants
        ];
    }
}
