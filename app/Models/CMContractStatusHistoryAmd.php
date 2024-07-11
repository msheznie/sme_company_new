<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractStatusHistoryAmd
 * @package App\Models
 * @version July 3, 2024, 9:43 am +04
 *
 * @property integer $id
 * @property integer $contract_history_id
 * @property string $uuid
 * @property integer $contractID
 * @property integer $milestoneID
 * @property integer $changedFrom
 * @property integer $changedTo
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class CMContractStatusHistoryAmd extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_milestone_status_history_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'contract_history_id',
        'uuid',
        'contractID',
        'milestoneID',
        'changedFrom',
        'changedTo',
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
        'contract_history_id' => 'integer',
        'uuid' => 'string',
        'contractID' => 'integer',
        'milestoneID' => 'integer',
        'changedFrom' => 'integer',
        'changedTo' => 'integer',
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
        'contract_history_id' => 'nullable|integer',
        'uuid' => 'required|string|max:255',
        'contractID' => 'required|integer',
        'milestoneID' => 'required|integer',
        'changedFrom' => 'required|integer',
        'changedTo' => 'required|integer',
        'companySystemID' => 'required|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
