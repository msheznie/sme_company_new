<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractMilestonePenaltyDetail
 * @package App\Models
 * @version July 28, 2024, 10:45 am +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property integer $milestone_penalty_master_id
 * @property integer $milestone_title
 * @property number $milestone_amount
 * @property number $penalty_percentage
 * @property number $penalty_amount
 * @property string|\Carbon\Carbon $penalty_start_date
 * @property boolean $penalty_frequency
 * @property integer $due_in
 * @property number $due_penalty_amount
 * @property integer $status
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractMilestonePenaltyDetail extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_milestone_penalty_detail';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'contract_id',
        'milestone_penalty_master_id',
        'milestone_title',
        'milestone_amount',
        'penalty_percentage',
        'penalty_amount',
        'penalty_start_date',
        'penalty_frequency',
        'due_in',
        'due_penalty_amount',
        'status',
        'company_id',
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
        'contract_id' => 'integer',
        'milestone_penalty_master_id' => 'integer',
        'milestone_title' => 'integer',
        'milestone_amount' => 'float',
        'penalty_percentage' => 'float',
        'penalty_amount' => 'float',
        'penalty_start_date' => 'string',
        'penalty_frequency' => 'integer',
        'due_in' => 'integer',
        'due_penalty_amount' => 'float',
        'status' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uuid' => 'required|string|max:255',
        'contract_id' => 'required|integer',
        'milestone_penalty_master_id' => 'required|integer',
        'milestone_title' => 'nullable|integer',
        'milestone_amount' => 'nullable|numeric',
        'penalty_percentage' => 'nullable|numeric',
        'penalty_amount' => 'nullable|numeric',
        'penalty_start_date' => 'nullable',
        'penalty_frequency' => 'nullable|integer',
        'due_in' => 'nullable|integer',
        'due_penalty_amount' => 'nullable|numeric',
        'status' => 'nullable|integer',
        'company_id' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function milestone()
    {
        return $this->belongsTo(ContractMilestone::class, 'milestone_title', 'id');
    }

    public function billingFrequencies()
    {
        return $this->belongsTo(BillingFrequencies::class, 'penalty_frequency', 'id');
    }

    public static function getRecordsWithMilestone($contractId, $companySystemID)
    {
        return ContractMilestonePenaltyDetail::where('contract_id', $contractId)
            ->where('company_id', $companySystemID)
            ->get();
    }

    public function ContractMilestonePenaltyDetail($contractId, $companySystemID, $milestonePenaltyMasterId, $search)
    {
        $query = ContractMilestonePenaltyDetail::with([
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
            'billingFrequencies' => function ($q)
            {
                $q->select('description', 'id',);
            },
        ])->where('contract_id', $contractId)
            ->where('milestone_penalty_master_id', $milestonePenaltyMasterId)
            ->where('company_id', $companySystemID)
            ->orderBy('id', 'asc');

        if ($search)
        {
            $search = str_replace("\\", "\\\\", $search);
            $query = $query->where(function ($query) use ($search)
            {
                $query->orWhereHas('milestone', function ($query1) use ($search)
                {
                    $query1->where('title', 'LIKE', "%{$search}%");
                });
            });
        }

        return $query;
    }

    public static function getMilestoneTitle($milestoneId, $contractId, $companyId)
    {
        return ContractMilestonePenaltyDetail::where('milestone_title', $milestoneId)
            ->where('contract_id', $contractId)
            ->where('company_id', $companyId)
            ->first();
    }


}
