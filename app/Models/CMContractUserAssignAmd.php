<?php

namespace App\Models;

use App\Exceptions\ContractCreationException;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

/**
 * Class CMContractUserAssignAmd
 * @package App\Models
 * @version July 2, 2024, 4:20 am +04
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $contractId
 * @property integer $userGroupId
 * @property integer $userId
 * @property integer $status
 * @property integer $createdBy
 * @property integer $updatedBy
 */
class CMContractUserAssignAmd extends Model
{
    use HasFactory;

    public $table = 'cm_contract_user_assign_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'id',
        'contract_history_id',
        'uuid',
        'contractId',
        'userGroupId',
        'userId',
        'status',
        'createdBy',
        'updatedBy'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'amd_id' => 'integer',
        'id' => 'integer',
        'contract_history_id' => 'integer',
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

    public function userAssignedData($id)
    {
        $subquery = CMContractUserAssignAmd::select('userGroupId', DB::raw('MIN(amd_id) as min_id'))
            ->where('userGroupId', '!=', 0)
            ->where('contract_history_id',$id)
            ->groupBy('userGroupId', 'status', 'created_at');

        $distinctRecords = CMContractUserAssignAmd::with
        (
            ['userGroup', 'assignedUsers', 'employee', 'updatedByEmployee']
        )
            ->select('cm_contract_user_assign_amd.*')
            ->joinSub($subquery, 'sub', function ($join)
            {
                $join->on('cm_contract_user_assign_amd.userGroupId', '=', 'sub.userGroupId')
                    ->on('cm_contract_user_assign_amd.amd_id', '=', 'sub.min_id');
            });

        $allRecords = CMContractUserAssignAmd::with(['userGroup', 'assignedUsers', 'employee', 'updatedByEmployee'])
            ->select('cm_contract_user_assign_amd.*')
            ->where('userGroupId', '=', 0)
            ->where('contract_history_id',$id)
            ->orderBy('amd_id', 'desc');

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
    public static function getContractIdColumn()
    {
        return 'contractId';
    }

    public static function getCompanyIdColumn()
    {
        return null;
    }
}
