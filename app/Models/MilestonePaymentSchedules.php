<?php

namespace App\Models;

use App\Utilities\ContractManagementUtils;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MilestonePaymentSchedules
 * @package App\Models
 * @version June 27, 2024, 9:13 am +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property integer $milestone_id
 * @property string $description
 * @property number $percentage
 * @property number $amount
 * @property string $payment_due_date
 * @property string $actual_payment_date
 * @property string $milestone_due_date
 * @property boolean $currency_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class MilestonePaymentSchedules extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_milestone_payment_schedules';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'contract_id',
        'milestone_id',
        'description',
        'percentage',
        'amount',
        'payment_due_date',
        'actual_payment_date',
        'milestone_due_date',
        'currency_id',
        'company_id',
        'created_by',
        'updated_by',
        'milestone_status'
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
        'milestone_id' => 'integer',
        'description' => 'string',
        'percentage' => 'float',
        'amount' => 'float',
        'payment_due_date' => 'string',
        'actual_payment_date' => 'string',
        'milestone_due_date' => 'string',
        'currency_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'milestone_status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function milestoneDetail()
    {
        return $this->belongsTo(ContractMilestone::class, 'milestone_id', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'currency_id', 'currencyID');
    }
    public function milestonePaymentSchedules($searchKeyword, $companyId, $contractUuid)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
        $contractID = $contract['id'] ?? 0;
        $paymentSchedules = MilestonePaymentSchedules::select('uuid', 'contract_id', 'milestone_id', 'description',
            'percentage', 'amount', 'payment_due_date', 'actual_payment_date', 'milestone_due_date', 'currency_id',
            'milestone_status')
            ->where('contract_id', $contractID)
            ->where('company_id', $companyId)
            ->with([
                'milestoneDetail' => function ($q)
                {
                    $q->select('id', 'uuid', 'title');
                },
                'currency' => function ($q)
                {
                    $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                }
            ]);
        if ($searchKeyword)
        {
            $searchKeyword = str_replace("\\", "\\\\", $searchKeyword);
            $paymentSchedules = $paymentSchedules->where(function ($query) use ($searchKeyword)
            {
                $query->orWhere('description', 'LIKE', "%{$searchKeyword}%");
                $query->orWhereHas('milestoneDetail', function ($query2) use ($searchKeyword)
                {
                    $query2->where('title', 'LIKE', "%{$searchKeyword}%");
                });
            });
        }
        return $paymentSchedules;
    }

    public static function existMilestonePayment($contractId, $companySystemID)
    {
        return MilestonePaymentSchedules::where('contract_id', $contractId)
            ->where('company_id', $companySystemID)
            ->first();
    }

    public static function getTotalAmount($contractID)
    {
        return MilestonePaymentSchedules::where('contract_id', $contractID)->sum('amount');
    }
    public static function getTotalPercentage($contractID)
    {
        return MilestonePaymentSchedules::where('contract_id', $contractID)->sum('percentage');
    }
    public static function checkMilestoneUsedInRetention($milestoneID)
    {
        return ContractMilestoneRetention::where('milestoneId', $milestoneID)->exists();
    }
}
