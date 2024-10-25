<?php

namespace App\Models;

use App\Helpers\General;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

/**
 * Class ErpDocumentReferedHistory
 * @package App\Models
 * @version September 26, 2024, 2:21 pm +04
 *
 * @property integer $documentApprovedID
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
 * @property string $rejectedYN
 * @property string|\Carbon\Carbon $rejectedDate
 * @property string $rejectedComments
 * @property string $approvedPCID
 * @property string $reference_email
 * @property integer $myApproveFlag
 * @property integer $isDeligationApproval
 * @property string $approvedForEmpID
 * @property integer $isApprovedFromPC
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $refTimes
 * @property boolean $status
 */
class ErpDocumentReferedHistory extends Model
{
    public $table = 'erp_documentreferedhistory';

    const CREATED_AT = 'timestamp';
    const UPDATED_AT = 'timestamp';
    protected $primaryKey = 'documentApprovedID';

    public $fillable = [
        'documentApprovedID',
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
        'approvedPCID',
        'reference_email',
        'myApproveFlag',
        'isDeligationApproval',
        'approvedForEmpID',
        'isApprovedFromPC',
        'timeStamp',
        'refTimes',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documentReferedID' => 'integer',
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
        'docConfirmedByEmpSystemID' => 'integer',
        'docConfirmedByEmpID' => 'string',
        'preRollApprovedDate' => 'datetime',
        'approvedYN' => 'integer',
        'approvedComments' => 'string',
        'rejectedYN' => 'string',
        'rejectedDate' => 'datetime',
        'rejectedComments' => 'string',
        'approvedPCID' => 'string',
        'reference_email' => 'string',
        'myApproveFlag' => 'integer',
        'isDeligationApproval' => 'integer',
        'approvedForEmpID' => 'string',
        'isApprovedFromPC' => 'integer',
        'refTimes' => 'integer',
        'status' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function saveReferedHistory($insertArray)
    {
        return ErpDocumentReferedHistory::insert($insertArray);
    }

    public function updateReferedHistory(
        $documentSystemCode, $companySystemID, $documentSystemID, $documentApprovedID, $rollLevelOrder,
        $referedHistoryArray
    )
    {
        return ErpDocumentReferedHistory::where('documentSystemCode', $documentSystemCode)
            ->where('companySystemID', $companySystemID)
            ->where('documentSystemID', $documentSystemID)
            ->where('documentApprovedID', $documentApprovedID)
            ->where('rollLevelOrder', $rollLevelOrder)
            ->update($referedHistoryArray);
    }


}
