<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Gsup1296AddNewFormFieldValidators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO form_field_validators (form_field_id, form_validator_id, value, status, created_at, updated_at) VALUES (67, 1, 'REQUIRED', 1, '2022-11-08 09:26:43', NULL)");
        DB::statement("INSERT INTO form_field_validators (form_field_id, form_validator_id, value, status, created_at, updated_at) VALUES (36, 1, 'REQUIRED', 1, '2022-11-08 09:26:43', NULL)");
        DB::statement("INSERT INTO form_field_validators (form_field_id, form_validator_id, value, status, created_at, updated_at) VALUES (68, 1, 'REQUIRED', 1, '2022-11-08 09:26:43', NULL)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM form_field_validators WHERE (form_field_id = 67 AND form_validator_id = 1 AND value = 'REQUIRED') OR (form_field_id = 36 AND form_validator_id = 1 AND value = 'REQUIRED') OR (form_field_id = 68 AND form_validator_id = 1 AND value = 'REQUIRED')");
    }
}
