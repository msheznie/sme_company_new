<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
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
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('Adm@2021'),
            'remember_token' => null,
            'registration_number' => "0",
        ]);

        DB::table('role_users')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);
    }
}
