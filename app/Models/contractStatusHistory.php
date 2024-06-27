<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class contractStatusHistory
 * @package App\Models
 * @version June 26, 2024, 10:27 am +04
 *
 * @property integer $contract_id
 * @property integer $contract_history_id
 * @property integer $status
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class contractStatusHistory extends Model
{
    use HasFactory;

    public $table = 'cm_contract_status_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'contract_id',
        'status',
        'company_id',
        'contract_history_id',
        'created_by',
        'updated_by',
        'system_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contract_id' => 'integer',
        'contract_history_id' => 'integer',
        'status' => 'integer',
        'company_id' => 'integer',
        'system_user' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];


    public function getContractListStatus($id)
    {
        return contractStatusHistory::select('id','status','contract_id','created_at','created_by','system_user')
        ->with(['employees' => function ($query)
        {
            $query->select('employeeSystemID','empName');
        }])
        ->where('contract_id', $id)
        ->get();
    }

    public function employees()
    {
        return $this->hasOne(Employees::class, 'employeeSystemID', 'created_by');
    }
}
