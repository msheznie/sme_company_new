<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MilestonePaymentSchedules
 * @package App\Models
 * @version June 27, 2024, 9:13 am +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property integer $milestone_id
 * @property string $description
 * @property number $percentage
 * @property number $amount
 * @property string $payment_due_date
 * @property string $actual_payment_date
 * @property string $milestone_due_date
 * @property boolean $currency_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class MilestonePaymentSchedules extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_milestone_payment_schedules';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'contract_id',
        'milestone_id',
        'description',
        'percentage',
        'amount',
        'payment_due_date',
        'actual_payment_date',
        'milestone_due_date',
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
        'milestone_id' => 'integer',
        'description' => 'string',
        'percentage' => 'float',
        'amount' => 'float',
        'payment_due_date' => 'date',
        'actual_payment_date' => 'date',
        'milestone_due_date' => 'date',
        'currency_id' => 'boolean',
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


}
