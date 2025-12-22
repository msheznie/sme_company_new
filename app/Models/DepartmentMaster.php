<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DepartmentMaster
 * @package App\Models
 * @version August 22, 2024, 1:21 pm +04
 *
 * @property string $DepartmentDes
 * @property integer $parent_department_id
 * @property integer $is_root_department
 * @property integer $Erp_companyID
 * @property integer $SchMasterID
 * @property integer $BranchID
 * @property integer $SortOrder
 * @property integer $hod_id
 * @property integer $isActive
 * @property integer $created_by
 * @property string $CreatedUserName
 * @property string|\Carbon\Carbon $CreatedDate
 * @property string $CreatedPC
 * @property string $ModifiedUserName
 * @property string|\Carbon\Carbon $Timestamp
 * @property string $ModifiedPC
 */
class DepartmentMaster extends Model
{

    use HasFactory;

    public $table = 'srp_departmentmaster';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $hidden = ['DepartmentMasterID'];



    public $fillable = [
        'DepartmentDes',
        'parent_department_id',
        'is_root_department',
        'Erp_companyID',
        'SchMasterID',
        'BranchID',
        'SortOrder',
        'hod_id',
        'isActive',
        'created_by',
        'CreatedUserName',
        'CreatedDate',
        'CreatedPC',
        'ModifiedUserName',
        'Timestamp',
        'ModifiedPC'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'DepartmentMasterID' => 'integer',
        'DepartmentDes' => 'string',
        'parent_department_id' => 'integer',
        'is_root_department' => 'integer',
        'Erp_companyID' => 'integer',
        'SchMasterID' => 'integer',
        'BranchID' => 'integer',
        'SortOrder' => 'integer',
        'hod_id' => 'integer',
        'isActive' => 'integer',
        'created_by' => 'integer',
        'CreatedUserName' => 'string',
        'CreatedDate' => 'datetime',
        'CreatedPC' => 'string',
        'ModifiedUserName' => 'string',
        'Timestamp' => 'datetime',
        'ModifiedPC' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'DepartmentDes' => 'nullable|string|max:255',
        'parent_department_id' => 'required|integer',
        'is_root_department' => 'required|integer',
        'Erp_companyID' => 'nullable|integer',
        'SchMasterID' => 'nullable|integer',
        'BranchID' => 'nullable|integer',
        'SortOrder' => 'nullable|integer',
        'hod_id' => 'nullable|integer',
        'isActive' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'CreatedUserName' => 'nullable|string|max:255',
        'CreatedDate' => 'nullable',
        'CreatedPC' => 'nullable|string|max:255',
        'ModifiedUserName' => 'nullable|string|max:255',
        'Timestamp' => 'nullable',
        'ModifiedPC' => 'nullable|string|max:255'
    ];


}
