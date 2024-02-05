<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            AuthClientSeeder::class,
            RolesSeeder::class,
            NavigationSeeder::class,
            RolesAndPermissionsSeeder::class,
            DefaultKYCFormDataSeeder::class,
            TenantSeeder::class,
            DevDataSeeder::class,
            PermissionProviderSeeder::class
        ]);
    }
}
