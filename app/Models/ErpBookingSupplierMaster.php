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
    public $table = 'erp_bookinvsuppmaster';

    const CREATED_AT = 'createdDateAndTime';
    const UPDATED_AT = 'timestamp';

    protected $primaryKey = 'bookingSuppMasInvAutoID';

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

    ];

    public function currency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'supplierTransactionCurrencyID', 'currencyID');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'companySystemID', 'companySystemID');
    }

    public function directDetail()
    {
        return $this->hasMany(ErpDirectInvoiceDetails::class, 'directInvoiceAutoID', 'bookingSuppMasInvAutoID');
    }
    public function supplier()
    {
        return $this->belongsTo(SupplierMaster::class, 'supplierID', 'supplierCodeSystem');
    }
    public function approvedBy()
    {
        return $this->hasMany(ErpDocumentApproved::class, 'documentSystemCode', 'bookingSuppMasInvAutoID');
    }
    public function confirmedBy()
    {
        return $this->belongsTo(Employees::class, 'confirmedByEmpSystemID', 'employeeSystemID');
    }
    public function transactionCurrency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'supplierTransactionCurrencyID', 'currencyID');
    }

    public function localCurrency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'localCurrencyID', 'currencyID');
    }

    public function rptCurrency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'companyReportingCurrencyID', 'currencyID');
    }

    public function grvDetail()
    {
        return $this->hasMany(BookInvSuppDet::class, 'bookingSuppMasInvAutoID', 'bookingSuppMasInvAutoID');
    }
    public function detail()
    {
        return $this->hasMany(BookInvSuppDet::class, 'bookingSuppMasInvAutoID', 'bookingSuppMasInvAutoID');
    }
    public function supplierGrv()
    {
        return $this->belongsTo(ChartOfAccount::class, 'supplierGLCodeSystemID', 'chartOfAccountSystemID');
    }
    public function checkSupplierInvoiceMasterExists($id, $selectedCompanyId)
    {
        return ErpBookingSupplierMaster::select('bookingSuppMasInvAutoID', 'documentSystemID', 'bookingInvCode')
            ->where('bookingSuppMasInvAutoID', $id)
            ->where('companySystemID', $selectedCompanyId)
            ->first();
    }
    public static function getInvoicesForFilters($directInvoiceIds, $selectedCompanyID)
    {
        return ErpBookingSupplierMaster::whereIn('bookingSuppMasInvAutoID', $directInvoiceIds)
            ->where('companySystemID', $selectedCompanyID)
            ->select('bookingSuppMasInvAutoID', 'bookingInvCode')
            ->get();
    }
    public function getInvoiceMasterDetails($invoiceID)
    {
        return ErpBookingSupplierMaster::where('bookingSuppMasInvAutoID', $invoiceID)
            ->select('bookingSuppMasInvAutoID', 'documentSystemID', 'documentID', 'projectID', 'bookingInvCode',
                'bookingDate', 'comments', 'secondaryRefNo', 'supplierID', 'supplierGLCodeSystemID', 'supplierGLCode',
                'supplierTransactionCurrencyID', 'supplierInvoiceNo', 'companyReportingCurrencyID', 'localCurrencyID',
                'bookingAmountTrans', 'supplierInvoiceDate', 'bookingAmountLocal', 'bookingAmountRpt', 'confirmedYN',
                'confirmedDate', 'approved', 'approvedDate', 'documentType', 'rcmActivated', 'VATAmount',
                'retentionPercentage', 'netAmount', 'VATPercentage', 'serviceLineSystemID', 'companySystemID',
                'confirmedByEmpSystemID')
            ->with([
                'detail' => function ($q)
                {
                    $q->select('bookingSuppMasInvAutoID', 'totTransactionAmount', 'totLocalAmount');
                },
                'grvDetail' => function ($q)
                {
                    $q->select('bookingSuppMasInvAutoID', 'totRptAmount', 'totLocalAmount', 'grvAutoID')
                        ->with([
                            'grvMaster' => function ($q)
                            {
                                $q->select('grvAutoID', 'grvPrimaryCode', 'grvDate', 'grvNarration');
                            }
                        ]);
                },
                'supplierGrv' => function ($q)
                {
                    $q->select('chartOfAccountSystemID', 'AccountCode', 'catogaryBLorPL', 'AccountDescription');
                },
                'directDetail' => function ($q)
                {
                    $q->select('directInvoiceAutoID', 'glCode', 'glCodeDes', 'serviceLineCode', 'DIAmount', 'VATAmount',
                    'netAmount', 'serviceLineSystemID', 'detail_project_id')
                        ->with([
                        'project' => function ($q)
                        {
                            $q->select('id', 'projectCode', 'description');
                        },
                        'segment' => function ($q)
                        {
                            $q->select('serviceLineSystemID');
                        }
                    ]);
                },
                'company' => function ($q)
                {
                    $q->select('companySystemID', 'CompanyName', 'logoPath', 'masterCompanySystemIDReorting');
                },
                'supplier' => function ($q)
                {
                    $q->select('supplierCodeSystem', 'primarySupplierCode', 'supplierName');
                },
                'currency' => function ($q)
                {
                    $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                },
                'localCurrency' => function ($q)
                {
                    $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                },
                'rptCurrency' => function ($q)
                {
                    $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                },
                'approvedBy' => function ($q)
                {
                    $q->select('documentSystemCode', 'approvedDate', 'employeeSystemID')
                        ->with([
                        'employee' => function ($q)
                        {
                            $q->select('employeeSystemID', 'empName', 'empFullName');
                        }
                    ])
                        ->where('documentSystemID', 11);
                },
                'confirmedBy' => function ($q)
                {
                    $q->select('employeeSystemID', 'empName', 'empFullName');
                }
            ])
            ->first();
    }
}
