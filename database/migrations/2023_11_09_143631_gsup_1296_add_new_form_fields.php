<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Gsup1296AddNewFormFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO form_fields (id, name, display_name, type, placeholder, class, sort, status, created_at, updated_at, data_from_tenant) VALUES (67, 'vat_certificate_document', 'VAT Certificate Document', 'file_uploader', NULL, 'col-md-6 mb-4', 67, 1, '2023-11-06 09:26:43', NULL, 0)");
        DB::statement("INSERT INTO form_fields (id, name, display_name, type, placeholder, class, sort, status, created_at, updated_at, data_from_tenant) VALUES (68, 'vat_certificate_expiry_date', 'VAT Certificate Expiry date', 'date', 'YYYY-MM-DD', 'col-md-6 mb-4', 68, 1, '2022-11-06 09:26:43', NULL, 0)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM form_fields WHERE id IN (67, 68)");
    }
}
