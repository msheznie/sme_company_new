<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Navigation;
use App\Services\GeneralService;

class GSUP1034addRfxNavigation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Navigation::insert([
            'id'=> 14,
            'parent_id' => 10,
            'name' => 'RFX',
            'icon' => null,
            'path' => '/tender-management/rfx',
            'order_index' => 2,
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
