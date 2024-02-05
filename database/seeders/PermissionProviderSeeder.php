<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionsModel;

class PermissionProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PermissionsModel::where('id',17)->update(['provider' => 'GET_PURCHASE_ORDERS']);
        PermissionsModel::where('id',21)->update(['provider' => 'GET_PURCHASE_ORDER_APPOINTMENTS']);
        PermissionsModel::where('id',25)->update(['provider' => 'GET_ALL_APPOINTMENT_DELIVERIES']);
        PermissionsModel::where('id',29)->update(['provider' => 'GET_INVOICES']);
        PermissionsModel::where('id',33)->update(['provider' => 'GET_TENDERS']);
        PermissionsModel::where('id',41)->update(['provider' => 'GET_PAYMENTVOUCHERS']);

    }
}
