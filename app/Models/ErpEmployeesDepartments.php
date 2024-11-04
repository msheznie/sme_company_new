<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpEmployeesDepartments
 * @package App\Models
 * @version June 3, 2024, 3:24 pm +04
 *
 * @property integer $employeeSystemID
 * @property string $employeeID
 * @property integer $employeeGroupID
 * @property integer $companySystemID
 * @property string $companyId
 * @property integer $documentSystemID
 * @property string $documentID
 * @property integer $departmentSystemID
 * @property string $departmentID
 * @property integer $ServiceLineSystemID
 * @property string $ServiceLineID
 * @property integer $warehouseSystemCode
 * @property string $reportingManagerID
 * @property integer $isDefault
 * @property integer $dischargedYN
 * @property integer $approvalDeligated
 * @property string $approvalDeligatedFromEmpID
 * @property string $approvalDeligatedFrom
 * @property string $approvalDeligatedTo
 * @property integer $dmsIsUploadEnable
 * @property integer $isActive
 * @property string|\Carbon\Carbon $activatedDate
 * @property string $activatedByEmpID
 * @property integer $activatedByEmpSystemID
 * @property integer $removedYN
 * @property string $removedByEmpID
 * @property integer $removedByEmpSystemID
 * @property string|\Carbon\Carbon $removedDate
 * @property string|\Carbon\Carbon $createdDate
 * @property integer $createdByEmpSystemID
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $deligateDetaileID
 */
class ErpEmployeesDepartments extends Model
{
    use HasFactory;

    public $table = 'employeesdepartments';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'employeeSystemID',
        'employeeID',
        'employeeGroupID',
        'companySystemID',
        'companyId',
        'documentSystemID',
        'documentID',
        'departmentSystemID',
        'departmentID',
        'ServiceLineSystemID',
        'ServiceLineID',
        'warehouseSystemCode',
        'reportingManagerID',
        'isDefault',
        'dischargedYN',
        'approvalDeligated',
        'approvalDeligatedFromEmpID',
        'approvalDeligatedFrom',
        'approvalDeligatedTo',
        'dmsIsUploadEnable',
        'isActive',
        'activatedDate',
        'activatedByEmpID',
        'activatedByEmpSystemID',
        'removedYN',
        'removedByEmpID',
        'removedByEmpSystemID',
        'removedDate',
        'createdDate',
        'createdByEmpSystemID',
        'timeStamp',
        'deligateDetaileID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'employeesDepartmentsID' => 'integer',
        'employeeSystemID' => 'integer',
        'employeeID' => 'string',
        'employeeGroupID' => 'integer',
        'companySystemID' => 'integer',
        'companyId' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'departmentSystemID' => 'integer',
        'departmentID' => 'string',
        'ServiceLineSystemID' => 'integer',
        'ServiceLineID' => 'string',
        'warehouseSystemCode' => 'integer',
        'reportingManagerID' => 'string',
        'isDefault' => 'integer',
        'dischargedYN' => 'integer',
        'approvalDeligated' => 'integer',
        'approvalDeligatedFromEmpID' => 'string',
        'approvalDeligatedFrom' => 'string',
        'approvalDeligatedTo' => 'string',
        'dmsIsUploadEnable' => 'integer',
        'isActive' => 'integer',
        'activatedDate' => 'datetime',
        'activatedByEmpID' => 'string',
        'activatedByEmpSystemID' => 'integer',
        'removedYN' => 'integer',
        'removedByEmpID' => 'string',
        'removedByEmpSystemID' => 'integer',
        'removedDate' => 'datetime',
        'createdDate' => 'datetime',
        'createdByEmpSystemID' => 'integer',
        'timeStamp' => 'datetime',
        'deligateDetaileID' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employeeSystemID','employeeSystemID');
    }
    public static function checkUserHasApprovalAccess(
        $approvalGroupID,
        $companySystemID,
        $documentSystemID,
        $employeeSystemID
    )
    {
        return  ErpEmployeesDepartments::where([
            'employeeGroupID' => $approvalGroupID,
            'companySystemID' => $companySystemID,
            'documentSystemID' => $documentSystemID,
            'employeeSystemID' => $employeeSystemID,
            'isActive' => 1,
            'removedYN' => 0
        ])
        ->with(['employee'])
        ->groupBy('employeeSystemID')
        ->exists();
    }

    public static function getApprovalList($approvalGroupIDs, $selectedCompanyID, $documentSystemID)
    {
        return ErpEmployeesDepartments::select('employeesDepartmentsID', 'employeeSystemID', 'employeeGroupID')
            ->whereIn('employeeGroupID', $approvalGroupIDs)
            ->where('companySystemID', $selectedCompanyID)
            ->where('documentSystemID', $documentSystemID)
            ->where('isActive', 1)
            ->where('removedYN', 0)
            ->with(['employee' => function ($query)
            {
                $query->select('employeeSystemID', 'empName')
                    ->where('discharegedYN', 0);
            }])
            ->get()
            ->groupBy('employeeGroupID');
    }
    public static function getApprovalListToEmail($approvalGroupID, $companySystemID, $documentSystemID)
    {
        return ErpEmployeesDepartments::select('employeesDepartmentsID', 'employeeSystemID', 'employeeGroupID')
            ->where('employeeGroupID', $approvalGroupID)
            ->with(['employee' => function ($query)
            {
                $query->select('employeeSystemID', 'empName')
                    ->where('discharegedYN', 0)
                    ->where('isEmailVerified', 1);
            }])
            ->where('companySystemID', $companySystemID)
            ->where('documentSystemID', $documentSystemID)
            ->where('isActive', 1)
            ->where('removedYN', 0)
            ->get();
    }
}
