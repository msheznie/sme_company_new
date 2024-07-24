<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;
/**
 * Class ContractUserAssign
 * @package App\Models
 * @version May 13, 2024, 5:42 am +04
 *
 * @property string $uuid
 * @property integer $contractId
 * @property integer $userGroupId
 * @property integer $userId
 * @property integer $status
 * @property integer $createdBy
 * @property integer $updatedBy
 */
class ContractUserAssign extends Model
{
    use HasFactory;
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_contract_user_assign';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'userGroupId', 'userId', 'createdBy', 'updatedBy'];

    public $fillable = [
        'uuid',
        'contractId',
        'userGroupId',
        'userId',
        'status',
        'createdBy',
        'updatedBy',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'contractId' => 'integer',
        'userGroupId' => 'integer',
        'userId' => 'integer',
        'status' => 'integer',
        'createdBy' => 'integer',
        'updatedBy' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function getAssignedUsers($companySystemId, $uuid)
    {
        $contractResults = ContractMaster::select('id')->where('uuid', $uuid)->first();
        $subquery = ContractUserAssign::select('userGroupId', DB::raw('MIN(id) as min_id'))
            ->where('userGroupId', '!=', 0)
            ->where('contractId', $contractResults->id)
            ->groupBy('userGroupId', 'status', 'created_at');

        $distinctRecords = ContractUserAssign::with(['userGroup', 'assignedUsers', 'employee', 'updatedByEmployee'])
            ->select('cm_contract_user_assign.*')
            ->joinSub($subquery, 'sub', function ($join)
            {
                $join->on('cm_contract_user_assign.userGroupId', '=', 'sub.userGroupId')
                    ->on('cm_contract_user_assign.id', '=', 'sub.min_id');
            });

        $allRecords = ContractUserAssign::with(['userGroup', 'assignedUsers', 'employee', 'updatedByEmployee'])
            ->select('cm_contract_user_assign.*')
            ->where('userGroupId', '=', 0)
            ->where('contractId', $contractResults->id)
            ->orderBy('id', 'desc');

        return $distinctRecords->union($allRecords);
    }

    public function userGroup()
    {
        return $this->belongsTo('App\Models\ContractUserGroup','userGroupId','id');
    }

    public function assignedUsers()
    {
        return $this->hasOne('App\Models\ContractUsers', 'id', 'userId');
    }
    public function employee()
    {
        return $this->belongsTo(Employees::class,  'createdBy', 'employeeSystemID');
    }
    public function updatedByEmployee()
    {
        return $this->belongsTo(Employees::class, 'updatedBy', 'employeeSystemID');
    }


    public function contractUserGroupAssignedUser()
    {
        return $this->belongsTo(ContractUserGroupAssignedUser::class, 'userId', 'contractUserId');
    }

    public static function getUserAssignDetailsByContractId($contractId)
    {
            return self::where('contractId', $contractId)
                ->get();
    }

    public static function getContractIdColumn()
    {
        return 'contractId';
    }

    public static function getCompanyIdColumn()
    {
        return null;
    }

    public static function getAssignUserGroups($contractId)
    {
        return ContractUserAssign::select('userGroupId')
            ->where('contractId', $contractId)
            ->where('status', 1)
            ->distinct()
            ->get();
    }

    public function contractMaster()
    {
        return $this->belongsTo(ContractMaster::class, 'contractId', 'id');
    }

    public static function  getReminderContractExpiryUsers($contractIds)
    {
        return self::with(['contractMaster' => function ($query)
        {
            $query->select('id', 'contractCode', 'title', 'contractOwner',
                'counterPartyName', 'companySystemID', 'endDate'
            );
        }])
            ->whereIn('contractId', $contractIds)
            ->where('status',1)
            ->get(['userId', 'contractId'])
            ->map(function ($contractUserAssign)
            {
                return [
                    'userId' => $contractUserAssign->userId,
                    'contractId' => $contractUserAssign->contractId,
                    'contractCode' => $contractUserAssign->contractMaster->contractCode,
                    'title' => $contractUserAssign->contractMaster->title,
                    'contractOwner' => $contractUserAssign->contractMaster->contractOwner,
                    'counterPartyName' => $contractUserAssign->contractMaster->counterPartyName,
                    'companySystemID' => $contractUserAssign->contractMaster->companySystemID,
                    'endDate' => $contractUserAssign->contractMaster->endDate,
                ];
            });
    }

}
