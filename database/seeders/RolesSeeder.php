<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = array('Super Admin','Admin','Module User','Supplier');
        foreach($userRole as $val){ 
            Role::create(['name' => $val]);
        } 
    }
}
