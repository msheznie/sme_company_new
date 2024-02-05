<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBidTender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tenant', function (Blueprint $table) {
            $table->integer('is_bid_tender')->default(0)->after('kyc_status')->nullable();
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
            $table->dropColumn('is_bid_tender');
        });
    }
}
