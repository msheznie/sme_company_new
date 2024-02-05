<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup1300CreateSupplierHistoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('supplier_master_history')) {
            Schema::create('supplier_master_history', function (Blueprint $table) {
                $table->id();
                $table->integer('edit_referred')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('user_tenant_id')->nullable();
                $table->integer('tenant_id')->nullable();
                $table->string('ammend_comment')->nullable();
                $table->timestamps();
            });
        }


        if (!Schema::hasTable('supplier_details_history')) {
            Schema::create('supplier_details_history', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->integer('tenant_id')->unsigned();
                $table->integer('form_section_id')->unsigned();
                $table->integer('form_group_id')->unsigned();
                $table->integer('form_field_id')->unsigned();
                $table->integer('form_data_id')->nullable()->unsigned();
                $table->integer('sort');
                $table->text('value');
                $table->tinyInteger('status')->default(1);
                $table->integer('master_id')->nullable();
                $table->timestamps();
            });
        }
       
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_master_history');
        Schema::dropIfExists('supplier_details_history');
    }
}
