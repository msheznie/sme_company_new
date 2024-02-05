<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Gsup1296AlterFormFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE form_fields SET display_name = 'VAT Registration Number' WHERE id = 36");
        DB::statement("UPDATE form_fields SET class = 'col-md-6 mb-4' WHERE id = 36");
        DB::statement("UPDATE form_fields SET class = 'col-md-6 mb-4' WHERE id = 31");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE form_fields SET display_name = 'Tax Registration Number' WHERE id = 36");
        DB::statement("UPDATE form_fields SET class = 'col-md-4 mb-4' WHERE id = 36");
        DB::statement("UPDATE form_fields SET class = 'col-md-12 mb-4' WHERE id = 31");
    }
}
