<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ChartOfAccount
 * @package App\Models
 * @version September 9, 2024, 12:47 pm +04
 *
 * @property integer $primaryCompanySystemID
 * @property string $primaryCompanyID
 * @property integer $documentSystemID
 * @property string $documentID
 * @property string $AccountCode
 * @property string $AccountDescription
 * @property string $masterAccount
 * @property integer $catogaryBLorPLID
 * @property string $catogaryBLorPL
 * @property integer $controllAccountYN
 * @property integer $controlAccountsSystemID
 * @property string $controlAccounts
 * @property integer $isApproved
 * @property integer $approvedBySystemID
 * @property string $approvedBy
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedComment
 * @property integer $isActive
 * @property integer $isBank
 * @property integer $AllocationID
 * @property integer $relatedPartyYN
 * @property integer $interCompanySystemID
 * @property string $interCompanyID
 * @property integer $confirmedYN
 * @property integer $confirmedEmpSystemID
 * @property string $confirmedEmpID
 * @property string $confirmedEmpName
 * @property string|\Carbon\Carbon $confirmedEmpDate
 * @property integer $isMasterAccount
 * @property integer $RollLevForApp_curr
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property string $createdPcID
 * @property string $createdUserGroup
 * @property string $createdUserID
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string $modifiedPc
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $timestamp
 * @property integer $reportTemplateCategory
 */
class ChartOfAccount extends Model
{
    use HasFactory;

    public $table = 'chartofaccounts';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timestamp';

    protected $primaryKey = 'chartOfAccountSystemID';

    protected $dates = ['deleted_at'];



    public $fillable = [
        'primaryCompanySystemID',
        'primaryCompanyID',
        'documentSystemID',
        'documentID',
        'AccountCode',
        'AccountDescription',
        'masterAccount',
        'catogaryBLorPLID',
        'catogaryBLorPL',
        'controllAccountYN',
        'controlAccountsSystemID',
        'controlAccounts',
        'isApproved',
        'approvedBySystemID',
        'approvedBy',
        'approvedDate',
        'approvedComment',
        'isActive',
        'isBank',
        'AllocationID',
        'relatedPartyYN',
        'interCompanySystemID',
        'interCompanyID',
        'confirmedYN',
        'confirmedEmpSystemID',
        'confirmedEmpID',
        'confirmedEmpName',
        'confirmedEmpDate',
        'isMasterAccount',
        'RollLevForApp_curr',
        'refferedBackYN',
        'timesReferred',
        'createdPcID',
        'createdUserGroup',
        'createdUserID',
        'createdDateTime',
        'modifiedPc',
        'modifiedUser',
        'timestamp',
        'reportTemplateCategory'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'chartOfAccountSystemID' => 'integer',
        'primaryCompanySystemID' => 'integer',
        'primaryCompanyID' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'AccountCode' => 'string',
        'AccountDescription' => 'string',
        'masterAccount' => 'string',
        'catogaryBLorPLID' => 'integer',
        'catogaryBLorPL' => 'string',
        'controllAccountYN' => 'integer',
        'controlAccountsSystemID' => 'integer',
        'controlAccounts' => 'string',
        'isApproved' => 'integer',
        'approvedBySystemID' => 'integer',
        'approvedBy' => 'string',
        'approvedDate' => 'datetime',
        'approvedComment' => 'string',
        'isActive' => 'integer',
        'isBank' => 'integer',
        'AllocationID' => 'integer',
        'relatedPartyYN' => 'integer',
        'interCompanySystemID' => 'integer',
        'interCompanyID' => 'string',
        'confirmedYN' => 'integer',
        'confirmedEmpSystemID' => 'integer',
        'confirmedEmpID' => 'string',
        'confirmedEmpName' => 'string',
        'confirmedEmpDate' => 'datetime',
        'isMasterAccount' => 'integer',
        'RollLevForApp_curr' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'createdPcID' => 'string',
        'createdUserGroup' => 'string',
        'createdUserID' => 'string',
        'createdDateTime' => 'datetime',
        'modifiedPc' => 'string',
        'modifiedUser' => 'string',
        'timestamp' => 'datetime',
        'reportTemplateCategory' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'primaryCompanySystemID' => 'nullable|integer',
        'primaryCompanyID' => 'nullable|string|max:45',
        'documentSystemID' => 'nullable|integer',
        'documentID' => 'nullable|string|max:20',
        'AccountCode' => 'required|string|max:20',
        'AccountDescription' => 'nullable|string',
        'masterAccount' => 'nullable|string|max:20',
        'catogaryBLorPLID' => 'nullable|integer',
        'catogaryBLorPL' => 'required|string|max:2',
        'controllAccountYN' => 'nullable|integer',
        'controlAccountsSystemID' => 'nullable|integer',
        'controlAccounts' => 'nullable|string|max:50',
        'isApproved' => 'nullable|integer',
        'approvedBySystemID' => 'nullable|integer',
        'approvedBy' => 'nullable|string|max:100',
        'approvedDate' => 'nullable',
        'approvedComment' => 'nullable|string',
        'isActive' => 'nullable|integer',
        'isBank' => 'nullable|integer',
        'AllocationID' => 'nullable|integer',
        'relatedPartyYN' => 'nullable|integer',
        'interCompanySystemID' => 'nullable|integer',
        'interCompanyID' => 'nullable|string|max:45',
        'confirmedYN' => 'nullable|integer',
        'confirmedEmpSystemID' => 'nullable|integer',
        'confirmedEmpID' => 'nullable|string|max:100',
        'confirmedEmpName' => 'nullable|string|max:500',
        'confirmedEmpDate' => 'nullable',
        'isMasterAccount' => 'nullable|integer',
        'RollLevForApp_curr' => 'nullable|integer',
        'refferedBackYN' => 'nullable|integer',
        'timesReferred' => 'nullable|integer',
        'createdPcID' => 'nullable|string|max:255',
        'createdUserGroup' => 'nullable|string|max:255',
        'createdUserID' => 'nullable|string|max:255',
        'createdDateTime' => 'nullable',
        'modifiedPc' => 'nullable|string|max:255',
        'modifiedUser' => 'nullable|string|max:255',
        'timestamp' => 'nullable',
        'reportTemplateCategory' => 'nullable|integer'
    ];


}
