<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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



    public $fillable = [
        'uuid',
        'groupName',
        'status',
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
        'status' => 'integer'
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
        return ContractUserGroup::select('uuid', 'groupName', 'status')
            ->where('companySystemId', $companySystemId);
    }

    public function getContractUserGroupAssignedUsers($companySystemId, $selectUserGroup)
    {
        $result = ContractUserGroup::select('id')->where('uuid', $selectUserGroup)->first();
        $userGroupId = 0;
        if(isset($result)){
            $userGroupId = $result->id;
        }
       return ContractUserGroupAssignedUser::select('uuid', 'contractUserId', 'created_at', 'created_by')
           ->with(['contractUserGroup', 'user' ,'employee'])
            ->where('userGroupId', $userGroupId)
            ->where('companySystemID', $companySystemId);
    }

    public function getContractUserListForUserGroup($companySystemId, $groupId)
    {
        return ContractUsers::select('uuid', 'contractUserId', 'contractUserName')
            ->whereDoesntHave('assignedContractUserGroup', function ($query) use ($groupId) {
                $query->where('userGroupId', $groupId);
            })
            ->where('companySystemId', $companySystemId);
    }
}
