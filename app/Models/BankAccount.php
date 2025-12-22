<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BankAccount
 * @package App\Models
 * @version August 14, 2024, 4:08 pm +04
 *
 * @property integer $bankAssignedAutoID
 * @property integer $bankmasterAutoID
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $documentSystemID
 * @property string $documentID
 * @property string $bankShortCode
 * @property string $bankName
 * @property string $bankBranch
 * @property string $BranchCode
 * @property string $BranchAddress
 * @property string $BranchContactPerson
 * @property string $BranchTel
 * @property string $BranchFax
 * @property string $BranchEmail
 * @property string $AccountNo
 * @property string $AccountName
 * @property integer $accountCurrencyID
 * @property string $accountSwiftCode
 * @property string $accountIBAN#
 * @property integer $chqueManualStartingNo
 * @property integer $isManualActive
 * @property integer $chquePrintedStartingNo
 * @property integer $isPrintedActive
 * @property integer $chartOfAccountSystemID
 * @property string $glCodeLinked
 * @property boolean $isCash
 * @property string $extraNote
 * @property integer $isAccountActive
 * @property integer $isDefault
 * @property integer $approvedYN
 * @property string $approvedByEmpID
 * @property integer $approvedByUserSystemID
 * @property string $approvedEmpName
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedComments
 * @property string|\Carbon\Carbon $createdDateTime
 * @property integer $createdUserSystemID
 * @property string $createdEmpID
 * @property string $createdPCID
 * @property string $modifedDateTime
 * @property integer $modifiedUserSystemID
 * @property string $modifiedByEmpID
 * @property string $modifiedPCID
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $confirmedYN
 * @property integer $confirmedByEmpSystemID
 * @property string $confirmedByEmpID
 * @property string $confirmedByName
 * @property string|\Carbon\Carbon $confirmedDate
 * @property integer $RollLevForApp_curr
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property integer $isTempBank
 */
class BankAccount extends Model
{
    use HasFactory;

    public $table = 'erp_bankaccount';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timeStamp';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'bankAccountAutoID';


    public $fillable = [
        'bankAssignedAutoID',
        'bankmasterAutoID',
        'companySystemID',
        'companyID',
        'documentSystemID',
        'documentID',
        'bankShortCode',
        'bankName',
        'bankBranch',
        'BranchCode',
        'BranchAddress',
        'BranchContactPerson',
        'BranchTel',
        'BranchFax',
        'BranchEmail',
        'AccountNo',
        'AccountName',
        'accountCurrencyID',
        'accountSwiftCode',
        'accountIBAN#',
        'chqueManualStartingNo',
        'isManualActive',
        'chquePrintedStartingNo',
        'isPrintedActive',
        'chartOfAccountSystemID',
        'glCodeLinked',
        'isCash',
        'extraNote',
        'isAccountActive',
        'isDefault',
        'approvedYN',
        'approvedByEmpID',
        'approvedByUserSystemID',
        'approvedEmpName',
        'approvedDate',
        'approvedComments',
        'createdDateTime',
        'createdUserSystemID',
        'createdEmpID',
        'createdPCID',
        'modifedDateTime',
        'modifiedUserSystemID',
        'modifiedByEmpID',
        'modifiedPCID',
        'timeStamp',
        'confirmedYN',
        'confirmedByEmpSystemID',
        'confirmedByEmpID',
        'confirmedByName',
        'confirmedDate',
        'RollLevForApp_curr',
        'refferedBackYN',
        'timesReferred',
        'isTempBank'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'bankAccountAutoID' => 'integer',
        'bankAssignedAutoID' => 'integer',
        'bankmasterAutoID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'bankShortCode' => 'string',
        'bankName' => 'string',
        'bankBranch' => 'string',
        'BranchCode' => 'string',
        'BranchAddress' => 'string',
        'BranchContactPerson' => 'string',
        'BranchTel' => 'string',
        'BranchFax' => 'string',
        'BranchEmail' => 'string',
        'AccountNo' => 'string',
        'AccountName' => 'string',
        'accountCurrencyID' => 'integer',
        'accountSwiftCode' => 'string',
        'accountIBAN#' => 'string',
        'chqueManualStartingNo' => 'integer',
        'isManualActive' => 'integer',
        'chquePrintedStartingNo' => 'integer',
        'isPrintedActive' => 'integer',
        'chartOfAccountSystemID' => 'integer',
        'glCodeLinked' => 'string',
        'isCash' => 'boolean',
        'extraNote' => 'string',
        'isAccountActive' => 'integer',
        'isDefault' => 'integer',
        'approvedYN' => 'integer',
        'approvedByEmpID' => 'string',
        'approvedByUserSystemID' => 'integer',
        'approvedEmpName' => 'string',
        'approvedDate' => 'datetime',
        'approvedComments' => 'string',
        'createdDateTime' => 'datetime',
        'createdUserSystemID' => 'integer',
        'createdEmpID' => 'string',
        'createdPCID' => 'string',
        'modifedDateTime' => 'string',
        'modifiedUserSystemID' => 'integer',
        'modifiedByEmpID' => 'string',
        'modifiedPCID' => 'string',
        'timeStamp' => 'datetime',
        'confirmedYN' => 'integer',
        'confirmedByEmpSystemID' => 'integer',
        'confirmedByEmpID' => 'string',
        'confirmedByName' => 'string',
        'confirmedDate' => 'datetime',
        'RollLevForApp_curr' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'isTempBank' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function currency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'accountCurrencyID','currencyID');
    }
}
