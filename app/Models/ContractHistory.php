<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractHistory
 * @package App\Models
 * @version May 29, 2024, 2:49 pm +04
 *
 * @property integer $category
 * @property string $date
 * @property string $end_date
 * @property integer $contract_id
 * @property integer $company_id
 * @property string $contract_title
 * @property string $created_date
 * @property integer $created_by
 */
class ContractHistory extends Model
{
    use HasFactory;

    public $table = 'cm_contract_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'category',
        'date',
        'end_date',
        'uuid',
        'contract_id',
        'cloning_contract_id',
        'company_id',
        'created_by',
        'comment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category' => 'integer',
        'uuid' => 'string',
        'date' => 'date',
        'end_date' => 'date',
        'contract_id' => 'integer',
        'cloning_contract_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'comment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category' => 'required|integer',
        'date' => 'required',
        'end_date' => 'required',
        'contract_id' => 'required|integer',
        'company_id' => 'required|integer',
        'contract_title' => 'required|string|max:255',
        'created_by' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'comment' => 'nullable',
    ];

    public static function addendumData($contractId, $companyId,$contractCategory)
    {
        return self::select('id', 'uuid', 'contract_id', 'company_id', 'created_at', 'created_by')
            ->with(['employees' => function ($query)
            {
                $query->select('employeeSystemID', 'empName');
            } , 'contractMaster' => function ($query)
            {
                $query->select('id', 'contractCode','startDate','endDate','parent_id','uuid','confirmed_yn',
                'approved_yn','refferedBackYN','timesReferred'
                );
            }])
            ->where('company_id', $companyId)
            ->where('category', $contractCategory)
            ->where('cloning_contract_id', $contractId)
            ->get();
    }

    public function employees()
    {
        return $this->hasOne(Employees::class, 'employeeSystemID', 'created_by');
    }

    public function contractMaster()
    {
        return $this->hasOne(ContractMaster::class, 'id', 'contract_id');
    }

    public function contractOldMaster()
    {
        return $this->belongsTo(ContractMaster::class, 'contract_id', 'id');
    }

    public function createdUser()
    {
        return $this->belongsTo(Employees::class, 'created_by', 'employeeSystemID');
    }

    public static function contractHistory($contractId, $categoryId, $companySystemID)
    {
        return ContractHistory::with([
            'contractOldMaster' => function ($q)
            {
                $q->select('title', 'id', );
            }, 'createdUser' => function ($q1)
            {
                $q1->select('employeeSystemID', 'empName');
            }
        ])->where('contract_id', $contractId)
            ->where('company_id', $companySystemID)
            ->where('category', $categoryId)
            ->orderBy('id', 'asc');
    }
}
