<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

/**
 * Class ContractUserGroup
 * @package App\Models
 * @version May 7, 2024, 10:59 am +04
 *
 * @property string $uuid
 * @property string $group_name
 * @property string $status
 */
class ContractUserGroup extends Model
{
    use HasFactory;

    public $table = 'cm_contract_user_group';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id'];



    public $fillable = [
        'uuid',
        'groupName',
        'status',
        'isDefault',
        'companySystemID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'groupName' => 'string',
        'companySystemID' => 'integer',
        'status' => 'integer',
        'isDefault' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function assignedUsers(){
        return $this->hasMany(ContractUserGroupAssignedUser::class, 'userGroupId', 'id');
    }

    public function getContractUserGroupList($companySystemId)
    {
        return ContractUserGroup::select('uuid', 'groupName', 'status', 'isDefault')
            ->where('companySystemID', $companySystemId)->orderBy('id', 'desc');
    }

    public function getContractUserGroupAssignedUsers($companySystemId, $selectUserGroup)
    {
        $result = ContractUserGroup::select('id')->where('uuid', $selectUserGroup)->first();
        $userGroupId = 0;
        if(isset($result))
        {
            $userGroupId = $result->id;
        }
       return ContractUserGroupAssignedUser::select('uuid', 'contractUserId', 'status', 'created_at', 'created_by')
           ->with([
               'assignedUser' => function ($q)
               {
                   $q->with([
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
                   ]);
               }, 'employee'
           ])
            ->where('userGroupId', $userGroupId)
            ->where('companySystemID', $companySystemId)
           ->orderBy('id', 'desc');
    }

    public function getContractUserListForUserGroup($companySystemId, $groupId, $isActive)
    {
        $query = ContractUsers::select('uuid', 'contractUserId', 'contractUserName as itemName')
            ->whereDoesntHave('assignedContractUserGroup', function ($query) use ($groupId)
            {
                $query->where('userGroupId', $groupId);
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
            ->where('companySystemId', $companySystemId);

        if ($isActive == 1)
        {
            $query->where('isActive', 1);
        }

        return $query;
    }

    public function getContractUserListToAssign($companySystemId, $contractId)
    {

        $userList =  ContractUsers::select('uuid', 'contractUserId', 'contractUserName as itemName')
            ->whereDoesntHave('assignedContractUserGroup')
            ->where('companySystemId', $companySystemId)
        ->get();

        $contractUserAssignedResult = ContractUserAssign::where('contractId', $contractId)
            ->where('userGroupId', 0)
            ->where('status', 1)
            ->pluck('userId')->toArray();

        $selectedUserList =  ContractUsers::select('uuid', 'contractUserId','contractUserName as itemName')
            ->whereIn('id', $contractUserAssignedResult)
            ->get();

        return [
            'userList' => $userList,
            'userSelected' => $selectedUserList
        ];
    }

    public function contractUserGroupList($companySystemId,$contractuuid)
    {
        $userGroup =  ContractUserGroup::selectRaw('uuid, groupName AS itemName')
            ->where('companySystemID', $companySystemId)
            ->where('status', 1)
            ->get();
        $contractResult = ContractMaster::select('id')->where('uuid', $contractuuid)->first();
        $contractUserAssignedResult = ContractUserAssign::where('contractId', $contractResult->id)
            ->where('status', 1)
            ->pluck('userGroupId')->toArray();
        $userGroupSelected =  ContractUserGroup::selectRaw('uuid, groupName AS itemName')
            ->where('companySystemID', $companySystemId)
            ->whereIn('id', $contractUserAssignedResult)
            ->get();
        return [
            'userGroup' => $userGroup,
            'userGroupSelected' => $userGroupSelected
        ];
    }

    public static function getActiveDefaultUserGroups($companySystemId)
    {
        return ContractUserGroup::select('id')
            ->where('isDefault', 1)
            ->where('status',1)
            ->where('companySystemID', $companySystemId)
            ->get();
    }
}
