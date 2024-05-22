<?php

namespace App\Models;

use App\Helper\Helper;
use App\Helpers\General;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_milestone_retention';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'contractId'];



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
            'milestone' => function ($q) {
                $q->select('title', 'id', 'amount', 'uuid');
            }
        ])->where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)
            ->orderBy('id', 'desc');
    }


    public function setStartDateAttribute($value){
        $this->attributes['startDate'] = General::convertDateTime($value);
    }
}
