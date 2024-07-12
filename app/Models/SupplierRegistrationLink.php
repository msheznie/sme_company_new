<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SupplierRegistrationLink extends Model
{
    public $table = 'srm_supplier_registration_link';

    protected $primaryKey  = 'id';

    public $fillable = [
        'name',
        'email',
        'registration_number',
        'company_id',
        'token',
        'STATUS',
        'token_expiry_date_time',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'uuid',
        'supplier_master_id',
        'confirmed_by_emp_id',
        'confirmed_by_name',
        'confirmed_date',
        'approved_by_emp_name',
        'approved_yn',
        'approved_by_emp_id',
        'RollLevForApp_curr',
        'timesReferred',
        'confirmed_yn',
        'refferedBackYN',
        'is_bid_tender',
        'created_via',
        'sub_domain',
        'is_existing_erp_supplier'
    ];

    protected $casts = [
        'id' => 'integer',
        'supplier_master_id' => 'integer',
        'uuid' => 'string',
        'is_existing_erp_supplier' => 'integer',
        ];

    public static function getSupplierId($id)
    {
        return SupplierRegistrationLink::where('supplier_master_id', $id)
            ->first();
    }

}
