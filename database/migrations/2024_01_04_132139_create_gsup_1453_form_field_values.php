<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGsup1453FormFieldValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('form_field_validators')->insert([
            [
                'form_field_id' => 69,
                'form_validator_id' => 2,
                'value' => 'EMAIL',
                'status' =>  1
            ],
            [
                'form_field_id' => 69,
                'form_validator_id' => 1,
                'value' => 'REQUIRED',
                'status' =>  1
            ],
        ]);

        DB::table('form_fields')->insert([
            [
                'id' => 69,
                'name' => 'email_address',
                'display_name' => 'Email Address',
                'type' => 'text',
                'placeholder' =>  'Enter Email',
                'class' =>  'col-md-6 mb-4',
                'sort' =>  69,
                'status' =>  1,
            ]
        ]);

        DB::table('form_group_details')->insert([
            [
                'form_group_id' => 1,
                'form_field_id' => 69,
                'sort' => 5,
                'status' =>  1
            ]
        ]);
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
