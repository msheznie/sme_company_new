<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PermissionsModel;

class GSUP1082AddPermissionProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PermissionsModel::where('id',17)->update(['provider' => 'GET_PURCHASE_ORDERS']);
        PermissionsModel::where('id',21)->update(['provider' => 'GET_PURCHASE_ORDER_APPOINTMENTS']);
        PermissionsModel::where('id',25)->update(['provider' => 'GET_ALL_APPOINTMENT_DELIVERIES']);
        PermissionsModel::where('id',29)->update(['provider' => 'GET_INVOICES']);
        PermissionsModel::where('id',33)->update(['provider' => 'GET_TENDERS']);
        PermissionsModel::where('id',41)->update(['provider' => 'GET_PAYMENTVOUCHERS']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        PermissionsModel::where('id',17)->update(['provider' => NULL]);
        PermissionsModel::where('id',21)->update(['provider' => NULL]);
        PermissionsModel::where('id',25)->update(['provider' => NULL]);
        PermissionsModel::where('id',29)->update(['provider' => NULL]);
        PermissionsModel::where('id',33)->update(['provider' => NULL]);
        PermissionsModel::where('id',41)->update(['provider' => NULL]);
    }
}
