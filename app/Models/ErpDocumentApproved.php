<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpDocumentApproved
 * @package App\Models
 * @version May 22, 2024, 9:25 am +04
 *
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $departmentSystemID
 * @property string $departmentID
 * @property integer $serviceLineSystemID
 * @property string $serviceLineCode
 * @property integer $documentSystemID
 * @property string $documentID
 * @property integer $documentSystemCode
 * @property string $documentCode
 * @property string|\Carbon\Carbon $documentDate
 * @property integer $approvalLevelID
 * @property integer $rollID
 * @property integer $approvalGroupID
 * @property integer $rollLevelOrder
 * @property integer $employeeSystemID
 * @property string $employeeID
 * @property string|\Carbon\Carbon $docConfirmedDate
 * @property integer $docConfirmedByEmpSystemID
 * @property string $docConfirmedByEmpID
 * @property string|\Carbon\Carbon $preRollApprovedDate
 * @property integer $approvedYN
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedComments
 * @property integer $rejectedYN
 * @property string|\Carbon\Carbon $rejectedDate
 * @property string $rejectedComments
 * @property integer $myApproveFlag
 * @property integer $isDeligationApproval
 * @property string $approvedForEmpID
 * @property integer $isApprovedFromPC
 * @property string $approvedPCID
 * @property string $reference_email
 * @property string|\Carbon\Carbon $timeStamp
 * @property boolean $status
 */
class ErpDocumentApproved extends Model
{
    use HasFactory;

    public $table = 'erp_documentapproved';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';


    protected $primaryKey = 'documentApprovedID';

    public $fillable = [
        'companySystemID',
        'companyID',
        'departmentSystemID',
        'departmentID',
        'serviceLineSystemID',
        'serviceLineCode',
        'documentSystemID',
        'documentID',
        'documentSystemCode',
        'documentCode',
        'documentDate',
        'approvalLevelID',
        'rollID',
        'approvalGroupID',
        'rollLevelOrder',
        'employeeSystemID',
        'employeeID',
        'docConfirmedDate',
        'docConfirmedByEmpSystemID',
        'docConfirmedByEmpID',
        'preRollApprovedDate',
        'approvedYN',
        'approvedDate',
        'approvedComments',
        'rejectedYN',
        'rejectedDate',
        'rejectedComments',
        'myApproveFlag',
        'isDeligationApproval',
        'approvedForEmpID',
        'isApprovedFromPC',
        'approvedPCID',
        'reference_email',
        'timeStamp',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documentApprovedID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'departmentSystemID' => 'integer',
        'departmentID' => 'string',
        'serviceLineSystemID' => 'integer',
        'serviceLineCode' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'documentSystemCode' => 'integer',
        'documentCode' => 'string',
        'documentDate' => 'datetime',
        'approvalLevelID' => 'integer',
        'rollID' => 'integer',
        'approvalGroupID' => 'integer',
        'rollLevelOrder' => 'integer',
        'employeeSystemID' => 'integer',
        'employeeID' => 'string',
        'docConfirmedDate' => 'datetime',
        'docConfirmedByEmpSystemID' => 'integer',
        'docConfirmedByEmpID' => 'string',
        'preRollApprovedDate' => 'datetime',
        'approvedYN' => 'integer',
        'approvedDate' => 'datetime',
        'approvedComments' => 'string',
        'rejectedYN' => 'integer',
        'rejectedDate' => 'datetime',
        'rejectedComments' => 'string',
        'myApproveFlag' => 'integer',
        'isDeligationApproval' => 'integer',
        'approvedForEmpID' => 'string',
        'isApprovedFromPC' => 'integer',
        'approvedPCID' => 'string',
        'reference_email' => 'string',
        'timeStamp' => 'datetime',
        'status' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function saveDocumentApproved($insertArray)
    {
        return ErpDocumentApproved::insert($insertArray);
    }
    public function contractMaster()
    {
        return $this->belongsTo(ContractMaster::class, 'documentSystemCode', 'id');
    }
    public static function checkApprovalEligible($documentApprovedID)
    {
        return ErpDocumentApproved::where('documentApprovedID', $documentApprovedID)
            ->where('approvedYN', -1)
            ->first();
    }
    public static function updateDocumentApproved(
        $documentApprovedID,
        $approvedComment,
        $employeeSystemID,
        $employeeCode
    )
    {
        return ErpDocumentApproved::where('documentApprovedID', $documentApprovedID)
            ->update([
                'approvedYN' => -1,
                'approvedDate' => now(),
                'approvedComments' => $approvedComment,
                'employeeSystemID' => $employeeSystemID,
                'employeeID' => $employeeCode
            ]);
    }
    public static function checkApproveDocumentExists(
        $documentApprovedID,
        $isConfirmation = 0,
        $documentMasterID = 0,
        $documentSystemCode = 0
    )
    {
        return ErpDocumentApproved::select('documentApprovedID', 'companySystemID', 'approvalGroupID',
            'departmentSystemID', 'documentSystemID', 'documentSystemCode', 'approvalLevelID', 'approvalGroupID',
            'approvedYN', 'approvedDate', 'rejectedYN')
            ->when($isConfirmation == 1, function ($q) use ($documentMasterID, $documentSystemCode)
            {
                $q->where([
                    'documentSystemID' => $documentMasterID,
                    'documentSystemCode' => $documentSystemCode
                ]);
            })
            ->when($isConfirmation == 0, function ($q) use ($documentApprovedID)
            {
                $q->where('documentApprovedID', $documentApprovedID);
            })
            ->first();
    }
    public function approved_by()
    {
        return $this->belongsTo(Employees::class,'employeeSystemID','employeeSystemID');
    }
    public function documentApprovedList($documentSystemID, $ids, $companySystemID)
    {
        return ErpDocumentApproved::select('documentApprovedID', 'approvedYN', 'rollLevelOrder', 'employeeSystemID',
            'approvalGroupID', 'rejectedYN', 'rejectedComments', 'rejectedDate', 'approvedComments', 'approvedDate'
        )
            ->where('documentSystemID', $documentSystemID)
            ->whereIn('documentSystemCode', $ids)
            ->where('companySystemID', $companySystemID)
            ->with(['approved_by'])
            ->get();
    }
}
