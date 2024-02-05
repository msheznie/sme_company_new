<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'uuid' => (string) Str::uuid(),
            'name' => 'Dev Supplier',
            'email' => 'supplier@mail.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('Sup@2021'),
            'remember_token' => null,
            'registration_number' => "0",
        ]);

        DB::table('role_users')->insert([
            'user_id' => 2,
            'role_id' => 4,
        ]);

        DB::table('user_tenant')->insert([
            'user_id' => 2,
            'tenant_id' => 1,
            'company_id' => 1,
            'email' => 'supplier@mail.com',
            'status' => 1,
            'kyc_status' => 0,
            'created_at' => Carbon::now()
        ]);
    }
}
