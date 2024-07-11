<?php

namespace App\Models;

use App\Helpers\General;
use App\Services\ContractHistoryService;
use App\Traits\HasCompanyIdColumn;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Awobaz\Compoships\Compoships;
use App\Traits\HasContractIdColumn;
/**
 * Class ContractMaster
 * @package App\Models
 * @version March 7, 2024, 2:16 pm +04
 *
 * @property string $contractCode
 * @property string $title
 * @property integer $contractType
 * @property integer $counterParty
 * @property integer $counterPartyName
 * @property string $referenceCode
 * @property string|\Carbon\Carbon $startDate
 * @property string|\Carbon\Carbon $endDate
 * @property integer $status
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractMaster extends Model
{
    use HasFactory;
    use Compoships;
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_contract_master';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'contractType', 'counterPartyName', 'created_by'];



    public $fillable = [
        'uuid',
        'contractCode',
        'documentMasterId',
        'title',
        'description',
        'contractType',
        'counterParty',
        'counterPartyName',
        'referenceCode',
        'contractOwner',
        'contractAmount',
        'startDate',
        'endDate',
        'agreementSignDate',
        'notifyDays',
        'contractTermPeriod',
        'contractRenewalDate',
        'contractExtensionDate',
        'contractTerminateDate',
        'contractRevisionDate',
        'primaryCounterParty',
        'primaryEmail',
        'primaryPhoneNumber',
        'secondaryCounterParty',
        'secondaryEmail',
        'secondaryPhoneNumber',
        'status',
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
        'companySystemID',
        'created_by',
        'updated_by',
        'is_amendment',
        'is_addendum',
        'is_renewal',
        'is_extension',
        'is_revision',
        'is_termination',
        'parent_id',
        'tender_id',
        'contract_history_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'contractCode' => 'string',
        'documentMasterId' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'contractType' => 'integer',
        'counterParty' => 'integer',
        'counterPartyName' => 'integer',
        'referenceCode' => 'string',
        'contractOwner' => 'integer',
        'contractAmount' => 'double',
        'startDate' => 'string',
        'endDate' => 'string',
        'agreementSignDate' => 'string',
        'notifyDays' => 'integer',
        'contractTermPeriod' => 'string',
        'contractRenewalDate' => 'string',
        'contractExtensionDate' => 'string',
        'contractTerminateDate' => 'string',
        'contractRevisionDate' => 'string',
        'primaryCounterParty' => 'string',
        'primaryEmail' => 'string',
        'primaryPhoneNumber' => 'string',
        'secondaryCounterParty' => 'string',
        'secondaryEmail' => 'string',
        'secondaryPhoneNumber' => 'string',
        'status' => 'integer',
        'confirmed_yn' => 'integer',
        'confirmed_date' => 'datetime',
        'confirm_by' => 'integer',
        'confirmed_comment' => 'string',
        'rollLevelOrder' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'approved_yn' => 'integer',
        'approved_by' => 'integer',
        'approved_date' => 'datetime',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'is_amendment' => 'integer',
        'is_addendum' => 'integer',
        'is_renewal' => 'integer',
        'is_extension' => 'integer',
        'is_revision' => 'integer',
        'is_termination' => 'integer',
        'parent_id' => 'integer',
        'tender_id' => 'integer',
        'contract_history_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function contractTypes()
    {
        return $this->belongsTo(CMContractTypes::class, 'contractType', 'contract_typeId');
    }
    public function counterParties()
    {
        return $this->belongsTo(CMCounterPartiesMaster::class, 'counterParty', 'cmCounterParty_id');
    }

    public function createdUser()
    {
        return $this->belongsTo(Employees::class, 'created_by', 'employeeSystemID');
    }

    public function contractUsers()
    {
        return $this->belongsTo(ContractUsers::class, 'counterPartyName', 'id');
    }

    public function contractOwners()
    {
        return $this->belongsTo(ContractUsers::class, 'contractOwner', 'id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Employees::class, 'confirm_by', 'employeeSystemID');
    }

    public function contractAssignedUsers()
    {
        return $this->hasMany(ContractUserAssign::class, 'contractId', 'id');
    }
    public function erpDocumentApproved()
    {
        return $this->hasMany(ErpDocumentApproved::class, 'documentSystemCode', 'id');
    }

    public function contractMaster($search, $companyId, $filter)
    {
        $contractTypeID = $filter['contractTypeID'] ?? 0;
        $counterPartyNameID = $filter['counterPartyNameID'] ?? 0;
        $currentEmployeeId = General::currentEmployeeId();

        $contractId = CMContractTypes::select('contract_typeId')
            ->where('uuid',$contractTypeID)
            ->where('companySystemID', $companyId)
            ->first();

        $counterPartyID = ContractUsers::select('id')
            ->where('uuid',$counterPartyNameID)
            ->where('companySystemId', $companyId)
            ->first();


        $contractUserId = ContractUsers::select('id')
            ->where('contractUserId',$currentEmployeeId)
            ->first();

        $query = ContractMaster::with(['contractTypes' => function ($q)
        {
            $q->select('contract_typeId', 'cm_type_name', 'uuid');
        }, 'counterParties' => function ($q1)
        {
            $q1->select('cmCounterParty_id', 'cmCounterParty_name');
        }, 'createdUser' => function ($q2)
        {
            $q2->select('employeeSystemID', 'empName');
        }, 'contractUsers' => function ($q3)
        {
            $q3->with(['contractSupplierUser','contractCustomerUser']);
        }, 'contractAssignedUsers' => function ($q4) use ($contractUserId)
        {
            $q4->select('contractId', 'userId')
                ->where('userId', $contractUserId->id)
                ->where('status', 1);
        },'contractHistoryStatus' => function ($q5) use ($companyId)
            {
                $q5->select(DB::raw('MIN(id) as id'), 'contract_id', 'status')
                    ->where('company_id', $companyId)
                    ->groupBy('contract_id', 'status')
                    ->orderBy('id', 'asc');

            },'contractAssignedUsers.contractUserGroupAssignedUser'
        ])->where('companySystemID', $companyId)
            ->orderBy('id', 'desc');
        if ($filter)
        {
            if (isset($filter['counterPartyID']))
            {
                $query->where('counterParty', $filter['counterPartyID']);
            }
            if (isset($filter['is_status']))
            {
                $query->where('status', $filter['is_status']);
            }
            if (isset($filter['contractTypeID']))
            {
                $query->where('contractType', $contractId['contract_typeId']);
            }
            if (isset($filter['counterPartyNameID']))
            {
                $query->where('counterPartyName', $counterPartyID['id']);
            }
        }
        if ($search)
        {
            $search = str_replace("\\", "\\\\", $search);
            $query = $query->where(function ($query) use ($search)
            {
                $query->orWhere('contractCode', 'LIKE', "%{$search}%");
                $query->orWhere('title', 'LIKE', "%{$search}%");
                $query->orWhere('referenceCode', 'LIKE', "%{$search}%");
                $query->orWhereHas('contractTypes', function ($query1) use ($search)
                {
                    $query1->where('cm_type_name', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('counterParties', function ($query2) use ($search)
                {
                    $query2->where('cmCounterParty_name', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('contractUsers.contractSupplierUser', function ($query3) use ($search)
                {
                    $query3->where('supplierName', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('contractUsers.contractCustomerUser', function ($query4) use ($search)
                {
                        $query4->where('customerName', 'LIKE', "%{$search}%");
                });
            });
        }
        return $query;
    }

    public function getMaxContractId()
    {
        return  (self::max('id') ?? 0) + 1;
    }

    public function getContractApprovals($isPending, $selectedCompanyID, $search, $employeeID)
    {
        $approvals = DB::table('erp_documentapproved')
            ->select(
                'erp_documentapproved.rollLevelOrder',
                'erp_documentapproved.documentApprovedID',
                'erp_documentapproved.documentSystemID',
                'approvalLevelID',
                'cm_contract_master.contractCode',
                'cm_contract_master.uuid',
                'cm_contract_master.title',
                'cm_contract_master.contractAmount',
                'cm_contract_types.cm_type_name',
                'cm_counter_parties_master.cmCounterParty_name',
                'employees.empName As created_user',
                'cm_contract_master.created_at',
                'currencymaster.CurrencyCode',
                'currencymaster.DecimalPlaces',
                DB::raw('CASE
                    WHEN cm_counter_parties_master.cmCounterParty_name = "Supplier" THEN suppliermaster.supplierName
                    WHEN cm_counter_parties_master.cmCounterParty_name = "Customer" THEN customermaster.CustomerName
                    ELSE NULL
                 END as contractUserName')
            )
            ->join('cm_contract_master', function ($query) use ($selectedCompanyID, $isPending)
            {
                $query->on('erp_documentapproved.documentSystemCode', '=', 'cm_contract_master.id')
                ->when($isPending == 1, function ($query1)
                {
                    $query1->on('erp_documentapproved.rollLevelOrder', '=', 'cm_contract_master.rollLevelOrder');
                    $query1->where('cm_contract_master.approved_yn', 0)
                        ->where('cm_contract_master.confirmed_yn', 1);
                })
                ->when($isPending == 0, function ($query1)
                {
                    $query1->where('cm_contract_master.approved_yn', 1)
                        ->where('cm_contract_master.confirmed_yn', 1);
                })
                ->where('cm_contract_master.companySystemID', $selectedCompanyID);
            })
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
            ->join('employees', 'cm_contract_master.created_by', 'employees.employeeSystemID')
            ->join('companymaster',
                'cm_contract_master.companySystemID', '=', 'companymaster.companySystemID')
            ->join('currencymaster',
                'companymaster.localCurrencyID', '=', 'currencymaster.currencyID')
            ->where('erp_documentapproved.documentSystemID', 123)
            ->where('erp_documentapproved.companySystemID', $selectedCompanyID);
        if ($isPending == 1)
        {
            $approvals = $approvals->where('erp_documentapproved.rejectedYN', 0)
                ->where('erp_documentapproved.approvedYN', 0)
                ->join('employeesdepartments', function ($query) use ($selectedCompanyID, $employeeID)
                {
                    $query->on('erp_documentapproved.approvalGroupID', '=', 'employeesdepartments.employeeGroupID')
                        ->on('erp_documentapproved.documentSystemID', '=', 'employeesdepartments.documentSystemID')
                        ->on('erp_documentapproved.companySystemID', '=', 'employeesdepartments.companySystemID')
                        ->where('employeesdepartments.documentSystemID', 123)
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

    public function contractMasterHistory()
    {
        return $this->hasOne(ContractMaster::class, 'id', 'parent_id');
    }
    public static function emailValidation($id, $companySystemID, $email, $type)
    {
        return ContractMaster::where('id', '!=' ,$id)
            ->when($type == 'primary', function ($q) use ($email)
            {
                $q->where('primaryEmail', $email)
                    ->orWhere('secondaryEmail', $email);
            })
            ->when($type == 'secondary', function ($q) use ($email)
            {
                $q->where('secondaryEmail', $email)
                    ->orWhere('primaryEmail', $email);
            })
            ->where('companySystemID', $companySystemID)
            ->exists();
    }

    public static function getContractIdColumn()
    {
        return 'id';
    }

    public static function getCompanyIdColumn()
    {
        return 'companySystemID';
    }
    public static function getCurrentInactiveContract($todayDate)
    {
        return ContractMaster::select('id', 'companySystemID')
            ->whereDate('startDate', $todayDate)
            ->where('status', 0)
            ->get();
    }
    public function contractHistoryStatus()
    {
        return $this->hasMany(contractStatusHistory::class, 'contract_id', 'id');
    }

    public function contractScenarioAssigns()
    {
        return $this->hasMany(CMContractScenarioAssign::class, 'contract_id', 'id');
    }

    public function contractUserAssigns()
    {
        return $this->hasMany(ContractUserAssign::class, 'contractId', 'id');
    }

    public function contractMilestonePaymentSchedule()
    {
        return $this->hasMany(MilestonePaymentSchedules::class, 'contract_id', 'id');
    }
    public function contractRetention()
    {
        return $this->hasMany(ContractMilestoneRetention::class, 'contractId', 'id');
    }

    public static function updatedAddendumRecords($companyId, $contractId, $data)
    {
        return ContractMaster::where('companySystemID', $companyId)
            ->where('parent_id', $contractId)
            ->where('is_addendum', 1)
            ->update($data);
    }

    public static function getAddendumRecordsId($companyId, $contractId)
    {
        return ContractMaster::select('id')
            ->where('companySystemID', $companyId)
            ->where('parent_id', $contractId)
            ->where('is_addendum', 1)
            ->get();
    }

    public static function getExistingContractType($companyId, $contractId)
    {
        return ContractMaster::select('contractType')
            ->where('companySystemID', $companyId)
            ->where('id', $contractId)
            ->first();
    }
}
