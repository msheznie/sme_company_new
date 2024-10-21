<?php

namespace App\Models;

use App\Helper\Helper;
use App\Helpers\General;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;

/**
 * Class ContractMilestoneRetention
 * @package App\Models
 * @version April 29, 2024, 11:28 pm +04
 *
 * @property string $uuid
 * @property integer $contractId
 * @property integer $milestoneId
 * @property number $retentionPercentage
 * @property number $retentionAmount
 * @property string|\Carbon\Carbon $startDate
 * @property string|\Carbon\Carbon $dueDate
 * @property string $withholdPeriod
 * @property boolean $paymentStatus
 * @property integer $companySystemId
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractMilestoneRetention extends Model
{
    use HasFactory;
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_milestone_retention';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = ['id'];



    public $fillable = [
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
        'contractId' => 'integer',
        'milestoneId' => 'integer',
        'retentionPercentage' => 'float',
        'retentionAmount' => 'float',
        'withholdPeriod' => 'string',
        'paymentStatus' => 'boolean',
        'companySystemId' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function contract()
    {
        return $this->belongsTo(ContractMaster::class, 'contractId', 'id');
    }

    public function milestone()
    {
        return $this->belongsTo(ContractMilestone::class, 'milestoneId', 'id');
    }

    public function ContractMilestoneRetention($companySystemID, $contractId)
    {
        return ContractMilestoneRetention::with([
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
        ])->where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)
            ->orderBy('id', 'asc');
    }


    public function setStartDateAttribute($value)
    {
        $this->attributes['startDate'] = General::convertDateTime($value);
    }

    public static function getContractIdColumn()
    {
        return 'contractId';
    }

    public static function getCompanyIdColumn()
    {
        return 'companySystemId';
    }

    public static function checkRetentionAddedForContract($contractId, $companySystemID)
    {
        return ContractMilestoneRetention::where('contractID', $contractId)
            ->where('companySystemID', $companySystemID)
            ->exists();
    }
    public static function checkRetentionExistWithMilestone($contractId, $companySystemID)
    {
        return ContractMilestoneRetention::where('contractID', $contractId)
            ->where('companySystemID', $companySystemID)
            ->whereNotNull('milestoneId')
            ->exists();
    }
    public static function getMilestoneRetention($contractID)
    {
        return self::where('contractID', $contractID)->get();
    }
    public static function getContractMilestoneRetentionData($contractID, $companySystemID)
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
            ->where('contractId', $contractID)
            ->where('companySystemId', $companySystemID)
            ->get();
    }
    public static function getMilestoneRetentionForUpdate($milestoneRetentionUuid)
    {
        return self::where('uuid', $milestoneRetentionUuid)->first();
    }
    public static function updateMilestoneRetention($contractID, $companySystemID, $updateData)
    {
        return self::where('contractId', $contractID)
            ->where('companySystemId', $companySystemID)
            ->update($updateData);
    }
    public static function checkForDuplicateMilestoneRetention($milestoneID, $contractID, $companySystemID)
    {
        return self::where('milestoneId', $milestoneID)
            ->where('contractId', $contractID)
            ->where('companySystemId', $companySystemID)
            ->exists();
    }
    public static function getContractMilestoneRetentionCount($contractID, $companySystemID)
    {
        return self::where('contractId', $contractID)
            ->where('companySystemId', $companySystemID)
            ->count();
    }
    public static function checkUuidExists($uuid)
    {
        return self::where('uuid', $uuid)->exists();
    }
    public static function deleteMilestoneRetention($amdRecordIds, $contractId)
    {
        self::whereNotIn('id', $amdRecordIds)
            ->where('contractId',$contractId)
            ->delete();
    }
}
