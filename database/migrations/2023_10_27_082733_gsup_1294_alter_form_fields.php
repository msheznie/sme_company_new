<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1294AlterFormFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO form_fields (id, name, display_name, type, placeholder, class, sort, status, created_at, updated_at, data_from_tenant)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [66, 'local_supplier', 'Local Supplier', 'checkbox', NULL, 'col-md-4', 66, 1, '2022-10-26 09:26:43', NULL, 0]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM  'form_fields' WHERE 'id' = 64");
    }
}
