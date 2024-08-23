<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmployeeDepartments
 * @package App\Models
 * @version August 22, 2024, 1:16 pm +04
 *
 * @property integer $EmpID
 * @property integer $DepartmentMasterID
 * @property integer $isPrimary
 * @property string $date_from
 * @property string $date_to
 * @property integer $Erp_companyID
 * @property integer $SchMasterID
 * @property integer $BranchID
 * @property integer $AcademicYearID
 * @property integer $isActive
 * @property string $CreatedUserName
 * @property string|\Carbon\Carbon $CreatedDate
 * @property string $CreatedPC
 * @property string $ModifiedUserName
 * @property string|\Carbon\Carbon $Timestamp
 * @property string $ModifiedPC
 */
class EmployeeDepartments extends Model
{

    use HasFactory;

    public $table = 'srp_empdepartments';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'EmpID',
        'DepartmentMasterID',
        'isPrimary',
        'date_from',
        'date_to',
        'Erp_companyID',
        'SchMasterID',
        'BranchID',
        'AcademicYearID',
        'isActive',
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
        'EmpDepartmentID' => 'integer',
        'EmpID' => 'integer',
        'DepartmentMasterID' => 'integer',
        'isPrimary' => 'integer',
        'date_from' => 'date',
        'date_to' => 'date',
        'Erp_companyID' => 'integer',
        'SchMasterID' => 'integer',
        'BranchID' => 'integer',
        'AcademicYearID' => 'integer',
        'isActive' => 'integer',
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
        'EmpID' => 'nullable|integer',
        'DepartmentMasterID' => 'nullable|integer',
        'isPrimary' => 'nullable|integer',
        'date_from' => 'required',
        'date_to' => 'nullable',
        'Erp_companyID' => 'nullable|integer',
        'SchMasterID' => 'nullable|integer',
        'BranchID' => 'nullable|integer',
        'AcademicYearID' => 'nullable|integer',
        'isActive' => 'nullable|integer',
        'CreatedUserName' => 'nullable|string|max:255',
        'CreatedDate' => 'nullable',
        'CreatedPC' => 'nullable|string|max:255',
        'ModifiedUserName' => 'nullable|string|max:255',
        'Timestamp' => 'nullable',
        'ModifiedPC' => 'nullable|string|max:255'
    ];

    public function department()
    {
        return $this->belongsTo(DepartmentMaster::class, 'DepartmentMasterID', 'DepartmentMasterID');
    }


}
