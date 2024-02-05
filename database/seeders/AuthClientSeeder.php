<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            [
                'user_id' => null,
                'name' => 'Laravel Personal Access Client',
                'secret' => '9Hga2Q18Qa7z3lTW53t3mg8daF1tjKOGWuTpTxSz',
                'provider' => null,
                'redirect' => 'http://localhost',
                'personal_access_client' => 't',
                'password_client' => 'f',
                'revoked' => 'f'
            ],
            [
                'user_id' => null,
                'name' => 'Laravel Password Grant Client',
                'secret' => 'wI9AhNEbcS8mRvplNRRhYIZ8VUD90lsKr8ccMdPf',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => 'f',
                'password_client' => 't',
                'revoked' => 'f'
            ]
        ]);
    }
}
