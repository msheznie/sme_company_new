<?php

namespace App\Models;

use App\Utilities\ContractManagementUtils;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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
    public function contractMaster()
    {
        return $this->belongsTo(ContractMaster::class, 'contract_id', 'id');
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

    public static function getTotalAmount($contractID, $isEdit, $uuid)
    {
        return MilestonePaymentSchedules::where('contract_id', $contractID)
            ->when($isEdit == 1, function ($q) use ($uuid)
            {
                $q->where('uuid', '!=', $uuid);
            })
            ->sum('amount');
    }
    public static function getTotalPercentage($contractID, $isEdit, $uuid)
    {
        return MilestonePaymentSchedules::where('contract_id', $contractID)
            ->when($isEdit == 1, function ($q) use ($uuid)
            {
                $q->where('uuid', '!=', $uuid);
            })
            ->sum('percentage');
    }
    public static function checkMilestoneUsedInRetention($milestoneID)
    {
        return ContractMilestoneRetention::where('milestoneId', $milestoneID)->exists();
    }

    public function milestonePaymentSchedulesReport($search, $companyId, $filter)
    {
        $contractTypeUuid = $filter['contractTypeID'] ?? null;
        $milestoneStatus = $filter['milestoneStatus'] ?? 3;
        $contractType = CMContractTypes::select('contract_typeId')
            ->where('uuid',$contractTypeUuid)
            ->where('companySystemID', $companyId)
            ->first();
        $contractTypeID = $contractType['contract_typeId'] ?? 0;

        $results =  MilestonePaymentSchedules::select(
            'id',
            'uuid',
            'contract_id',
            'milestone_id',
            'description',
            'percentage',
            'amount',
            'milestone_status'
        )
        ->with([
            'contractMaster' => function ($query) use($contractTypeID)
            {
                $query->select('id', 'contractCode', 'title', 'contractAmount', 'counterParty', 'counterPartyName',
                    'contractType'
                )
                ->with([
                    'contractUsers' => function ($query)
                    {
                        $query->select('id', 'uuid', 'contractUserId', 'contractUserType', 'contractUserName')
                        ->with([
                            'contractSupplierUser' => function ($q)
                            {
                                $q->select('supplierCodeSystem', 'supplierName');
                            },
                            'contractCustomerUser' => function ($q)
                            {
                                $q->select('customerCodeSystem', 'CustomerName');
                            }
                        ]);
                    }, 'contractTypes' => function ($q)
                    {
                        $q->select('contract_typeId', 'cm_type_name', 'uuid');
                    }
                ])
                ->when($contractTypeID > 0, function ($q) use ($contractTypeID)
                {
                    $q->where('contractType', $contractTypeID);
                })
                ->where('approved_yn', 1);
            }, 'milestoneDetail' => function ($query)
            {
                $query->select('id', 'uuid', 'title', 'due_date');
            }
        ])
        ->where(function ($query) use($contractTypeID)
        {
            $query->whereHas('contractMaster', function ($q) use ($contractTypeID)
            {
                $q->where('approved_yn', 1);
                $q->when($contractTypeID > 0, function ($q) use ($contractTypeID)
                {
                    $q->where('contractType', $contractTypeID);
                    $q->whereHas('contractTypes');
                });
            });
        })
        ->when($milestoneStatus != 3, function ($q) use($milestoneStatus)
        {
            $q->where('milestone_status', $milestoneStatus);
        });

        if ($search)
        {
            $search = str_replace("\\", "\\\\", $search);
            $results = $results->where(function ($results) use ($search)
            {
                $results->orWhere('amount', 'LIKE', "%{$search}%");
                $results->orWhereHas('contractMaster', function ($query1) use ($search)
                {
                    $query1->where('contractCode', 'LIKE', "%{$search}%");
                    $query1->orWhere('title', 'LIKE', "%{$search}%");
                    $query1->orWhereHas('contractUsers.contractSupplierUser', function ($query3) use ($search)
                    {
                        $query3->where('supplierName', 'LIKE', "%{$search}%");
                    });
                    $query1->orWhereHas('contractUsers.contractCustomerUser', function ($query3) use ($search)
                    {
                        $query3->where('CustomerName', 'LIKE', "%{$search}%");
                    });
                });
                $results->orWhereHas('contractMaster.contractTypes', function ($query1) use ($search)
                {
                    $query1->where('cm_type_name', 'LIKE', "%{$search}%");
                });
                $results->orWhereHas('milestoneDetail', function ($query1) use ($search)
                {
                    $query1->where('title', 'LIKE', "%{$search}%");
                });

            });
        }
        return $results->orderBy('contract_id', 'desc');
    }

    public static function getContractMilestone($companyId, $filter)
    {
        $query = MilestonePaymentSchedules::select('uuid', 'contract_id', 'milestone_id', 'description',
            'percentage', 'amount', 'payment_due_date', 'actual_payment_date', 'milestone_due_date', 'currency_id',
            'milestone_status')
            ->where('company_id', $companyId)
            ->with([
                'milestoneDetail' => function ($q)
                {
                    $q->select('id', 'uuid', 'title');
                },
                'contractMaster' => function ($q1)
                {
                    $q1->select('id', 'contractCode', 'title');
                }
            ])
            ->orderBy('id', 'desc');

        if ($filter)
        {
            if (isset($filter['month']))
            {
                $query->whereMonth('payment_due_date', $filter['month']);
            }

            if (isset($filter['year']))
            {
                $query->whereYear('payment_due_date', $filter['year']);
            }
        }
        return $query;
    }

    public static function checkContractHasMilestone($contractID, $companyId)
    {
        return MilestonePaymentSchedules::select('id')
            ->where('contract_id', $contractID)
            ->where('company_id', $companyId)
            ->exists();
    }

    public static function getMilestonePaymentSchedule($uuid)
    {
        return MilestonePaymentSchedules::select('milestone_id')
            ->where('uuid', $uuid)
            ->first();
    }
}
