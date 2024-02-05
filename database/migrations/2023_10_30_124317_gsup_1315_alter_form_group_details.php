<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1315AlterFormGroupDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO form_group_details (form_group_id, form_field_id, sort, status, created_at, updated_at) VALUES (1, 66, 11, 1, '2023-10-30 09:26:43', NULL)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM form_group_details WHERE form_group_id = 1 AND form_field_id = 66");
    }
}
