<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PaySpplierInvoiceMaster
 * @package App\Models
 * @version August 11, 2024, 2:22 pm +04
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
 * @property string $BPVcode
 * @property string|\Carbon\Carbon $BPVdate
 * @property integer $BPVbank
 * @property integer $BPVAccount
 * @property integer $BPVchequeNo
 * @property string|\Carbon\Carbon $BPVchequeDate
 * @property string $BPVNarration
 * @property integer $BPVbankCurrency
 * @property number $BPVbankCurrencyER
 * @property integer $directPaymentpayeeYN
 * @property integer $directPaymentPayeeSelectEmp
 * @property string $directPaymentPayeeEmpID
 * @property string $directPaymentPayee
 * @property integer $directPayeeCurrency
 * @property string $directPayeeBankMemo
 * @property integer $BPVsupplierID
 * @property integer $supplierGLCodeSystemID
 * @property string $supplierGLCode
 * @property integer $supplierTransCurrencyID
 * @property number $supplierTransCurrencyER
 * @property integer $supplierDefCurrencyID
 * @property number $supplierDefCurrencyER
 * @property integer $localCurrencyID
 * @property number $localCurrencyER
 * @property integer $companyRptCurrencyID
 * @property number $companyRptCurrencyER
 * @property number $payAmountBank
 * @property number $payAmountSuppTrans
 * @property number $payAmountSuppDef
 * @property number $payAmountCompLocal
 * @property number $payAmountCompRpt
 * @property number $suppAmountDocTotal
 * @property boolean $createMonthlyDeduction
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
 * @property integer $invoiceType
 * @property integer $matchInvoice
 * @property integer $trsCollectedYN
 * @property integer $trsCollectedByEmpSystemID
 * @property string $trsCollectedByEmpID
 * @property string $trsCollectedByEmpName
 * @property string|\Carbon\Carbon $trsCollectedDate
 * @property integer $trsClearedYN
 * @property string|\Carbon\Carbon $trsClearedDate
 * @property integer $trsClearedByEmpSystemID
 * @property string $trsClearedByEmpID
 * @property string $trsClearedByEmpName
 * @property number $trsClearedAmount
 * @property integer $bankClearedYN
 * @property number $bankClearedAmount
 * @property string|\Carbon\Carbon $bankReconciliationDate
 * @property string|\Carbon\Carbon $bankClearedDate
 * @property integer $bankClearedByEmpSystemID
 * @property string $bankClearedByEmpID
 * @property string $bankClearedByEmpName
 * @property integer $chequePaymentYN
 * @property integer $chequePrintedYN
 * @property string|\Carbon\Carbon $chequePrintedDateTime
 * @property integer $chequePrintedByEmpSystemID
 * @property string $chequePrintedByEmpID
 * @property string $chequePrintedByEmpName
 * @property integer $chequeSentToTreasury
 * @property integer $chequeSentToTreasuryByEmpSystemID
 * @property string $chequeSentToTreasuryByEmpID
 * @property string $chequeSentToTreasuryByEmpName
 * @property string|\Carbon\Carbon $chequeSentToTreasuryDate
 * @property integer $chequeReceivedByTreasury
 * @property integer $chequeReceivedByTreasuryByEmpSystemID
 * @property string $chequeReceivedByTreasuryByEmpID
 * @property string $chequeReceivedByTreasuryByEmpName
 * @property string|\Carbon\Carbon $chequeReceivedByTreasuryDate
 * @property integer $timesReferred
 * @property integer $matchingConfirmedYN
 * @property integer $matchingConfirmedByEmpSystemID
 * @property string $matchingConfirmedByEmpID
 * @property string $matchingConfirmedByName
 * @property string|\Carbon\Carbon $matchingConfirmedDate
 * @property integer $refferedBackYN
 * @property integer $RollLevForApp_curr
 * @property integer $noOfApprovalLevels
 * @property integer $isRelatedPartyYN
 * @property integer $advancePaymentTypeID
 * @property integer $isPdcChequeYN
 * @property integer $finalSettlementYN
 * @property integer $partyTblID
 * @property integer $expenseClaimOrPettyCash
 * @property integer $interCompanyToSystemID
 * @property string $interCompanyToID
 * @property integer $ReversedYN
 * @property integer $cancelYN
 * @property string $cancelComment
 * @property string|\Carbon\Carbon $cancelDate
 * @property integer $cancelledByEmpSystemID
 * @property string $canceledByEmpID
 * @property string $canceledByEmpName
 * @property string $createdUserGroup
 * @property integer $createdUserSystemID
 * @property string $createdUserID
 * @property string $createdPcID
 * @property integer $modifiedUserSystemID
 * @property string $modifiedUser
 * @property string $modifiedPc
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string|\Carbon\Carbon $timestamp
 * @property integer $rcmActivated
 * @property number $retentionVatAmount
 * @property number $VATAmount
 * @property number $VATAmountLocal
 * @property number $VATAmountRpt
 * @property number $netAmount
 * @property number $netAmountLocal
 * @property number $netAmountRpt
 * @property number $VATPercentage
 * @property integer $pdcChequeYN
 * @property number $bankAccountBalance
 * @property integer $payment_mode
 * @property integer $advanceAccountSystemID
 * @property string $AdvanceAccount
 * @property number $VATAmountBank
 * @property integer $applyVAT
 */
class PaySpplierInvoiceMaster extends Model
{
    public $table = 'erp_paysupplierinvoicemaster';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timestamp';

    protected $primaryKey  = 'PayMasterAutoId';

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
        'BPVcode',
        'BPVdate',
        'BPVbank',
        'BPVAccount',
        'BPVchequeNo',
        'BPVchequeDate',
        'BPVNarration',
        'BPVbankCurrency',
        'BPVbankCurrencyER',
        'directPaymentpayeeYN',
        'directPaymentPayeeSelectEmp',
        'directPaymentPayeeEmpID',
        'directPaymentPayee',
        'directPayeeCurrency',
        'directPayeeBankMemo',
        'BPVsupplierID',
        'supplierGLCodeSystemID',
        'supplierGLCode',
        'supplierTransCurrencyID',
        'supplierTransCurrencyER',
        'supplierDefCurrencyID',
        'supplierDefCurrencyER',
        'localCurrencyID',
        'localCurrencyER',
        'companyRptCurrencyID',
        'companyRptCurrencyER',
        'payAmountBank',
        'payAmountSuppTrans',
        'payAmountSuppDef',
        'payAmountCompLocal',
        'payAmountCompRpt',
        'suppAmountDocTotal',
        'createMonthlyDeduction',
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
        'invoiceType',
        'matchInvoice',
        'trsCollectedYN',
        'trsCollectedByEmpSystemID',
        'trsCollectedByEmpID',
        'trsCollectedByEmpName',
        'trsCollectedDate',
        'trsClearedYN',
        'trsClearedDate',
        'trsClearedByEmpSystemID',
        'trsClearedByEmpID',
        'trsClearedByEmpName',
        'trsClearedAmount',
        'bankClearedYN',
        'bankClearedAmount',
        'bankReconciliationDate',
        'bankClearedDate',
        'bankClearedByEmpSystemID',
        'bankClearedByEmpID',
        'bankClearedByEmpName',
        'chequePaymentYN',
        'chequePrintedYN',
        'chequePrintedDateTime',
        'chequePrintedByEmpSystemID',
        'chequePrintedByEmpID',
        'chequePrintedByEmpName',
        'chequeSentToTreasury',
        'chequeSentToTreasuryByEmpSystemID',
        'chequeSentToTreasuryByEmpID',
        'chequeSentToTreasuryByEmpName',
        'chequeSentToTreasuryDate',
        'chequeReceivedByTreasury',
        'chequeReceivedByTreasuryByEmpSystemID',
        'chequeReceivedByTreasuryByEmpID',
        'chequeReceivedByTreasuryByEmpName',
        'chequeReceivedByTreasuryDate',
        'timesReferred',
        'matchingConfirmedYN',
        'matchingConfirmedByEmpSystemID',
        'matchingConfirmedByEmpID',
        'matchingConfirmedByName',
        'matchingConfirmedDate',
        'refferedBackYN',
        'RollLevForApp_curr',
        'noOfApprovalLevels',
        'isRelatedPartyYN',
        'advancePaymentTypeID',
        'isPdcChequeYN',
        'finalSettlementYN',
        'partyTblID',
        'expenseClaimOrPettyCash',
        'interCompanyToSystemID',
        'interCompanyToID',
        'ReversedYN',
        'cancelYN',
        'cancelComment',
        'cancelDate',
        'cancelledByEmpSystemID',
        'canceledByEmpID',
        'canceledByEmpName',
        'createdUserGroup',
        'createdUserSystemID',
        'createdUserID',
        'createdPcID',
        'modifiedUserSystemID',
        'modifiedUser',
        'modifiedPc',
        'createdDateTime',
        'timestamp',
        'rcmActivated',
        'retentionVatAmount',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'VATPercentage',
        'pdcChequeYN',
        'bankAccountBalance',
        'payment_mode',
        'advanceAccountSystemID',
        'AdvanceAccount',
        'VATAmountBank',
        'applyVAT'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'PayMasterAutoId' => 'integer',
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
        'BPVcode' => 'string',
        'BPVdate' => 'datetime',
        'BPVbank' => 'integer',
        'BPVAccount' => 'integer',
        'BPVchequeNo' => 'integer',
        'BPVchequeDate' => 'datetime',
        'BPVNarration' => 'string',
        'BPVbankCurrency' => 'integer',
        'BPVbankCurrencyER' => 'float',
        'directPaymentpayeeYN' => 'integer',
        'directPaymentPayeeSelectEmp' => 'integer',
        'directPaymentPayeeEmpID' => 'string',
        'directPaymentPayee' => 'string',
        'directPayeeCurrency' => 'integer',
        'directPayeeBankMemo' => 'string',
        'BPVsupplierID' => 'integer',
        'supplierGLCodeSystemID' => 'integer',
        'supplierGLCode' => 'string',
        'supplierTransCurrencyID' => 'integer',
        'supplierTransCurrencyER' => 'float',
        'supplierDefCurrencyID' => 'integer',
        'supplierDefCurrencyER' => 'float',
        'localCurrencyID' => 'integer',
        'localCurrencyER' => 'float',
        'companyRptCurrencyID' => 'integer',
        'companyRptCurrencyER' => 'float',
        'payAmountBank' => 'float',
        'payAmountSuppTrans' => 'float',
        'payAmountSuppDef' => 'float',
        'payAmountCompLocal' => 'float',
        'payAmountCompRpt' => 'float',
        'suppAmountDocTotal' => 'float',
        'createMonthlyDeduction' => 'boolean',
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
        'invoiceType' => 'integer',
        'matchInvoice' => 'integer',
        'trsCollectedYN' => 'integer',
        'trsCollectedByEmpSystemID' => 'integer',
        'trsCollectedByEmpID' => 'string',
        'trsCollectedByEmpName' => 'string',
        'trsCollectedDate' => 'datetime',
        'trsClearedYN' => 'integer',
        'trsClearedDate' => 'datetime',
        'trsClearedByEmpSystemID' => 'integer',
        'trsClearedByEmpID' => 'string',
        'trsClearedByEmpName' => 'string',
        'trsClearedAmount' => 'float',
        'bankClearedYN' => 'integer',
        'bankClearedAmount' => 'float',
        'bankReconciliationDate' => 'datetime',
        'bankClearedDate' => 'datetime',
        'bankClearedByEmpSystemID' => 'integer',
        'bankClearedByEmpID' => 'string',
        'bankClearedByEmpName' => 'string',
        'chequePaymentYN' => 'integer',
        'chequePrintedYN' => 'integer',
        'chequePrintedDateTime' => 'datetime',
        'chequePrintedByEmpSystemID' => 'integer',
        'chequePrintedByEmpID' => 'string',
        'chequePrintedByEmpName' => 'string',
        'chequeSentToTreasury' => 'integer',
        'chequeSentToTreasuryByEmpSystemID' => 'integer',
        'chequeSentToTreasuryByEmpID' => 'string',
        'chequeSentToTreasuryByEmpName' => 'string',
        'chequeSentToTreasuryDate' => 'datetime',
        'chequeReceivedByTreasury' => 'integer',
        'chequeReceivedByTreasuryByEmpSystemID' => 'integer',
        'chequeReceivedByTreasuryByEmpID' => 'string',
        'chequeReceivedByTreasuryByEmpName' => 'string',
        'chequeReceivedByTreasuryDate' => 'datetime',
        'timesReferred' => 'integer',
        'matchingConfirmedYN' => 'integer',
        'matchingConfirmedByEmpSystemID' => 'integer',
        'matchingConfirmedByEmpID' => 'string',
        'matchingConfirmedByName' => 'string',
        'matchingConfirmedDate' => 'datetime',
        'refferedBackYN' => 'integer',
        'RollLevForApp_curr' => 'integer',
        'noOfApprovalLevels' => 'integer',
        'isRelatedPartyYN' => 'integer',
        'advancePaymentTypeID' => 'integer',
        'isPdcChequeYN' => 'integer',
        'finalSettlementYN' => 'integer',
        'partyTblID' => 'integer',
        'expenseClaimOrPettyCash' => 'integer',
        'interCompanyToSystemID' => 'integer',
        'interCompanyToID' => 'string',
        'ReversedYN' => 'integer',
        'cancelYN' => 'integer',
        'cancelComment' => 'string',
        'cancelDate' => 'datetime',
        'cancelledByEmpSystemID' => 'integer',
        'canceledByEmpID' => 'string',
        'canceledByEmpName' => 'string',
        'createdUserGroup' => 'string',
        'createdUserSystemID' => 'integer',
        'createdUserID' => 'string',
        'createdPcID' => 'string',
        'modifiedUserSystemID' => 'integer',
        'modifiedUser' => 'string',
        'modifiedPc' => 'string',
        'createdDateTime' => 'datetime',
        'timestamp' => 'datetime',
        'rcmActivated' => 'integer',
        'retentionVatAmount' => 'float',
        'VATAmount' => 'float',
        'VATAmountLocal' => 'float',
        'VATAmountRpt' => 'float',
        'netAmount' => 'float',
        'netAmountLocal' => 'float',
        'netAmountRpt' => 'float',
        'VATPercentage' => 'float',
        'pdcChequeYN' => 'integer',
        'bankAccountBalance' => 'float',
        'payment_mode' => 'integer',
        'advanceAccountSystemID' => 'integer',
        'AdvanceAccount' => 'string',
        'VATAmountBank' => 'float',
        'applyVAT' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function checkSupplierPVExists($id, $selectedCompanyId)
    {
        return PaySpplierInvoiceMaster::select('PayMasterAutoId', 'documentSystemID', 'BPVcode')
            ->where('PayMasterAutoId', $id)
            ->where('companySystemID', $selectedCompanyId)
            ->first();
    }
    public static function getPaymentVoucherForFilters($directPVIds, $selectedCompanyID)
    {
        return PaySpplierInvoiceMaster::whereIn('PayMasterAutoId', $directPVIds)
            ->where('companySystemID', $selectedCompanyID)
            ->select('PayMasterAutoId', 'BPVcode')
            ->get();
    }
    public function currency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'directPayeeCurrency', 'currencyID');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'companySystemID', 'companySystemID');
    }
    public function paymentMode()
    {
        return $this->belongsTo(PaymentType::class, 'payment_mode', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(SupplierMaster::class, 'BPVsupplierID', 'supplierCodeSystem');
    }
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'BPVAccount', 'bankAccountAutoID');
    }
    public function directDetail()
    {
        return $this->hasMany(DirectPaymentDetails::class, 'directPaymentAutoID', 'PayMasterAutoId');
    }
    public function localCurrency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'localCurrencyID', 'currencyID');
    }

    public function rptCurrency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'companyRptCurrencyID', 'currencyID');
    }
    public function approvedBy()
    {
        return $this->hasMany(ErpDocumentApproved::class, 'documentSystemCode', 'PayMasterAutoId');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Employees::class, 'confirmedByEmpSystemID', 'employeeSystemID');
    }
    public function bankCurrency()
    {
        return $this->belongsTo(CurrencyMaster::class , 'BPVbankCurrency', 'currencyID');
    }
    public function supplierCurrency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'supplierTransCurrencyID', 'currencyID');
    }
    public function supplierDetail()
    {
        return $this->hasMany(PaySupplierInvoiceDetail::class, 'PayMasterAutoId', 'PayMasterAutoId');
    }
    public function paymentVoucherMaster($paymentVoucherID)
    {
        return PaySpplierInvoiceMaster::select('PayMasterAutoId', 'directPayeeCurrency', 'companySystemID',
            'payment_mode', 'BPVsupplierID', 'BPVAccount', 'localCurrencyID', 'companyRptCurrencyID', 'localCurrencyID',
            'confirmedByEmpSystemID', 'BPVbankCurrency', 'supplierTransCurrencyID', 'BPVcode', 'BPVdate', 'invoiceType',
            'directPaymentPayee', 'BPVNarration', 'BPVchequeNo', 'BPVchequeDate', 'retentionVatAmount')
            ->where('PayMasterAutoId', $paymentVoucherID)
            ->with([
                'company' => function ($q)
                {
                    $q->select('companySystemID', 'CompanyName', 'logoPath', 'masterCompanySystemIDReorting');
                },
                'paymentMode' => function ($q)
                {
                    $q->select('id', 'description');
                },
                'supplierDetail' => function ($q)
                {
                    $q->select('PayMasterAutoId', 'bookingInvDocCode', 'purchaseOrderID', 'supplierInvoiceNo',
                        'supplierInvoiceDate', 'supplierInvoiceAmount', 'supplierPaymentAmount', 'paymentBalancedAmount');
                    $q->with([
                        'poMaster' => function ($q)
                        {
                            $q->select('purchaseOrderID', 'purchaseOrderCode');
                        }
                    ]);
                },
                'supplier' => function ($q)
                {
                    $q->select('supplierCodeSystem', 'primarySupplierCode', 'supplierName');
                },
                'bankAccount' => function ($q)
                {
                    $q->select('bankAccountAutoID', 'bankName', 'AccountNo', 'accountCurrencyID')
                        ->with([
                        'currency' => function ($q)
                        {
                            $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                        }
                    ]);
                },
                'currency' => function ($q)
                {
                    $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                },
                'directDetail' => function ($q)
                {
                    $q->select('directPaymentAutoID', 'serviceLineSystemID', 'serviceLineSystemID', 'DPAmount',
                        'vatAmount', 'localAmount', 'VATAmountLocal', 'comRptAmount', 'VATAmountRpt', 'glCode',
                        'glCodeDes')
                        ->with([
                        'project' => function ($q)
                        {
                            $q->select('id', 'projectCode', 'description');
                        },
                        'segment' => function ($q)
                        {
                            $q->select('serviceLineSystemID', 'ServiceLineDes');
                        }
                    ]);
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
                        ->where('documentSystemID', 4);
                },
                'confirmedBy' => function ($q)
                {
                    $q->select('employeeSystemID', 'empName', 'empFullName');
                }
            ])
            ->first();
    }
}
