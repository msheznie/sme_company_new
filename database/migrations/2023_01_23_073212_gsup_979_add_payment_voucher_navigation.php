<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Navigation;
use App\Services\GeneralService;

class Gsup979AddPaymentVoucherNavigation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Navigation::insert([
            'id'=>13,
            'parent_id' => null,
            'name' => 'Payment Voucher',
            'icon' => 'list',
            'path' => '/payment-voucher',
            'order_index' => 10,
            'has_children' => 0,
            'status' => 1,
        ]);
        GeneralService::navigationInsert();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
