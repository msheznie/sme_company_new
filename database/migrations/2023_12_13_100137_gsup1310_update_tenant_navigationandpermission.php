<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\GeneralService;
use App\Models\RoleHasPermissions;
use App\Models\NavigationRole;

class Gsup1310UpdateTenantNavigationandpermission extends Migration
    {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rolePermission = RoleHasPermissions::select('permission_id', 'role_id')->get();
        $navigationRole = NavigationRole::select('navigation_id', 'role_id')->get();
        $tenantList = GeneralService::tenantList();

        RoleHasPermissions::truncate();
        NavigationRole::truncate();

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropPrimary('role_has_permissions_pkey');
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->primary(['permission_id', 'role_id', 'tenant_id']);
            $table->foreign('tenant_id')
                ->references('id')->on('tenants')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        Schema::table('navigation_role', function (Blueprint $table) {
            $table->dropPrimary('navigation_role_pkey');
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->primary(['navigation_id', 'role_id', 'tenant_id']);
        });

        $insertRolePermission = $tenantList->flatMap(function ($tenant) use ($rolePermission) {
            return $rolePermission->map(function ($permission) use ($tenant) {
                return [
                    "permission_id" => $permission['permission_id'],
                    "role_id" => $permission['role_id'],
                    "tenant_id" => $tenant['value']
                ];
            });
        });

        $insertNavigationRole = $tenantList->flatMap(function ($tenant) use ($navigationRole) {
            return $navigationRole->map(function ($navigation) use ($tenant) {
                return [
                    "navigation_id" => $navigation['navigation_id'],
                    "role_id" => $navigation['role_id'],
                    "tenant_id" => $tenant['value']
                ];
            });
        });

        if ($insertRolePermission->isNotEmpty()) {
            $insertRolePermission->chunk(1000)->each(function ($chunkedPermissions) {
                RoleHasPermissions::insert($chunkedPermissions->toArray());
            });
        }

        if ($insertNavigationRole->isNotEmpty()) {
            $insertNavigationRole->chunk(1000)->each(function ($chunkedNavigations) {
                NavigationRole::insert($chunkedNavigations->toArray());
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropForeign('role_has_permissions_tenant_id_foregin');
            $table->dropPrimary(['permission_id', 'role_id', 'tenant_id']);
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::table('navigation_role', function (Blueprint $table) {
            $table->dropPrimary(['navigation_id', 'role_id', 'tenant_id']);
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
            $table->primary(['navigation_id', 'role_id']);
        });
    }
}
