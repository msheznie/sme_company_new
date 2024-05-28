<?php

namespace App\Models;

use App\Helpers\General;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    use SoftDeletes;

    use HasFactory;

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
        'timesReferred',
        'companySystemID',
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
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
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

        $query = ContractMaster::with(['contractTypes' => function ($q) {
            $q->select('contract_typeId', 'cm_type_name', 'uuid');
        }, 'counterParties' => function ($q1) {
            $q1->select('cmCounterParty_id', 'cmCounterParty_name');
        }, 'createdUser' => function ($q2) {
            $q2->select('employeeSystemID', 'empName');
        }, 'contractUsers' => function ($q3) {
            $q3->with(['contractSupplierUser','contractCustomerUser']);
        }, 'contractAssignedUsers' => function ($q4) use ($contractUserId) {
            $q4->select('contractId', 'userId')
                ->where('userId', $contractUserId->id)
                ->where('status', 1);
        }, 'contractAssignedUsers.contractUserGroupAssignedUser'])->where('companySystemID', $companyId)
            ->orderBy('id', 'desc');
        if ($filter) {
            if (isset($filter['counterPartyID'])) {
                $query->where('counterParty', $filter['counterPartyID']);
            }
            if (isset($filter['is_status'])) {
                $query->where('status', $filter['is_status']);
            }
            if (isset($filter['contractTypeID'])) {
                $query->where('contractType', $contractId['contract_typeId']);
            }
            if (isset($filter['counterPartyNameID'])) {
                $query->where('counterPartyName', $counterPartyID['id']);
            }
        }
        if ($search) {
            $search = str_replace("\\", "\\\\", $search);
            $query = $query->where(function ($query) use ($search) {
                $query->orWhere('contractCode', 'LIKE', "%{$search}%");
                $query->orWhere('title', 'LIKE', "%{$search}%");
                $query->orWhere('referenceCode', 'LIKE', "%{$search}%");
                $query->orWhereHas('contractTypes', function ($query1) use ($search) {
                    $query1->where('cm_type_name', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('counterParties', function ($query2) use ($search) {
                    $query2->where('cmCounterParty_name', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('contractUsers.contractSupplierUser', function ($query3) use ($search) {
                    $query3->where('supplierName', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('contractUsers.contractCustomerUser', function ($query4) use ($search) {
                        $query4->where('customerName', 'LIKE', "%{$search}%");
                });
            });
        }
        return $query;
    }


}
