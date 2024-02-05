<?php

use App\Models\Navigation;
use App\Services\GeneralService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup469AddNavigationForPriceList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Navigation::insert([
            [
                'parent_id' => null,
                'name' => 'Price List',
                'icon' => 'list',
                'path' => '/price-list',
                'order_index' => 9,
                'has_children' => 0,
                'status' => 1,
            ],
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
