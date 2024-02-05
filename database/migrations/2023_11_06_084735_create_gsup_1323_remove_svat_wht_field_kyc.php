<?php

use App\Models\FormField;
use App\Models\FormGroupDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsup1323RemoveSvatWhtFieldKyc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        FormField::where('id', 30)->update([
            'class' => 'col-md-12'
        ]);

        FormField::whereIn('id', [32, 33, 34, 35])->update([
            'status' => 0
        ]);

        FormGroupDetail::where('form_group_id', 11)
            ->whereIn('form_field_id', [32, 33, 34, 35])
            ->update(['status' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        FormField::where('id', 30)->update([
            'class' => 'col-md-4'
        ]);

        FormField::whereIn('id', [32, 33, 34, 35])->update([
            'status' => 1
        ]);

        FormGroupDetail::where('form_group_id', 11)
            ->whereIn('form_field_id', [32, 33, 34, 35])
            ->update(['status' => 1]);
    }
}
