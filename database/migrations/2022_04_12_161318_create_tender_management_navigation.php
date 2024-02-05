<?php

use App\Models\Navigation;
use App\Services\GeneralService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderManagementNavigation extends Migration
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
                'name' => 'Tender Management',
                'icon' => 'trending-up',
                'path' => null,
                'order_index' => 8,
                'has_children' => 1,
                'status' => 1,
            ],
            /*Setting's children start*/
            [
                'parent_id' => null,
                'name' => 'Tenders',
                'icon' => null,
                'path' => '/tender-management/tenders',
                'order_index' => 1,
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

    }
}
