<?php

namespace Database\Seeders;

use App\Models\{Navigation, Permission, Role, RoleHasPermissions};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
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
         
        foreach ($superAdminPermissions as $permission) {
            $role->givePermissionTo($permission['name']);
        }
    }
}
