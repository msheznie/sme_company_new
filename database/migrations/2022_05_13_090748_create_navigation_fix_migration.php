<?php

use App\Models\Navigation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralService;
class CreateNavigationFixMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('navigations')->truncate();
        DB::table('role_has_permissions')->truncate();

        Navigation::insert([
            [
                'id'=>1,
                'parent_id' => null,
                'name' => 'Dashboard',
                'icon' => 'home',
                'path' => '/dashboard',
                'order_index' => 1,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'id'=>2,
                'parent_id' => null,
                'name' => 'Configuration',
                'icon' => 'settings',
                'path' => null,
                'order_index' => 2,
                'has_children' => 1,
                'status' => 1,
            ],
            /*Setting's children start*/
            [
                'id'=>3,
                'parent_id' => 2,
                'name' => 'Roles',
                'icon' => null,
                'path' => '/configuration/roles',
                'order_index' => 1,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'id'=>4,
                'parent_id' => 2,
                'name' => 'Navigation',
                'icon' => null,
                'path' => '/configuration/navigations',
                'order_index' => 2,
                'has_children' => 0,
                'status' => 1,
            ],
             /*Setting's children end*/
            [
                'id'=>5,
                'parent_id' => null,
                'name' => 'KYC',
                'icon' => 'user',
                'path' => '/suppliers/KYC',
                'order_index' => 3,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'id'=>6,
                'parent_id' => null,
                'name' => 'Purchase Order',
                'icon' => 'truck',
                'path' => '/purchase-orders',
                'order_index' => 4,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'id'=>7,
                'parent_id' => null,
                'name' => 'Delivery Appointment Calendar',
                'icon' => 'calendar',
                'path' => '/delivery-appointment',
                'order_index' => 5,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'id'=>8,
                'parent_id' => null,
                'name' => 'Delivery Appointment',
                'icon' => 'calendar',
                'path' => '/delivery-appointment-all',
                'order_index' => 6,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'id'=>9,
                'parent_id' => null,
                'name' => 'Invoice',
                'icon' => 'file-text',
                'path' => '/invoice',
                'order_index' => 7,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'id'=>10,
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
                'id'=>11,
                'parent_id' => 10,
                'name' => 'Tenders',
                'icon' => null,
                'path' => '/tender-management/tenders',
                'order_index' => 1,
                'has_children' => 0,
                'status' => 1,
            ], 
            [
                'id'=>12,
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
         
    }
}
