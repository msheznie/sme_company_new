<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;
/**
 * Class ContractHistory
 * @package App\Models
 * @version May 29, 2024, 2:49 pm +04
 *
 * @property integer $category
 * @property string $date
 * @property string $end_date
 * @property integer $contract_id
 * @property integer $company_id
 * @property string $contract_title
 * @property string $created_date
 * @property integer $created_by
 */
class ContractHistory extends Model
{
    use HasFactory;
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_contract_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'category',
        'date',
        'end_date',
        'uuid',
        'contract_id',
        'cloning_contract_id',
        'company_id',
        'created_by',
        'comment',
        'confirmed_yn',
        'confirmed_date',
        'confirm_by',
        'confirmed_comment',
        'rollLevelOrder',
        'refferedBackYN',
        'approved_yn',
        'approved_by',
        'approved_date',
        'timesReferred',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category' => 'integer',
        'uuid' => 'string',
        'date' => 'date',
        'end_date' => 'date',
        'contract_id' => 'integer',
        'cloning_contract_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'comment' => 'string',
        'confirmed_yn' => 'integer',
        'confirmed_date' => 'datetime',
        'confirm_by' => 'integer',
        'confirmed_comment' => 'string',
        'rollLevelOrder' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category' => 'required|integer',
        'date' => 'required',
        'end_date' => 'required',
        'contract_id' => 'required|integer',
        'company_id' => 'required|integer',
        'contract_title' => 'required|string|max:255',
        'created_by' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'comment' => 'nullable',
    ];

    public static function addendumData($contractId, $companyId,$contractCategory)
    {
        return self::select('uuid', 'contract_id', 'company_id', 'created_at', 'created_by','date','status')
            ->with(['employees' => function ($query)
            {
                $query->select('employeeSystemID', 'empName');
            } , 'contractMaster' => function ($query)
            {
                $query->select('id', 'contractCode','startDate','endDate','parent_id','uuid','confirmed_yn',
                'approved_yn','refferedBackYN','timesReferred','status', 'is_renewal', 'updated_at'
                );
            }])
            ->where('company_id', $companyId)
            ->where('category', $contractCategory)
            ->where('cloning_contract_id', $contractId)
            ->get();
    }

    public function employees()
    {
        return $this->hasOne(Employees::class, 'employeeSystemID', 'created_by');
    }

    public function contractMaster()
    {
        return $this->hasOne(ContractMaster::class, 'id', 'contract_id');
    }

    public function contractOldMaster()
    {
        return $this->belongsTo(ContractMaster::class, 'contract_id', 'id');
    }

    public function createdUser()
    {
        return $this->belongsTo(Employees::class, 'created_by', 'employeeSystemID');
    }

    public static function contractHistory
    (
        $contractId, $categoryId, $companySystemID,$contractColumnName = 'contract_id'
    )
    {
        return ContractHistory::with([
            'contractOldMaster' => function ($q)
            {
                $q->select('title', 'id', 'status', 'uuid');
            }, 'createdUser' => function ($q1)
            {
                $q1->select('employeeSystemID', 'empName');
            }
        ])->where($contractColumnName, $contractId)
            ->where('company_id', $companySystemID)
            ->where('category', $categoryId)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getExtendContractApprovals($isPending, $selectedCompanyID, $search, $employeeID, $documentSystemID)

    {
        $approvals = DB::table('erp_documentapproved')
            ->select(
                'erp_documentapproved.rollLevelOrder',
                'erp_documentapproved.documentApprovedID',
                'erp_documentapproved.documentSystemID',
                'approvalLevelID',
                'cm_contract_master.contractCode',
                'cm_contract_master.uuid As contract_uuid',
                'cm_contract_history.uuid',
                'cm_contract_history.date',
                'cm_contract_history.comment',
                'cm_contract_master.title',
                'cm_contract_master.contractAmount',
                'cm_contract_types.cm_type_name',
                'cm_counter_parties_master.cmCounterParty_name',
                'employees.empName As created_user',
                'cm_contract_history.created_at',
                'currencymaster.CurrencyCode',
                'currencymaster.DecimalPlaces',
                DB::raw('CASE
                    WHEN cm_counter_parties_master.cmCounterParty_name = "Supplier" THEN suppliermaster.supplierName
                    WHEN cm_counter_parties_master.cmCounterParty_name = "Customer" THEN customermaster.CustomerName
                    ELSE NULL
                 END as contractUserName')
            )
            ->join('cm_contract_history', function ($query) use ($selectedCompanyID, $isPending)
            {
                $query->on('erp_documentapproved.documentSystemCode', '=', 'cm_contract_history.id')
                    ->when($isPending == 1, function ($query1)
                    {
                        $query1->on('erp_documentapproved.rollLevelOrder', '=', 'cm_contract_history.rollLevelOrder');
                        $query1->where('cm_contract_history.approved_yn', 0)
                            ->where('cm_contract_history.confirmed_yn', 1);
                    })
                    ->when($isPending == 0, function ($query1)
                    {
                        $query1->where('cm_contract_history.approved_yn', 1)
                            ->where('cm_contract_history.confirmed_yn', 1);
                    })
                    ->where('cm_contract_history.company_id', $selectedCompanyID);
            })
            ->join('cm_contract_master',
                'cm_contract_history.contract_id', '=', 'cm_contract_master.id')
            ->join('cm_contract_types',
                'cm_contract_master.contractType', '=', 'cm_contract_types.contract_typeId')
            ->join('cm_counter_parties_master',
                'cm_contract_master.counterParty', '=', 'cm_counter_parties_master.cmCounterParty_id')
            ->join('cm_contract_users as first',
                'cm_contract_master.counterPartyName', '=', 'first.id')
            ->leftJoin('customermaster',
                'first.contractUserId', 'customermaster.customerCodeSystem')
            ->join('cm_contract_users as sec',
                'cm_contract_master.counterPartyName', '=', 'sec.id')
            ->leftJoin('suppliermaster',
                'sec.contractUserId', 'suppliermaster.supplierCodeSystem')
            ->join('employees', 'cm_contract_history.created_by', 'employees.employeeSystemID')
            ->join('companymaster',
                'cm_contract_master.companySystemID', '=', 'companymaster.companySystemID')
            ->join('currencymaster',
                'companymaster.localCurrencyID', '=', 'currencymaster.currencyID')
            ->where('erp_documentapproved.documentSystemID', $documentSystemID)
            ->where('erp_documentapproved.companySystemID', $selectedCompanyID);
        if ($isPending == 1)
        {
            $approvals = $approvals->where('erp_documentapproved.rejectedYN', 0)
                ->where('erp_documentapproved.approvedYN', 0)
                ->join('employeesdepartments', function ($query) use ($selectedCompanyID,
                    $employeeID,
                    $documentSystemID)
                {
                    $query->on('erp_documentapproved.approvalGroupID', '=', 'employeesdepartments.employeeGroupID')
                        ->on('erp_documentapproved.documentSystemID', '=', 'employeesdepartments.documentSystemID')
                        ->on('erp_documentapproved.companySystemID', '=', 'employeesdepartments.companySystemID')
                        ->where('employeesdepartments.documentSystemID', $documentSystemID)
                        ->where('employeesdepartments.companySystemID', $selectedCompanyID)
                        ->where('employeesdepartments.employeeSystemID', $employeeID)
                        ->where('employeesdepartments.isActive', 1)
                        ->where('employeesdepartments.removedYN', 0);
                });
        }
        else
        {
            $approvals = $approvals->where('erp_documentapproved.employeeSystemID', $employeeID)
                ->where('erp_documentapproved.approvedYN', -1);
        }
        $approvals = $approvals->orderBy('documentApprovedID', 'desc');
        if ($search)
        {
            $search = str_replace("\\", "\\\\", $search);
            $approvals = $approvals->where(function ($query) use ($search)
            {
                $query->where('cm_contract_master.title', 'LIKE', "%{$search}%")
                    ->orWhere('cm_contract_master.contractCode', 'LIKE', "%{$search}%");
            });
        }
        return $approvals;
    }

    public static function getContractIdColumn()
    {
        return 'contract_id';
    }

    public static function getCompanyIdColumn()
    {
        return 'company_id';
    }

    public static function getContractHistory($contractUuid, $companySystemID)
    {
        return ContractHistory::where('uuid', $contractUuid)
            ->where('company_id', $companySystemID)
            ->first();
    }
}
