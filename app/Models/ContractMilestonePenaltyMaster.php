<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractMilestonePenaltyMaster
 * @package App\Models
 * @version July 28, 2024, 10:50 am +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property number $minimum_penalty_percentage
 * @property number $minimum_penalty_amount
 * @property number $maximum_penalty_percentage
 * @property number $maximum_penalty_amount
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractMilestonePenaltyMaster extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_milestone_penalty_master';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'contract_id',
        'minimum_penalty_percentage',
        'minimum_penalty_amount',
        'maximum_penalty_percentage',
        'maximum_penalty_amount',
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
        'minimum_penalty_percentage' => 'float',
        'minimum_penalty_amount' => 'float',
        'maximum_penalty_percentage' => 'float',
        'maximum_penalty_amount' => 'float',
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
        'uuid' => 'required|string|max:255',
        'contract_id' => 'required|integer',
        'minimum_penalty_percentage' => 'nullable|numeric',
        'minimum_penalty_amount' => 'nullable|numeric',
        'maximum_penalty_percentage' => 'nullable|numeric',
        'maximum_penalty_amount' => 'nullable|numeric',
        'company_id' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function getMilestonePenaltyMaster($contractID, $companyId)
    {
        return ContractMilestonePenaltyMaster::select('id','uuid','minimum_penalty_percentage','minimum_penalty_amount',
            'maximum_penalty_percentage', 'maximum_penalty_amount')
            ->where('contract_id', $contractID)
            ->where('company_id', $companyId)
            ->first();

    }


}
