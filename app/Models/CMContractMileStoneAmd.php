<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractMileStoneAmd
 * @package App\Models
 * @version July 3, 2024, 6:01 am +04
 *
 * @property integer $id
 * @property integer $contract_history_id
 * @property string $uuid
 * @property integer $contractID
 * @property string $title
 * @property boolean $status
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $description
 */
class CMContractMileStoneAmd extends Model
{

    use HasFactory;

    public $table = 'cm_contract_milestone_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';






    public $fillable = [
        'id',
        'contract_history_id',
        'uuid',
        'contractID',
        'title',
        'status',
        'companySystemID',
        'created_by',
        'updated_by',
        'description'
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
        'title' => 'string',
        'status' => 'integer',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function getMilestoneAmd($historyId,$companyId)
    {
        return self::select('uuid', 'title', 'description', 'status','amd_id')
        ->where('contract_history_id', $historyId)
        ->where('companySystemID', $companyId)
        ->get();
    }

    public static function getContractMilestone($uuid)
    {
        return self::select('uuid', 'title','id')
        ->where('uuid', $uuid)
        ->first();
    }

    public function milestonePaymentSchedules()
    {
        return $this->hasOne('App\Models\MilestonePaymentSchedules', 'milestone_id', 'id');
    }
    public static function getMilestoneAmountAmd($contractHistoryID)
    {
        return self::with('milestonePaymentSchedules')
            ->where('contract_history_id', $contractHistoryID)
            ->has('milestonePaymentSchedules')
            ->get();
    }
}
