<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;

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
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_contract_milestone';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $hidden = ['id', 'contractID'];

    public $fillable = [
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

    public function milestonePaymentSchedules()
    {
        return $this->hasOne('App\Models\MilestonePaymentSchedules', 'milestone_id', 'id');
    }

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

    public static function getContractIdColumn()
    {
        return 'contractID';
    }

    public static function getCompanyIdColumn()
    {
        return 'companySystemID';
    }

    public static function getContractMilestone($contractID, $companySystemID)
    {
        return ContractMilestone::select('uuid', 'title', 'description', 'status')
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->get();

    }

    public static function getContractMilestoneWithAmount($milestoneUuid)
    {
        return ContractMilestone::with([
            'milestonePaymentSchedules' => function ($q)
            {
                $q->select('amount', 'id', 'uuid','milestone_id');
            }
        ])->where('uuid', $milestoneUuid)
          ->first();
    }

    public function checkMilestoneInPayment()
    {
        return $this->belongsTo(MilestonePaymentSchedules::class, 'id', 'milestone_id');
    }

    public function checkContractMilestoneExists($milestoneUuid)
    {
        return ContractMilestone::select('id')->where('uuid', $milestoneUuid)->first();
    }
    public static function checkContractHasMilestone($contractID)
    {
        return ContractMilestone::select('id')->where('contractID', $contractID)->exists();
    }

}
