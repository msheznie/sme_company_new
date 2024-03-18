<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CustomerMaster
 * @package App\Models
 * @version March 8, 2024, 4:54 pm +04
 *
 * @property integer $primaryCompanySystemID
 * @property string $primaryCompanyID
 * @property integer $documentSystemID
 * @property string $documentID
 * @property integer $lastSerialOrder
 * @property string $CutomerCode
 * @property string $customerShortCode
 * @property integer $customerCategoryID
 * @property integer $custGLAccountSystemID
 * @property string $custGLaccount
 * @property integer $custUnbilledAccountSystemID
 * @property string $custUnbilledAccount
 * @property string $CustomerName
 * @property string $customerSecondLanguage
 * @property string $ReportTitle
 * @property string $reportTitleSecondLanguage
 * @property string $customerAddress1
 * @property string $addressOneSecondLanguage
 * @property string $customerAddress2
 * @property string $addressTwoSecondLanguage
 * @property string $customerCity
 * @property string $customerCountry
 * @property string $CustWebsite
 * @property number $creditLimit
 * @property integer $creditDays
 * @property string $customerLogo
 * @property integer $interCompanyYN
 * @property integer $companyLinkedToSystemID
 * @property string $companyLinkedTo
 * @property integer $isCustomerActive
 * @property integer $isAllowedQHSE
 * @property string $vendorCode
 * @property integer $vatEligible
 * @property string $vatNumber
 * @property integer $vatPercentage
 * @property integer $isSupplierForiegn
 * @property integer $approvedYN
 * @property integer $approvedEmpSystemID
 * @property string $approvedEmpID
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedComment
 * @property integer $confirmedYN
 * @property integer $confirmedEmpSystemID
 * @property string $confirmedEmpID
 * @property string $confirmedEmpName
 * @property string|\Carbon\Carbon $confirmedDate
 * @property integer $RollLevForApp_curr
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property string $createdUserGroup
 * @property string $createdUserID
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string $createdPcID
 * @property string $modifiedPc
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $createdFrom
 * @property string $consignee_name
 * @property string $consignee_address
 * @property string $payment_terms
 * @property string $consignee_contact_no
 * @property string $customer_registration_no
 * @property string $customer_registration_expiry_date
 * @property integer $custAdvanceAccountSystemID
 * @property string $custAdvanceAccount
 */
class CustomerMaster extends Model
{

    public $table = 'customermaster';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'primaryCompanySystemID',
        'primaryCompanyID',
        'documentSystemID',
        'documentID',
        'lastSerialOrder',
        'CutomerCode',
        'customerShortCode',
        'customerCategoryID',
        'custGLAccountSystemID',
        'custGLaccount',
        'custUnbilledAccountSystemID',
        'custUnbilledAccount',
        'CustomerName',
        'customerSecondLanguage',
        'ReportTitle',
        'reportTitleSecondLanguage',
        'customerAddress1',
        'addressOneSecondLanguage',
        'customerAddress2',
        'addressTwoSecondLanguage',
        'customerCity',
        'customerCountry',
        'CustWebsite',
        'creditLimit',
        'creditDays',
        'customerLogo',
        'interCompanyYN',
        'companyLinkedToSystemID',
        'companyLinkedTo',
        'isCustomerActive',
        'isAllowedQHSE',
        'vendorCode',
        'vatEligible',
        'vatNumber',
        'vatPercentage',
        'isSupplierForiegn',
        'approvedYN',
        'approvedEmpSystemID',
        'approvedEmpID',
        'approvedDate',
        'approvedComment',
        'confirmedYN',
        'confirmedEmpSystemID',
        'confirmedEmpID',
        'confirmedEmpName',
        'confirmedDate',
        'RollLevForApp_curr',
        'refferedBackYN',
        'timesReferred',
        'createdUserGroup',
        'createdUserID',
        'createdDateTime',
        'createdPcID',
        'modifiedPc',
        'modifiedUser',
        'timeStamp',
        'createdFrom',
        'consignee_name',
        'consignee_address',
        'payment_terms',
        'consignee_contact_no',
        'customer_registration_no',
        'customer_registration_expiry_date',
        'custAdvanceAccountSystemID',
        'custAdvanceAccount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'customerCodeSystem' => 'integer',
        'primaryCompanySystemID' => 'integer',
        'primaryCompanyID' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'lastSerialOrder' => 'integer',
        'CutomerCode' => 'string',
        'customerShortCode' => 'string',
        'customerCategoryID' => 'integer',
        'custGLAccountSystemID' => 'integer',
        'custGLaccount' => 'string',
        'custUnbilledAccountSystemID' => 'integer',
        'custUnbilledAccount' => 'string',
        'CustomerName' => 'string',
        'customerSecondLanguage' => 'string',
        'ReportTitle' => 'string',
        'reportTitleSecondLanguage' => 'string',
        'customerAddress1' => 'string',
        'addressOneSecondLanguage' => 'string',
        'customerAddress2' => 'string',
        'addressTwoSecondLanguage' => 'string',
        'customerCity' => 'string',
        'customerCountry' => 'string',
        'CustWebsite' => 'string',
        'creditLimit' => 'float',
        'creditDays' => 'integer',
        'customerLogo' => 'string',
        'interCompanyYN' => 'integer',
        'companyLinkedToSystemID' => 'integer',
        'companyLinkedTo' => 'string',
        'isCustomerActive' => 'integer',
        'isAllowedQHSE' => 'integer',
        'vendorCode' => 'string',
        'vatEligible' => 'integer',
        'vatNumber' => 'string',
        'vatPercentage' => 'integer',
        'isSupplierForiegn' => 'integer',
        'approvedYN' => 'integer',
        'approvedEmpSystemID' => 'integer',
        'approvedEmpID' => 'string',
        'approvedDate' => 'datetime',
        'approvedComment' => 'string',
        'confirmedYN' => 'integer',
        'confirmedEmpSystemID' => 'integer',
        'confirmedEmpID' => 'string',
        'confirmedEmpName' => 'string',
        'confirmedDate' => 'datetime',
        'RollLevForApp_curr' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'createdUserGroup' => 'string',
        'createdUserID' => 'string',
        'createdDateTime' => 'datetime',
        'createdPcID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUser' => 'string',
        'timeStamp' => 'datetime',
        'createdFrom' => 'integer',
        'consignee_name' => 'string',
        'consignee_address' => 'string',
        'payment_terms' => 'string',
        'consignee_contact_no' => 'string',
        'customer_registration_no' => 'string',
        'customer_registration_expiry_date' => 'date',
        'custAdvanceAccountSystemID' => 'integer',
        'custAdvanceAccount' => 'string'
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
        'lastSerialOrder' => 'nullable|integer',
        'CutomerCode' => 'nullable|string|max:50',
        'customerShortCode' => 'nullable|string|max:50',
        'customerCategoryID' => 'nullable|integer',
        'custGLAccountSystemID' => 'nullable|integer',
        'custGLaccount' => 'nullable|string|max:45',
        'custUnbilledAccountSystemID' => 'nullable|integer',
        'custUnbilledAccount' => 'nullable|string|max:45',
        'CustomerName' => 'nullable|string',
        'customerSecondLanguage' => 'nullable|string',
        'ReportTitle' => 'nullable|string',
        'reportTitleSecondLanguage' => 'nullable|string',
        'customerAddress1' => 'nullable|string',
        'addressOneSecondLanguage' => 'nullable|string',
        'customerAddress2' => 'nullable|string',
        'addressTwoSecondLanguage' => 'nullable|string',
        'customerCity' => 'nullable|string|max:45',
        'customerCountry' => 'nullable|string|max:45',
        'CustWebsite' => 'nullable|string|max:255',
        'creditLimit' => 'nullable|numeric',
        'creditDays' => 'nullable|integer',
        'customerLogo' => 'nullable|string',
        'interCompanyYN' => 'nullable|integer',
        'companyLinkedToSystemID' => 'nullable|integer',
        'companyLinkedTo' => 'nullable|string|max:45',
        'isCustomerActive' => 'nullable|integer',
        'isAllowedQHSE' => 'nullable|integer',
        'vendorCode' => 'nullable|string|max:255',
        'vatEligible' => 'nullable|integer',
        'vatNumber' => 'nullable|string|max:100',
        'vatPercentage' => 'nullable|integer',
        'isSupplierForiegn' => 'nullable|integer',
        'approvedYN' => 'nullable|integer',
        'approvedEmpSystemID' => 'nullable|integer',
        'approvedEmpID' => 'nullable|string|max:100',
        'approvedDate' => 'nullable',
        'approvedComment' => 'nullable|string',
        'confirmedYN' => 'nullable|integer',
        'confirmedEmpSystemID' => 'nullable|integer',
        'confirmedEmpID' => 'nullable|string|max:20',
        'confirmedEmpName' => 'nullable|string|max:500',
        'confirmedDate' => 'nullable',
        'RollLevForApp_curr' => 'nullable|integer',
        'refferedBackYN' => 'nullable|integer',
        'timesReferred' => 'nullable|integer',
        'createdUserGroup' => 'nullable|string|max:255',
        'createdUserID' => 'nullable|string|max:255',
        'createdDateTime' => 'nullable',
        'createdPcID' => 'nullable|string|max:255',
        'modifiedPc' => 'nullable|string|max:255',
        'modifiedUser' => 'nullable|string|max:255',
        'timeStamp' => 'nullable',
        'createdFrom' => 'nullable|integer',
        'consignee_name' => 'nullable|string|max:255',
        'consignee_address' => 'nullable|string',
        'payment_terms' => 'nullable|string',
        'consignee_contact_no' => 'nullable|string|max:255',
        'customer_registration_no' => 'nullable|string|max:255',
        'customer_registration_expiry_date' => 'nullable',
        'custAdvanceAccountSystemID' => 'nullable|integer',
        'custAdvanceAccount' => 'nullable|string|max:255'
    ];

    public function pulledContractUser(){
        return $this->belongsTo(ContractUsers::class, 'customerCodeSystem', 'contractUserId');
    }
}
