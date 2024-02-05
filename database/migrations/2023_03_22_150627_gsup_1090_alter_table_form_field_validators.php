<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1090AlterTableFormFieldValidators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 2 and form_field_id =2");
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 8 and form_field_id = 5;");
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 11 and form_field_id = 6;");
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 15 and form_field_id = 7;");
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 86 and form_field_id= 45;");
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 96 and form_field_id = 49;");
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 99 and form_field_id = 49;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 2 and form_field_id =2");
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 8 and form_field_id = 5;");
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 11 and form_field_id = 6;");
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 15 and form_field_id = 7;");
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 86 and form_field_id= 45;");
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 96 and form_field_id = 49;");
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 99 and form_field_id = 49;");
    }
}
