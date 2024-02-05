<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Gsup1296AddFormGroupDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO form_group_details (form_group_id, form_field_id, sort, status, created_at, updated_at) VALUES (11, 67, 37, 1, '2022-11-06 09:26:43', NULL)");
        DB::statement("INSERT INTO form_group_details (form_group_id, form_field_id, sort, status, created_at, updated_at) VALUES (11, 68, 38, 1, '2022-11-06 09:26:43', NULL)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM form_group_details WHERE form_group_id = 11 AND form_field_id IN (67, 68)");
    }
}
