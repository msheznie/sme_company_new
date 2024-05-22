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
            ->where('companySystemId', $companySystemId)->orderBy('id', 'desc');
    }

    public function getContractUserGroupAssignedUsers($companySystemId, $selectUserGroup)
    {
        $result = ContractUserGroup::select('id')->where('uuid', $selectUserGroup)->first();
        $userGroupId = 0;
        if(isset($result)){
            $userGroupId = $result->id;
        }
       return ContractUserGroupAssignedUser::select('uuid', 'contractUserId', 'status', 'created_at', 'created_by')
           ->with(['assignedUser' ,'employee'])
            ->where('userGroupId', $userGroupId)
            ->where('companySystemID', $companySystemId)
           ->orderBy('id', 'desc');
    }

    public function getContractUserListForUserGroup($companySystemId, $groupId)
    {
        return ContractUsers::select('contractUserId as id', 'contractUserId', 'contractUserName as itemName')
            ->whereDoesntHave('assignedContractUserGroup', function ($query) use ($groupId) {
                $query->where('userGroupId', $groupId);
            })
            ->where('companySystemId', $companySystemId);
    }

    public function getContractUserListToAssign($companySystemId, $contractId)
    {
        $userList =  ContractUsers::select('id', 'contractUserName as itemName')
            ->whereDoesntHave('assignedContractUserGroup')
            ->where('companySystemId', $companySystemId)
        ->get();

        $contractUserAssignedResult = ContractUserAssign::where('contractId', $contractId)
            ->where('userGroupId', 0)
            ->pluck('userId')->toArray();

        $selectedUserList =  ContractUsers::select('id', 'contractUserName as itemName')
            ->whereIn('id', $contractUserAssignedResult)
            ->whereIn('id', $contractUserAssignedResult)
            ->get();

        return [
            'userList' => $userList,
            'userSelected' => $selectedUserList
        ];
    }

    public function contractUserGroupList($companySystemId,$contractuuid)
    {
        $userGroup =  ContractUserGroup::selectRaw('id, groupName AS itemName')
            ->where('companySystemId', $companySystemId)
            ->where('status', 1)
            ->get();
        $contractResult = ContractMaster::select('id')->where('uuid', $contractuuid)->first();
        $contractUserAssignedResult = ContractUserAssign::where('contractId', $contractResult->id)
            ->pluck('userGroupId')->toArray();
        $userGroupSelected =  ContractUserGroup::selectRaw('id, groupName AS itemName')
            ->where('companySystemId', $companySystemId)
            ->whereIn('id', $contractUserAssignedResult)
            ->get();
        return [
            'userGroup' => $userGroup,
            'userGroupSelected' => $userGroupSelected
        ];
    }
}
