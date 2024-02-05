<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Gsup1453UpdateEmailToSupplierEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('form_fields')
            ->where('id', 69)
            ->update([
                'name' => 'email_address',
                'display_name' => 'Supplier Email',
                'type' => 'text',
                'placeholder' =>  'Enter Supplier Email',
                'class' =>  'col-md-6 mb-4',
                'sort' =>  69,
                'status' =>  1,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
