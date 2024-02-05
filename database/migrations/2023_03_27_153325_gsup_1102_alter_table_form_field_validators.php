<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1102AlterTableFormFieldValidators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 10 and form_field_id = 5");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 10 and form_field_id = 5");
    }
}
