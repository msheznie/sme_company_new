<?php

namespace Database\Seeders;

use App\Models\Navigation;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            [
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
                'parent_id' => 2,
                'name' => 'Roles',
                'icon' => null,
                'path' => '/configuration/roles',
                'order_index' => 1,
                'has_children' => 0,
                'status' => 1,
            ],
            [
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
                'parent_id' => null,
                'name' => 'KYC',
                'icon' => 'user',
                'path' => '/suppliers/KYC',
                'order_index' => 3,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'name' => 'Purchase Order',
                'icon' => 'truck',
                'path' => '/purchase-orders',
                'order_index' => 4,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'name' => 'Delivery Appointment Calendar',
                'icon' => 'calendar',
                'path' => '/delivery-appointment',
                'order_index' => 5,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'name' => 'Delivery Appointment',
                'icon' => 'calendar',
                'path' => '/delivery-appointment-all',
                'order_index' => 6,
                'has_children' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'name' => 'Invoice',
                'icon' => 'file-text',
                'path' => '/invoice',
                'order_index' => 7,
                'has_children' => 0,
                'status' => 1,
            ],
        ]);

          /*Assign Navigation to role*/
          $navigations = Navigation::all();
          $role = Role::findById(1);
          if($role){
            $role->navigations()->sync($navigations);
          }
    }
}
