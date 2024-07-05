<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PeriodicBillings
 * @package App\Models
 * @version June 26, 2024, 9:09 am +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property number $amount
 * @property string|\Carbon\Carbon $start_date
 * @property string|\Carbon\Carbon $end_date
 * @property boolean $occurrence_type
 * @property integer $due_in
 * @property integer $no_of_installment
 * @property number $inst_payment_amount
 * @property boolean $currency_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class PeriodicBillings extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_periodic_billing';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'uuid',
        'contract_id',
        'amount',
        'start_date',
        'end_date',
        'occurrence_type',
        'due_in',
        'no_of_installment',
        'inst_payment_amount',
        'currency_id',
        'company_id',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'contract_id' => 'integer',
        'amount' => 'float',
        'start_date' => 'string',
        'end_date' => 'string',
        'occurrence_type' => 'integer',
        'due_in' => 'integer',
        'no_of_installment' => 'integer',
        'inst_payment_amount' => 'float',
        'currency_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function currency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'currency_id', 'currencyID');
    }
    public static function getContractPeriodicBilling($contractID)
    {
        return PeriodicBillings::select('uuid', 'amount', 'start_date', 'end_date', 'occurrence_type', 'due_in',
                'no_of_installment', 'inst_payment_amount', 'currency_id')
            ->with([
                'currency' => function ($q)
                {
                    $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                }
            ])
            ->where('contract_id', $contractID)->first();
    }

    public static function existPeriodicBilling($contractId, $companySystemID)
    {
        return PeriodicBillings::where('contract_id', $contractId)
            ->where('company_id', $companySystemID)
            ->first();
    }
}
