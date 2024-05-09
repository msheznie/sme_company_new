<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractUserGroupAssignedUser extends Model
{
    use HasFactory;

    public $table = 'cm_contract_user_group_assigned_user';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'id',
        'uuid',
        'userGroupId',
        'companySystemID',
        'contractUserId',
        'giveAccessToExistingContracts',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'userGroupId' => 'integer',
        'companySystemID' => 'integer',
        'contractUserId' => 'integer',
        'giveAccessToExistingContracts' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'userGroupId' => 'required',
        'companySystemID' => 'required',
        'contractUserId' => 'required'
    ];

    public function contractUserGroup(){
        return $this->belongsTo(ContractUserGroup::class, 'userGroupId', 'id');
    }

    public function user(){
        return $this->belongsTo(ContractUsers::class,'contractUserId','contractUserId');
    }

    public function employee(){
        return $this->belongsTo(Employees::class, 'created_by', 'employeeSystemID');
    }
}
