<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractOverallPenaltyAmd
 * @package App\Models
 * @version July 23, 2024, 2:01 pm +04
 *
 * @property integer $overall_penalty_id
 * @property integer $contract_history_id
 * @property string $uuid
 * @property integer $contract_id
 * @property number $minimum_penalty_percentage
 * @property number $minimum_penalty_amount
 * @property number $maximum_penalty_percentage
 * @property number $maximum_penalty_amount
 * @property number $actual_percentage
 * @property number $actual_penalty_amount
 * @property string|\Carbon\Carbon $penalty_circulation_start_date
 * @property string|\Carbon\Carbon $actual_penalty_start_date
 * @property boolean $penalty_circulation_frequency
 * @property integer $due_in
 * @property number $due_penalty_amount
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractOverallPenaltyAmd extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_overall_penalty_amd';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'overall_penalty_id',
        'contract_history_id',
        'uuid',
        'contract_id',
        'minimum_penalty_percentage',
        'minimum_penalty_amount',
        'maximum_penalty_percentage',
        'maximum_penalty_amount',
        'actual_percentage',
        'actual_penalty_amount',
        'penalty_circulation_start_date',
        'actual_penalty_start_date',
        'penalty_circulation_frequency',
        'due_in',
        'due_penalty_amount',
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
        'overall_penalty_id' => 'integer',
        'contract_history_id' => 'integer',
        'uuid' => 'string',
        'contract_id' => 'integer',
        'minimum_penalty_percentage' => 'float',
        'minimum_penalty_amount' => 'float',
        'maximum_penalty_percentage' => 'float',
        'maximum_penalty_amount' => 'float',
        'actual_percentage' => 'float',
        'actual_penalty_amount' => 'float',
        'penalty_circulation_start_date' => 'datetime',
        'actual_penalty_start_date' => 'datetime',
        'penalty_circulation_frequency' => 'boolean',
        'due_in' => 'integer',
        'due_penalty_amount' => 'float',
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
        'overall_penalty_id' => 'nullable|integer',
        'contract_history_id' => 'nullable|integer',
        'uuid' => 'required|string|max:255',
        'contract_id' => 'required|integer',
        'minimum_penalty_percentage' => 'nullable|numeric',
        'minimum_penalty_amount' => 'nullable|numeric',
        'maximum_penalty_percentage' => 'nullable|numeric',
        'maximum_penalty_amount' => 'nullable|numeric',
        'actual_percentage' => 'nullable|numeric',
        'actual_penalty_amount' => 'nullable|numeric',
        'penalty_circulation_start_date' => 'nullable',
        'actual_penalty_start_date' => 'nullable',
        'penalty_circulation_frequency' => 'nullable|boolean',
        'due_in' => 'nullable|integer',
        'due_penalty_amount' => 'nullable|numeric',
        'company_id' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
