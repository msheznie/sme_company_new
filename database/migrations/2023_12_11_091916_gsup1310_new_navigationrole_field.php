<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1310NewNavigationroleField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->integer('tenant_id')->nullable()->after('role_id');
        });
        Schema::table('navigation_role', function (Blueprint $table) {
            $table->integer('tenant_id')->nullable()->after('role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
        Schema::table('navigation_role', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
}
