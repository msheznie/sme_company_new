<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractMilestoneRetentionAmd
 * @package App\Models
 * @version October 1, 2024, 11:59 am +04
 *
 * @property integer $id
 * @property integer $contract_history_id
 * @property integer $level_no
 * @property string $uuid
 * @property integer $contractId
 * @property integer $milestoneId
 * @property number $retentionPercentage
 * @property number $retentionAmount
 * @property string $startDate
 * @property string $dueDate
 * @property string $withholdPeriod
 * @property boolean $paymentStatus
 * @property integer $companySystemId
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class ContractMilestoneRetentionAmd extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_milestone_retention_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'amd_id';
    protected $dates = ['deleted_at'];
    protected $hidden = ['amd_id', 'contract_history_id', 'contractId'];

    public $fillable = [
        'id',
        'contract_history_id',
        'level_no',
        'uuid',
        'contractId',
        'milestoneId',
        'retentionPercentage',
        'retentionAmount',
        'startDate',
        'dueDate',
        'withholdPeriod',
        'paymentStatus',
        'companySystemId',
        'created_by',
        'updated_by',
        'deleted_by'
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
        'level_no' => 'integer',
        'uuid' => 'string',
        'contractId' => 'integer',
        'milestoneId' => 'integer',
        'retentionPercentage' => 'float',
        'retentionAmount' => 'float',
        'startDate' => 'string',
        'dueDate' => 'string',
        'withholdPeriod' => 'string',
        'paymentStatus' => 'boolean',
        'companySystemId' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function milestone()
    {
        return $this->belongsTo(CMContractMileStoneAmd::class, 'milestoneId', 'id');
    }
    public static function getContractMilestoneRetentionAmd($contractHistoryId, $contractId, $companySystemID,
                                                            $fromApproval = false)
    {
        return self::with([
            'milestone' => function ($q)
            {
                $q->select('title', 'id', 'uuid')
                    ->with([
                        'milestonePaymentSchedules' => function ($q1)
                        {
                            $q1->select('amount', 'id', 'uuid','milestone_id');
                        }
                    ]);
            },
        ])->where('contract_history_id', $contractHistoryId)
            ->where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)
            ->when($fromApproval, function ($q)
            {
                $q->whereNotNull('id');
            })
            ->orderBy('id', 'asc');
    }
    public function getLevelNo($termId, $contractId)
    {
        $levelNo = self::where('id',$termId)
                ->where('contractId', $contractId)
                ->max('level_no') + 1;

        return  max(1, $levelNo);
    }
    public static function getContractMilestoneRetentionAmdData($contractHistoryID, $companyID)
    {
        return self::with([
            'milestone'=> function ($q)
            {
                $q->select('id', 'uuid')
                    ->with([
                        'milestonePaymentSchedules' => function ($q1)
                        {
                            $q1->select('amount', 'id', 'uuid','milestone_id');
                        }
                    ]);
            }
        ])
            ->where('contract_history_id', $contractHistoryID)
            ->where('companySystemId', $companyID)
            ->get();
    }
    public static function getMilestoneRetentionAmdForUpdate($milestoneRetentionUuid, $contractHistoryID)
    {
        return self::where([
            'uuid' => $milestoneRetentionUuid,
            'contract_history_id' => $contractHistoryID
        ])->first();
    }
    public static function updateMilestoneRetentionAmd($contractHistoryID, $companySystemID, $updateData)
    {
        return self::where('contract_history_id', $contractHistoryID)
            ->where('companySystemId', $companySystemID)
            ->update($updateData);
    }
    public static function checkForDuplicateMilestoneRetentionAmd($milestoneID, $contractHistoryID, $companySystemID)
    {
        return self::where('milestoneId', $milestoneID)
            ->where('contract_history_id', $contractHistoryID)
            ->where('companySystemId', $companySystemID)
            ->exists();
    }
    public static function getMilestoneRetentionAmdCount($contractHistoryID, $companySystemID)
    {
        return self::where('contract_history_id', $contractHistoryID)
            ->where('companySystemId', $companySystemID)
            ->count();
    }
    public static function checkUuidExists($uuid)
    {
        return self::where('uuid', $uuid)->exists();
    }
    public static function checkMilestoneRetentionFilled($contractHistoryID, $companySystemID)
    {
        return self::where('contract_history_id', $contractHistoryID)
            ->where('companySystemId', $companySystemID)
            ->where(function ($q)
            {
                $q->whereNull('milestoneId')
                    ->orWhereNull('retentionPercentage')
                    ->orWhereNull('startDate')
                    ->orWhereNull('dueDate')
                    ->orWhere('retentionPercentage', 0);
            })->exists();
    }
    public static function checkValidDate($contractHistoryID, $isStartDate, $date)
    {
        return self::where('contract_history_id', $contractHistoryID)
            ->when($isStartDate, function ($q) use ($date)
            {
                $q->whereDate('startDate', '<', $date);
            })
            ->when(!$isStartDate, function ($q) use ($date)
            {
                $q->whereDate('dueDate', '>', $date);
            })->exists();
    }
    public static function newContractMilestoneRetentionAmd($contractHistoryId)
    {
        return self::where('contract_history_id', $contractHistoryId)
            ->whereNull('id')
            ->get();
    }
    public static function checkMilestoneRetentionExists($historyID)
    {
        return self::where('contract_history_id', $historyID)->exists();
    }
}
