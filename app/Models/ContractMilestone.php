<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractMilestone
 * @package App\Models
 * @version April 26, 2024, 10:07 am +04
 *
 * @property string $uuid
 * @property integer $contractID
 * @property string $title
 * @property integer $percentage
 * @property number $amount
 * @property boolean $status
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractMilestone extends Model
{

    use HasFactory;

    public $table = 'cm_contract_milestone';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $hidden = ['id', 'contractID'];

    public $fillable = [
        'uuid',
        'contractID',
        'title',
        'percentage',
        'amount',
        'status',
        'companySystemID',
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
        'contractID' => 'integer',
        'title' => 'string',
        'percentage' => 'float',
        'amount' => 'float',
        'status' => 'integer',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uuid' => 'required|string|max:200',
        'contractID' => 'required|integer',
        'title' => 'required|string|max:255',
        'percentage' => 'required|numeric',
        'amount' => 'required|numeric',
        'status' => 'required|boolean',
        'companySystemID' => 'required|integer',
        'created_by' => 'required|integer',
        'updated_by' => 'required|integer',
        'deleted_at' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function getMilestoneDataByTitle($contractId,$title)
    {
            return self::where('title', $title)
                ->where('contractID', $contractId)
                ->first();
    }
    public static function getMilestoneData($contractId,$id)
    {
        return self::where('contractID', $contractId)
            ->where('id', $id)
            ->first();
    }


}
