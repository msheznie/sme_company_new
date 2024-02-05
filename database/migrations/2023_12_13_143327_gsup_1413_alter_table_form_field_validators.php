<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1413AlterTableFormFieldValidators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 101 and form_field_id = 50");
        DB::statement("UPDATE form_field_validators SET value = 13 WHERE id = 102 and form_field_id = 50");

        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 129 and form_field_id = 59");
        DB::statement("UPDATE form_field_validators SET value = 13 WHERE id = 126 and form_field_id = 59");

        DB::statement("UPDATE form_field_validators SET status = 0 WHERE id = 130 and form_field_id = 60");
        DB::statement("UPDATE form_field_validators SET value = 13 WHERE id = 131 and form_field_id = 60");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 101 and form_field_id = 50");
        DB::statement("UPDATE form_field_validators SET value = 15 WHERE id = 102 and form_field_id = 50");

        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 129 and form_field_id = 59");
        DB::statement("UPDATE form_field_validators SET value = 15 WHERE id = 126 and form_field_id = 59");

        DB::statement("UPDATE form_field_validators SET status = 1 WHERE id = 130 and form_field_id = 60");
        DB::statement("UPDATE form_field_validators SET value = 15 WHERE id = 131 and form_field_id = 60");
    }
}
