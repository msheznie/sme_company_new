<?php

use App\Models\Navigation;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class CreateNavigations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('navigations');

            $table->string('name')->unique();
            $table->string('path')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order_index');
            $table->integer('has_children');
            $table->integer('status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('navigations')->truncate();
        Navigation::insert([
            [
                'parent_id' => null,
                'name' => 'Dashboard',
                'icon' => 'home',
                'path' => '/dashboard',
                'order_index' => 1,
                'has_children' => 0,
                'status' => 1,
            ],
        ]);

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigations');
    }
}
