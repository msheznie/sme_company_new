<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKycStatusToUserTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tenant', function (Blueprint $table) {
            $table->integer('kyc_status')
                ->nullable()
                ->after('status')
                ->comment('0 - submitted, 1 - viewed, 2 - approved, 3 - rejected');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_tenant', function (Blueprint $table) {
            $table->dropColumn('kyc_status');
        });
    }
}
