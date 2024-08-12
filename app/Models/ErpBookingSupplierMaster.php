<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpBookingSupplierMaster
 * @package App\Models
 * @version August 10, 2024, 9:14 am +04
 *
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $documentSystemID
 * @property string $documentID
 * @property integer $projectID
 * @property integer $serialNo
 * @property integer $companyFinanceYearID
 * @property string|\Carbon\Carbon $FYBiggin
 * @property string|\Carbon\Carbon $FYEnd
 * @property integer $companyFinancePeriodID
 * @property string|\Carbon\Carbon $FYPeriodDateFrom
 * @property string|\Carbon\Carbon $FYPeriodDateTo
 * @property string $bookingInvCode
 * @property string|\Carbon\Carbon $bookingDate
 * @property string $comments
 * @property string $secondaryRefNo
 * @property integer $supplierID
 * @property integer $supplierGLCodeSystemID
 * @property string $supplierGLCode
 * @property integer $UnbilledGRVAccountSystemID
 * @property string $UnbilledGRVAccount
 * @property string $supplierInvoiceNo
 * @property string|\Carbon\Carbon $supplierInvoiceDate
 * @property integer $custInvoiceDirectAutoID
 * @property integer $supplierTransactionCurrencyID
 * @property number $supplierTransactionCurrencyER
 * @property integer $companyReportingCurrencyID
 * @property number $companyReportingER
 * @property integer $localCurrencyID
 * @property number $localCurrencyER
 * @property number $bookingAmountTrans
 * @property number $bookingAmountLocal
 * @property number $bookingAmountRpt
 * @property integer $confirmedYN
 * @property integer $confirmedByEmpSystemID
 * @property string $confirmedByEmpID
 * @property string $confirmedByName
 * @property string|\Carbon\Carbon $confirmedDate
 * @property integer $approved
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedByUserID
 * @property integer $approvedByUserSystemID
 * @property string|\Carbon\Carbon $postedDate
 * @property integer $documentType
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property integer $RollLevForApp_curr
 * @property integer $interCompanyTransferYN
 * @property string $createdUserGroup
 * @property integer $createdUserSystemID
 * @property string $createdUserID
 * @property string $createdPcID
 * @property integer $modifiedUserSystemID
 * @property string $modifiedUser
 * @property string $modifiedPc
 * @property string $createdDateTime
 * @property string|\Carbon\Carbon $createdDateAndTime
 * @property integer $cancelYN
 * @property string $cancelComment
 * @property string|\Carbon\Carbon $cancelDate
 * @property integer $canceledByEmpSystemID
 * @property string $canceledByEmpID
 * @property string $canceledByEmpName
 * @property string|\Carbon\Carbon $timestamp
 * @property integer $rcmActivated
 * @property integer $vatRegisteredYN
 * @property integer $isLocalSupplier
 * @property number $VATAmount
 * @property number $VATAmountLocal
 * @property number $VATAmountRpt
 * @property number $retentionVatAmount
 * @property string $retentionDueDate
 * @property number $retentionAmount
 * @property number $retentionPercentage
 * @property number $netAmount
 * @property number $netAmountLocal
 * @property number $netAmountRpt
 * @property number $VATPercentage
 * @property integer $serviceLineSystemID
 * @property integer $wareHouseSystemCode
 * @property integer $supplierVATEligible
 * @property integer $employeeID
 * @property integer $employeeControlAcID
 * @property integer $createMonthlyDeduction
 * @property integer $deliveryAppoinmentID
 * @property boolean $whtApplicableYN
 * @property integer $whtType
 * @property boolean $whtApplicable
 * @property number $whtAmount
 * @property boolean $whtEdited
 * @property integer $whtPercentage
 * @property boolean $isWHTApplicableVat
 */
class ErpBookingSupplierMaster extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'erp_bookinvsuppmaster';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'companySystemID',
        'companyID',
        'documentSystemID',
        'documentID',
        'projectID',
        'serialNo',
        'companyFinanceYearID',
        'FYBiggin',
        'FYEnd',
        'companyFinancePeriodID',
        'FYPeriodDateFrom',
        'FYPeriodDateTo',
        'bookingInvCode',
        'bookingDate',
        'comments',
        'secondaryRefNo',
        'supplierID',
        'supplierGLCodeSystemID',
        'supplierGLCode',
        'UnbilledGRVAccountSystemID',
        'UnbilledGRVAccount',
        'supplierInvoiceNo',
        'supplierInvoiceDate',
        'custInvoiceDirectAutoID',
        'supplierTransactionCurrencyID',
        'supplierTransactionCurrencyER',
        'companyReportingCurrencyID',
        'companyReportingER',
        'localCurrencyID',
        'localCurrencyER',
        'bookingAmountTrans',
        'bookingAmountLocal',
        'bookingAmountRpt',
        'confirmedYN',
        'confirmedByEmpSystemID',
        'confirmedByEmpID',
        'confirmedByName',
        'confirmedDate',
        'approved',
        'approvedDate',
        'approvedByUserID',
        'approvedByUserSystemID',
        'postedDate',
        'documentType',
        'refferedBackYN',
        'timesReferred',
        'RollLevForApp_curr',
        'interCompanyTransferYN',
        'createdUserGroup',
        'createdUserSystemID',
        'createdUserID',
        'createdPcID',
        'modifiedUserSystemID',
        'modifiedUser',
        'modifiedPc',
        'createdDateTime',
        'createdDateAndTime',
        'cancelYN',
        'cancelComment',
        'cancelDate',
        'canceledByEmpSystemID',
        'canceledByEmpID',
        'canceledByEmpName',
        'timestamp',
        'rcmActivated',
        'vatRegisteredYN',
        'isLocalSupplier',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'retentionVatAmount',
        'retentionDueDate',
        'retentionAmount',
        'retentionPercentage',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'VATPercentage',
        'serviceLineSystemID',
        'wareHouseSystemCode',
        'supplierVATEligible',
        'employeeID',
        'employeeControlAcID',
        'createMonthlyDeduction',
        'deliveryAppoinmentID',
        'whtApplicableYN',
        'whtType',
        'whtApplicable',
        'whtAmount',
        'whtEdited',
        'whtPercentage',
        'isWHTApplicableVat'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'bookingSuppMasInvAutoID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'projectID' => 'integer',
        'serialNo' => 'integer',
        'companyFinanceYearID' => 'integer',
        'FYBiggin' => 'datetime',
        'FYEnd' => 'datetime',
        'companyFinancePeriodID' => 'integer',
        'FYPeriodDateFrom' => 'datetime',
        'FYPeriodDateTo' => 'datetime',
        'bookingInvCode' => 'string',
        'bookingDate' => 'datetime',
        'comments' => 'string',
        'secondaryRefNo' => 'string',
        'supplierID' => 'integer',
        'supplierGLCodeSystemID' => 'integer',
        'supplierGLCode' => 'string',
        'UnbilledGRVAccountSystemID' => 'integer',
        'UnbilledGRVAccount' => 'string',
        'supplierInvoiceNo' => 'string',
        'supplierInvoiceDate' => 'datetime',
        'custInvoiceDirectAutoID' => 'integer',
        'supplierTransactionCurrencyID' => 'integer',
        'supplierTransactionCurrencyER' => 'float',
        'companyReportingCurrencyID' => 'integer',
        'companyReportingER' => 'float',
        'localCurrencyID' => 'integer',
        'localCurrencyER' => 'float',
        'bookingAmountTrans' => 'float',
        'bookingAmountLocal' => 'float',
        'bookingAmountRpt' => 'float',
        'confirmedYN' => 'integer',
        'confirmedByEmpSystemID' => 'integer',
        'confirmedByEmpID' => 'string',
        'confirmedByName' => 'string',
        'confirmedDate' => 'datetime',
        'approved' => 'integer',
        'approvedDate' => 'datetime',
        'approvedByUserID' => 'string',
        'approvedByUserSystemID' => 'integer',
        'postedDate' => 'datetime',
        'documentType' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'RollLevForApp_curr' => 'integer',
        'interCompanyTransferYN' => 'integer',
        'createdUserGroup' => 'string',
        'createdUserSystemID' => 'integer',
        'createdUserID' => 'string',
        'createdPcID' => 'string',
        'modifiedUserSystemID' => 'integer',
        'modifiedUser' => 'string',
        'modifiedPc' => 'string',
        'createdDateTime' => 'string',
        'createdDateAndTime' => 'datetime',
        'cancelYN' => 'integer',
        'cancelComment' => 'string',
        'cancelDate' => 'datetime',
        'canceledByEmpSystemID' => 'integer',
        'canceledByEmpID' => 'string',
        'canceledByEmpName' => 'string',
        'timestamp' => 'datetime',
        'rcmActivated' => 'integer',
        'vatRegisteredYN' => 'integer',
        'isLocalSupplier' => 'integer',
        'VATAmount' => 'float',
        'VATAmountLocal' => 'float',
        'VATAmountRpt' => 'float',
        'retentionVatAmount' => 'float',
        'retentionDueDate' => 'date',
        'retentionAmount' => 'float',
        'retentionPercentage' => 'float',
        'netAmount' => 'float',
        'netAmountLocal' => 'float',
        'netAmountRpt' => 'float',
        'VATPercentage' => 'float',
        'serviceLineSystemID' => 'integer',
        'wareHouseSystemCode' => 'integer',
        'supplierVATEligible' => 'integer',
        'employeeID' => 'integer',
        'employeeControlAcID' => 'integer',
        'createMonthlyDeduction' => 'integer',
        'deliveryAppoinmentID' => 'integer',
        'whtApplicableYN' => 'boolean',
        'whtType' => 'integer',
        'whtApplicable' => 'boolean',
        'whtAmount' => 'float',
        'whtEdited' => 'boolean',
        'whtPercentage' => 'integer',
        'isWHTApplicableVat' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'companySystemID' => 'nullable|integer',
        'companyID' => 'nullable|string|max:50',
        'documentSystemID' => 'nullable|integer',
        'documentID' => 'nullable|string|max:45',
        'projectID' => 'nullable|integer',
        'serialNo' => 'nullable|integer',
        'companyFinanceYearID' => 'nullable|integer',
        'FYBiggin' => 'nullable',
        'FYEnd' => 'nullable',
        'companyFinancePeriodID' => 'nullable|integer',
        'FYPeriodDateFrom' => 'nullable',
        'FYPeriodDateTo' => 'nullable',
        'bookingInvCode' => 'nullable|string|max:100',
        'bookingDate' => 'nullable',
        'comments' => 'nullable|string',
        'secondaryRefNo' => 'nullable|string|max:300',
        'supplierID' => 'nullable|integer',
        'supplierGLCodeSystemID' => 'nullable|integer',
        'supplierGLCode' => 'nullable|string|max:100',
        'UnbilledGRVAccountSystemID' => 'nullable|integer',
        'UnbilledGRVAccount' => 'nullable|string|max:20',
        'supplierInvoiceNo' => 'nullable|string|max:200',
        'supplierInvoiceDate' => 'nullable',
        'custInvoiceDirectAutoID' => 'nullable|integer',
        'supplierTransactionCurrencyID' => 'nullable|integer',
        'supplierTransactionCurrencyER' => 'nullable|numeric',
        'companyReportingCurrencyID' => 'nullable|integer',
        'companyReportingER' => 'nullable|numeric',
        'localCurrencyID' => 'nullable|integer',
        'localCurrencyER' => 'nullable|numeric',
        'bookingAmountTrans' => 'nullable|numeric',
        'bookingAmountLocal' => 'nullable|numeric',
        'bookingAmountRpt' => 'nullable|numeric',
        'confirmedYN' => 'nullable|integer',
        'confirmedByEmpSystemID' => 'nullable|integer',
        'confirmedByEmpID' => 'nullable|string|max:100',
        'confirmedByName' => 'nullable|string|max:500',
        'confirmedDate' => 'nullable',
        'approved' => 'nullable|integer',
        'approvedDate' => 'nullable',
        'approvedByUserID' => 'nullable|string|max:30',
        'approvedByUserSystemID' => 'nullable|integer',
        'postedDate' => 'nullable',
        'documentType' => 'nullable|integer',
        'refferedBackYN' => 'nullable|integer',
        'timesReferred' => 'nullable|integer',
        'RollLevForApp_curr' => 'nullable|integer',
        'interCompanyTransferYN' => 'nullable|integer',
        'createdUserGroup' => 'nullable|string|max:100',
        'createdUserSystemID' => 'nullable|integer',
        'createdUserID' => 'nullable|string|max:100',
        'createdPcID' => 'nullable|string|max:100',
        'modifiedUserSystemID' => 'nullable|integer',
        'modifiedUser' => 'nullable|string|max:100',
        'modifiedPc' => 'nullable|string|max:100',
        'createdDateTime' => 'nullable|string|max:100',
        'createdDateAndTime' => 'nullable',
        'cancelYN' => 'nullable|integer',
        'cancelComment' => 'nullable|string',
        'cancelDate' => 'nullable',
        'canceledByEmpSystemID' => 'nullable|integer',
        'canceledByEmpID' => 'nullable|string|max:100',
        'canceledByEmpName' => 'nullable|string|max:500',
        'timestamp' => 'nullable',
        'rcmActivated' => 'nullable|integer',
        'vatRegisteredYN' => 'nullable|integer',
        'isLocalSupplier' => 'nullable|integer',
        'VATAmount' => 'nullable|numeric',
        'VATAmountLocal' => 'nullable|numeric',
        'VATAmountRpt' => 'nullable|numeric',
        'retentionVatAmount' => 'required|numeric',
        'retentionDueDate' => 'nullable',
        'retentionAmount' => 'required|numeric',
        'retentionPercentage' => 'required|numeric',
        'netAmount' => 'nullable|numeric',
        'netAmountLocal' => 'nullable|numeric',
        'netAmountRpt' => 'nullable|numeric',
        'VATPercentage' => 'nullable|numeric',
        'serviceLineSystemID' => 'nullable|integer',
        'wareHouseSystemCode' => 'nullable|integer',
        'supplierVATEligible' => 'nullable|integer',
        'employeeID' => 'nullable|integer',
        'employeeControlAcID' => 'nullable|integer',
        'createMonthlyDeduction' => 'nullable|integer',
        'deliveryAppoinmentID' => 'nullable|integer',
        'whtApplicableYN' => 'required|boolean',
        'whtType' => 'required|integer',
        'whtApplicable' => 'required|boolean',
        'whtAmount' => 'nullable|numeric',
        'whtEdited' => 'required|boolean',
        'whtPercentage' => 'required|integer',
        'isWHTApplicableVat' => 'required|boolean'
    ];

    
}
