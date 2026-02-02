<?php

namespace App\Models;

use App\Helpers\General;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractUsers
 * @package App\Models
 * @version March 6, 2024, 10:12 am +04
 *
 * @property integer $contractUserId
 * @property integer $isActive
 * @property integer $companySystemId
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractUsers extends Model
{
    public $table = 'cm_contract_users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = ['id', 'contractUserId'];
    protected $primaryKey = 'id';

    public $fillable = [
        'contractUserId',
        'contractUserType',
        'contractUserCode',
        'contractUserName',
        'uuid',
        'isActive',
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
        'contractUserId' => 'integer',
        'contractUserType' => 'integer',
        'contractUserCode' => 'string',
        'contractUserName' => 'string',
        'uuid' => 'string',
        'isActive' => 'integer',
        'companySystemId' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'contractUserId' => 'required|integer',
        'contractUserCode' => 'nullable|string',
        'contractUserName' => 'nullable|string',
        'uuid' => 'nullable|string|unique:cm_contract_users,uuid',
        'isActive' => 'required|integer',
        'companySystemId' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function contractInternalUser(){
        return $this->belongsTo(Employees::class, 'contractUserId', 'employeeSystemID');
    }
    public function contractSupplierUser()
    {
        return $this->belongsTo(SupplierMaster::class, 'contractUserId', 'supplierCodeSystem');
    }
    public function contractCustomerUser(){
        return $this->belongsTo(CustomerMaster::class, 'contractUserId', 'customerCodeSystem');
    }

    public function assignedContractUserGroup(){
        return $this->belongsTo(ContractUserGroupAssignedUser::class, 'id', 'contractUserId');
    }

    public function getContractUserList($search, $companySystemId, $filter)
    {
        $contractUserType = $filter['contractUserType'] ?? 0;

        $contractUsers = ContractUsers::select
        ('uuid', 'contractUserId', 'contractUserType', 'contractUserCode', 'contractUserName', 'isActive')
            ->where('companySystemId', $companySystemId)
            ->when($contractUserType > 0, function ($q) use ($contractUserType)
            {
                $q->where('contractUserType', $contractUserType);
            })
            ->with([
                'contractInternalUser' => function ($q)
                {
                    $q->with([
                        'employeeDepartments' => function ($q)
                        {
                            $q->with([
                                'department' => function ($q)
                                {
                                    $q->select('DepartmentMasterID', 'DepartmentDes');
                                }
                            ]);
                            $q->select('EmpID', 'DepartmentMasterID', 'isPrimary', 'isActive');
                            $q->where('isPrimary', 1);
                            $q->where('isActive', 1);
                        }
                    ]);
                    $q->select('employeeSystemID');
                }
            ])
            ->orderBy('id', 'desc');

        if($search)
        {
            $search = str_replace("\\", "\\\\", $search);
            $contractUsers = $contractUsers->where(function ($query) use ($search)
            {
                $query->where('contractUserCode', 'LIKE', "%{$search}%");
                $query->orWhere('contractUserName', 'LIKE', "%{$search}%");
            });
        }

        return $contractUsers;
    }

    public function getInternalUserList($companySystemId, $searchKeyword)
    {
        $employees = Employees::selectRaw('employeeSystemID as id, empName as name, empID as code')
            ->where('discharegedYN', 0)
            ->where('empActive', 1)
            ->where(function ($q) use ($companySystemId)
            {
                $q->whereDoesntHave('pulledContractUser', function ($q) use ($companySystemId) {
                    $q->where([
                        'companySystemId' => $companySystemId,
                        'contractUserType' => 3
                    ]);
                });
            })
            ->with([
                'employeeDepartment' => function ($q)
                {
                    $q->with([
                        'department' => function ($q)
                        {
                            $q->select('DepartmentMasterID', 'DepartmentDes');
                        }
                    ]);
                    $q->select('EmpID', 'DepartmentMasterID', 'isPrimary', 'isActive');
                    $q->where('isPrimary', 1);
                    $q->where('isActive', 1);
                }
            ])
            ->orderBy('id', 'desc');

        if ($searchKeyword)
        {
            $search = str_replace("\\", "\\\\", $searchKeyword);
            $employees = $employees->where(function ($query) use ($search)
            {
                $query->where('empName', 'LIKE', "%{$search}%");
                $query->orWhere('empID', 'LIKE', "%{$search}%");
            });
        }
        return $employees;
    }

    public function getSupplierUserList($companySystemId, $searchKeyword){
        $supplierMaster = SupplierMaster::selectRaw
        ('supplierCodeSystem as id, supplierName as name, primarySupplierCode as code')
            ->where('approvedYN', 1)
            ->where('isActive', 1)
            ->where(function ($q) use ($companySystemId){
                $q->whereHas('assignedSuppliers', function ($q) use ($companySystemId) {
                    $q->where([
                        'companySystemID'  => $companySystemId,
                        'isActive' => 1,
                        'isAssigned' => -1
                    ]);
                });
            })
            ->where(function ($q) use ($companySystemId) {
                $q->whereDoesntHave('pulledContractUser', function ($q) use ($companySystemId) {
                    $q->where('companySystemId', $companySystemId);
                    $q->where('contractUserType', 1);
                });
            })
            ->orderBy('id', 'desc');
        if ($searchKeyword) {
            $search = str_replace("\\", "\\\\", $searchKeyword);
            $supplierMaster = $supplierMaster->where(function ($query) use ($search) {
                $query->where('supplierName', 'LIKE', "%{$search}%");
                $query->orWhere('primarySupplierCode', 'LIKE', "%{$search}%");
            });
        }
        return $supplierMaster;
    }

    public function getCustomerUserList($companySystemId, $searchKeyword){
        $customerMaster = CustomerMaster::selectRaw
        ('customerCodeSystem as id, CustomerName as name, CutomerCode as code')
            ->where('primaryCompanySystemID', $companySystemId)
            ->where('isCustomerActive', 1)
            ->where('approvedYN', 1)
            ->where(function ($q) {
                $q->whereDoesntHave('pulledContractUser', function ($q) {
                    $q->where('contractUserType', 2);
                });
            })
            ->orderBy('id', 'desc');

        if ($searchKeyword) {
            $search = str_replace("\\", "\\\\", $searchKeyword);
            $customerMaster = $customerMaster->where(function ($query) use ($search) {
                $query->where('CustomerName', 'LIKE', "%{$search}%");
                $query->orWhere('CutomerCode', 'LIKE', "%{$search}%");
            });
        }
        return $customerMaster;
    }

    public function getUserData($uuid)
    {
        return self::where('uuid', $uuid)->first();
    }

    public static function getContractUserIdByUuid($uuid)
    {
        return self::where('uuid', $uuid)
            ->select('contractUserId')
            ->first();
    }
    public static function getContractUserIdById($id)
    {
        return self::where('id', $id)
            ->select('contractUserId', 'contractUserType')
            ->whereIn('contractUserType',[1,3])
            ->first();
    }

    public static function getUserId($currentEmployeeId)
    {
        return ContractUsers::select('id')
            ->where('contractUserId',$currentEmployeeId)
            ->where('contractUserType', 3)
            ->first();
    }

    public function getSupplierContactDetails($uuid)
    {
        return ContractUsers::with([
            'contractSupplierUser' => function ($q)
                {
                    $q->with([
                        'supplierContactDetails' => function ($q)
                        {
                         $q->select
                         ('supplierID', 'contactPersonName', 'contactPersonEmail', 'contactPersonFax', 'isDefault');
                         $q->orderBy('supplierContactID', 'desc');
                        }
                    ]);
                    $q->select('supplierCodeSystem');
                }
            ])
            ->select('uuid', 'contractUserId')
            ->where('uuid', $uuid)
            ->first();

    }

}
