<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractDeliverableAmd
 * @package App\Models
 * @version July 3, 2024, 10:33 am +04
 *
 * @property integer $id
 * @property integer $contract_history_id
 * @property string $uuid
 * @property integer $contractID
 * @property integer $milestoneID
 * @property string $description
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 * @property string|\Carbon\Carbon $dueDate
 */
class CMContractDeliverableAmd extends Model
{


    use HasFactory;

    public $table = 'cm_contract_deliverables_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'id',
        'contract_history_id',
        'uuid',
        'contractID',
        'milestoneID',
        'description',
        'companySystemID',
        'created_by',
        'updated_by',
        'dueDate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'amd_id' => 'integer',
        'contract_history_id' => 'integer',
        'uuid' => 'string',
        'contractID' => 'integer',
        'milestoneID' => 'integer',
        'description' => 'string',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'dueDate' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'contract_history_id' => 'nullable|integer',
        'uuid' => 'required|string|max:200',
        'contractID' => 'required|integer',
        'milestoneID' => 'nullable|integer',
        'description' => 'required|string|max:255',
        'companySystemID' => 'required|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'dueDate' => 'nullable'
    ];

    public function milestone()
    {
        return $this->belongsTo(CMContractMileStoneAmd::class, 'milestoneID', 'id');
    }

    public function getContractDeliverables($id)
    {
        return self::where('milestoneID',$id)
            ->first();
    }

    public function getDeliverableData($historyUuid,$id)
    {
        return self::where('uuid');
    }
}
