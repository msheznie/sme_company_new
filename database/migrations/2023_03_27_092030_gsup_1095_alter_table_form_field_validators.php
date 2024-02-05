<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1095AlterTableFormFieldValidators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 6 and form_field_id = 4");
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 7 and form_field_id = 4");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 6 and form_field_id = 4");
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 7 and form_field_id = 4");
    }
}
