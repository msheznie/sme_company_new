<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PurchaseOrderMaster
 * @package App\Models
 * @version August 11, 2024, 7:01 pm +04
 *
 * @property \Illuminate\Database\Eloquent\Collection $erpPurchaseorderdetails
 * @property \Illuminate\Database\Eloquent\Collection $poWisePaymentTermConfigs
 * @property integer $poProcessId
 * @property integer $companySystemID
 * @property string $companyID
 * @property string $departmentID
 * @property integer $orderType
 * @property integer $amended
 * @property integer $projectID
 * @property integer $serviceLineSystemID
 * @property string $serviceLine
 * @property string $companyAddress
 * @property integer $documentSystemID
 * @property string $documentID
 * @property string $purchaseOrderCode
 * @property integer $serialNumber
 * @property integer $supplierID
 * @property string $supplierPrimaryCode
 * @property string $supplierName
 * @property string $supplierAddress
 * @property string $supplierTelephone
 * @property string $supplierFax
 * @property string $supplierEmail
 * @property integer $creditPeriod
 * @property string|\Carbon\Carbon $expectedDeliveryDate
 * @property string $narration
 * @property integer $poLocation
 * @property integer $financeCategory
 * @property string $referenceNumber
 * @property integer $shippingAddressID
 * @property string $shippingAddressDescriprion
 * @property integer $invoiceToAddressID
 * @property string $invoiceToAddressDescription
 * @property integer $soldToAddressID
 * @property string $soldToAddressDescriprion
 * @property string $paymentTerms
 * @property string $deliveryTerms
 * @property string $panaltyTerms
 * @property integer $localCurrencyID
 * @property number $localCurrencyER
 * @property integer $companyReportingCurrencyID
 * @property number $companyReportingER
 * @property integer $supplierDefaultCurrencyID
 * @property number $supplierDefaultER
 * @property integer $supplierTransactionCurrencyID
 * @property number $supplierTransactionER
 * @property integer $poConfirmedYN
 * @property integer $poConfirmedByEmpSystemID
 * @property string $poConfirmedByEmpID
 * @property string $poConfirmedByName
 * @property string|\Carbon\Carbon $poConfirmedDate
 * @property integer $poCancelledYN
 * @property integer $poCancelledBySystemID
 * @property string $poCancelledBy
 * @property string $poCancelledByName
 * @property string|\Carbon\Carbon $poCancelledDate
 * @property string $cancelledComments
 * @property number $poTotalComRptCurrency
 * @property number $poTotalLocalCurrency
 * @property number $poTotalSupplierDefaultCurrency
 * @property number $poTotalSupplierTransactionCurrency
 * @property number $poDiscountPercentage
 * @property number $poDiscountAmount
 * @property integer $supplierVATEligible
 * @property number $VATPercentage
 * @property number $VATAmount
 * @property number $VATAmountLocal
 * @property number $VATAmountRpt
 * @property string $shipTocontactPersonID
 * @property string $shipTocontactPersonTelephone
 * @property string $shipTocontactPersonFaxNo
 * @property string $shipTocontactPersonEmail
 * @property string $invoiceTocontactPersonID
 * @property string $invoiceTocontactPersonTelephone
 * @property string $invoiceTocontactPersonFaxNo
 * @property string $invoiceTocontactPersonEmail
 * @property string $soldTocontactPersonID
 * @property string $soldTocontactPersonTelephone
 * @property string $soldTocontactPersonFaxNo
 * @property string $soldTocontactPersonEmail
 * @property integer $priority
 * @property integer $approved
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedByUserID
 * @property integer $approvedByUserSystemID
 * @property string $approval_remarks
 * @property number $addOnPercent
 * @property number $addOnDefaultPercent
 * @property integer $GRVTrackingID
 * @property integer $logisticDoneYN
 * @property integer $poClosedYN
 * @property integer $grvRecieved
 * @property integer $invoicedBooked
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property string $poType
 * @property integer $poType_N
 * @property string $docRefNo
 * @property integer $RollLevForApp_curr
 * @property integer $sentToSupplier
 * @property integer $sentToSupplierByEmpSystemID
 * @property string $sentToSupplierByEmpID
 * @property string $sentToSupplierByEmpName
 * @property string|\Carbon\Carbon $sentToSupplierDate
 * @property integer $budgetBlockYN
 * @property integer $budgetYear
 * @property integer $hidePOYN
 * @property integer $hideByEmpSystemID
 * @property string $hideByEmpID
 * @property string $hideByEmpName
 * @property string|\Carbon\Carbon $hideDate
 * @property string $hideComments
 * @property integer $WO_purchaseOrderID
 * @property string|\Carbon\Carbon $WO_PeriodFrom
 * @property string|\Carbon\Carbon $WO_PeriodTo
 * @property integer $WO_NoOfAutoGenerationTimes
 * @property integer $WO_NoOfGeneratedTimes
 * @property integer $WO_fullyGenerated
 * @property integer $WO_amendYN
 * @property string|\Carbon\Carbon $WO_amendRequestedDate
 * @property integer $WO_amendRequestedByEmpSystemID
 * @property string $WO_amendRequestedByEmpID
 * @property integer $WO_confirmedYN
 * @property string|\Carbon\Carbon $WO_confirmedDate
 * @property string $WO_confirmedByEmpID
 * @property integer $WO_terminateYN
 * @property string|\Carbon\Carbon $WO_terminatedDate
 * @property string $WO_terminatedByEmpID
 * @property string $WO_terminateComments
 * @property integer $partiallyGRVAllowed
 * @property integer $logisticsAvailable
 * @property integer $vatRegisteredYN
 * @property integer $manuallyClosed
 * @property integer $manuallyClosedByEmpSystemID
 * @property string $manuallyClosedByEmpID
 * @property string $manuallyClosedByEmpName
 * @property string|\Carbon\Carbon $manuallyClosedDate
 * @property string $manuallyClosedComment
 * @property integer $supCategoryICVMasterID
 * @property integer $supCategorySubICVID
 * @property string $createdUserGroup
 * @property string $createdPcID
 * @property integer $createdUserSystemID
 * @property string $createdUserID
 * @property string $modifiedPc
 * @property integer $modifiedUserSystemID
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $createdDateTime
 * @property boolean $isSelected
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $allocateItemToSegment
 * @property integer $workOrderGenerateID
 * @property integer $rcmActivated
 * @property integer $poTypeID
 * @property integer $categoryID
 * @property integer $upload_job_status
 * @property integer $isBulkItemJobRun
 * @property string $vat_number
 */
class PurchaseOrderMaster extends Model
{

    use HasFactory;

    public $table = 'erp_purchaseordermaster';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'poProcessId',
        'companySystemID',
        'companyID',
        'departmentID',
        'orderType',
        'amended',
        'projectID',
        'serviceLineSystemID',
        'serviceLine',
        'companyAddress',
        'documentSystemID',
        'documentID',
        'purchaseOrderCode',
        'serialNumber',
        'supplierID',
        'supplierPrimaryCode',
        'supplierName',
        'supplierAddress',
        'supplierTelephone',
        'supplierFax',
        'supplierEmail',
        'creditPeriod',
        'expectedDeliveryDate',
        'narration',
        'poLocation',
        'financeCategory',
        'referenceNumber',
        'shippingAddressID',
        'shippingAddressDescriprion',
        'invoiceToAddressID',
        'invoiceToAddressDescription',
        'soldToAddressID',
        'soldToAddressDescriprion',
        'paymentTerms',
        'deliveryTerms',
        'panaltyTerms',
        'localCurrencyID',
        'localCurrencyER',
        'companyReportingCurrencyID',
        'companyReportingER',
        'supplierDefaultCurrencyID',
        'supplierDefaultER',
        'supplierTransactionCurrencyID',
        'supplierTransactionER',
        'poConfirmedYN',
        'poConfirmedByEmpSystemID',
        'poConfirmedByEmpID',
        'poConfirmedByName',
        'poConfirmedDate',
        'poCancelledYN',
        'poCancelledBySystemID',
        'poCancelledBy',
        'poCancelledByName',
        'poCancelledDate',
        'cancelledComments',
        'poTotalComRptCurrency',
        'poTotalLocalCurrency',
        'poTotalSupplierDefaultCurrency',
        'poTotalSupplierTransactionCurrency',
        'poDiscountPercentage',
        'poDiscountAmount',
        'supplierVATEligible',
        'VATPercentage',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'shipTocontactPersonID',
        'shipTocontactPersonTelephone',
        'shipTocontactPersonFaxNo',
        'shipTocontactPersonEmail',
        'invoiceTocontactPersonID',
        'invoiceTocontactPersonTelephone',
        'invoiceTocontactPersonFaxNo',
        'invoiceTocontactPersonEmail',
        'soldTocontactPersonID',
        'soldTocontactPersonTelephone',
        'soldTocontactPersonFaxNo',
        'soldTocontactPersonEmail',
        'priority',
        'approved',
        'approvedDate',
        'approvedByUserID',
        'approvedByUserSystemID',
        'approval_remarks',
        'addOnPercent',
        'addOnDefaultPercent',
        'GRVTrackingID',
        'logisticDoneYN',
        'poClosedYN',
        'grvRecieved',
        'invoicedBooked',
        'refferedBackYN',
        'timesReferred',
        'poType',
        'poType_N',
        'docRefNo',
        'RollLevForApp_curr',
        'sentToSupplier',
        'sentToSupplierByEmpSystemID',
        'sentToSupplierByEmpID',
        'sentToSupplierByEmpName',
        'sentToSupplierDate',
        'budgetBlockYN',
        'budgetYear',
        'hidePOYN',
        'hideByEmpSystemID',
        'hideByEmpID',
        'hideByEmpName',
        'hideDate',
        'hideComments',
        'WO_purchaseOrderID',
        'WO_PeriodFrom',
        'WO_PeriodTo',
        'WO_NoOfAutoGenerationTimes',
        'WO_NoOfGeneratedTimes',
        'WO_fullyGenerated',
        'WO_amendYN',
        'WO_amendRequestedDate',
        'WO_amendRequestedByEmpSystemID',
        'WO_amendRequestedByEmpID',
        'WO_confirmedYN',
        'WO_confirmedDate',
        'WO_confirmedByEmpID',
        'WO_terminateYN',
        'WO_terminatedDate',
        'WO_terminatedByEmpID',
        'WO_terminateComments',
        'partiallyGRVAllowed',
        'logisticsAvailable',
        'vatRegisteredYN',
        'manuallyClosed',
        'manuallyClosedByEmpSystemID',
        'manuallyClosedByEmpID',
        'manuallyClosedByEmpName',
        'manuallyClosedDate',
        'manuallyClosedComment',
        'supCategoryICVMasterID',
        'supCategorySubICVID',
        'createdUserGroup',
        'createdPcID',
        'createdUserSystemID',
        'createdUserID',
        'modifiedPc',
        'modifiedUserSystemID',
        'modifiedUser',
        'createdDateTime',
        'isSelected',
        'timeStamp',
        'allocateItemToSegment',
        'workOrderGenerateID',
        'rcmActivated',
        'poTypeID',
        'categoryID',
        'upload_job_status',
        'isBulkItemJobRun',
        'vat_number'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'purchaseOrderID' => 'integer',
        'poProcessId' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'departmentID' => 'string',
        'orderType' => 'integer',
        'amended' => 'integer',
        'projectID' => 'integer',
        'serviceLineSystemID' => 'integer',
        'serviceLine' => 'string',
        'companyAddress' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'purchaseOrderCode' => 'string',
        'serialNumber' => 'integer',
        'supplierID' => 'integer',
        'supplierPrimaryCode' => 'string',
        'supplierName' => 'string',
        'supplierAddress' => 'string',
        'supplierTelephone' => 'string',
        'supplierFax' => 'string',
        'supplierEmail' => 'string',
        'creditPeriod' => 'integer',
        'expectedDeliveryDate' => 'datetime',
        'narration' => 'string',
        'poLocation' => 'integer',
        'financeCategory' => 'integer',
        'referenceNumber' => 'string',
        'shippingAddressID' => 'integer',
        'shippingAddressDescriprion' => 'string',
        'invoiceToAddressID' => 'integer',
        'invoiceToAddressDescription' => 'string',
        'soldToAddressID' => 'integer',
        'soldToAddressDescriprion' => 'string',
        'paymentTerms' => 'string',
        'deliveryTerms' => 'string',
        'panaltyTerms' => 'string',
        'localCurrencyID' => 'integer',
        'localCurrencyER' => 'float',
        'companyReportingCurrencyID' => 'integer',
        'companyReportingER' => 'float',
        'supplierDefaultCurrencyID' => 'integer',
        'supplierDefaultER' => 'float',
        'supplierTransactionCurrencyID' => 'integer',
        'supplierTransactionER' => 'float',
        'poConfirmedYN' => 'integer',
        'poConfirmedByEmpSystemID' => 'integer',
        'poConfirmedByEmpID' => 'string',
        'poConfirmedByName' => 'string',
        'poConfirmedDate' => 'datetime',
        'poCancelledYN' => 'integer',
        'poCancelledBySystemID' => 'integer',
        'poCancelledBy' => 'string',
        'poCancelledByName' => 'string',
        'poCancelledDate' => 'datetime',
        'cancelledComments' => 'string',
        'poTotalComRptCurrency' => 'float',
        'poTotalLocalCurrency' => 'float',
        'poTotalSupplierDefaultCurrency' => 'float',
        'poTotalSupplierTransactionCurrency' => 'float',
        'poDiscountPercentage' => 'float',
        'poDiscountAmount' => 'float',
        'supplierVATEligible' => 'integer',
        'VATPercentage' => 'float',
        'VATAmount' => 'float',
        'VATAmountLocal' => 'float',
        'VATAmountRpt' => 'float',
        'shipTocontactPersonID' => 'string',
        'shipTocontactPersonTelephone' => 'string',
        'shipTocontactPersonFaxNo' => 'string',
        'shipTocontactPersonEmail' => 'string',
        'invoiceTocontactPersonID' => 'string',
        'invoiceTocontactPersonTelephone' => 'string',
        'invoiceTocontactPersonFaxNo' => 'string',
        'invoiceTocontactPersonEmail' => 'string',
        'soldTocontactPersonID' => 'string',
        'soldTocontactPersonTelephone' => 'string',
        'soldTocontactPersonFaxNo' => 'string',
        'soldTocontactPersonEmail' => 'string',
        'priority' => 'integer',
        'approved' => 'integer',
        'approvedDate' => 'datetime',
        'approvedByUserID' => 'string',
        'approvedByUserSystemID' => 'integer',
        'approval_remarks' => 'string',
        'addOnPercent' => 'float',
        'addOnDefaultPercent' => 'float',
        'GRVTrackingID' => 'integer',
        'logisticDoneYN' => 'integer',
        'poClosedYN' => 'integer',
        'grvRecieved' => 'integer',
        'invoicedBooked' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'poType' => 'string',
        'poType_N' => 'integer',
        'docRefNo' => 'string',
        'RollLevForApp_curr' => 'integer',
        'sentToSupplier' => 'integer',
        'sentToSupplierByEmpSystemID' => 'integer',
        'sentToSupplierByEmpID' => 'string',
        'sentToSupplierByEmpName' => 'string',
        'sentToSupplierDate' => 'datetime',
        'budgetBlockYN' => 'integer',
        'budgetYear' => 'integer',
        'hidePOYN' => 'integer',
        'hideByEmpSystemID' => 'integer',
        'hideByEmpID' => 'string',
        'hideByEmpName' => 'string',
        'hideDate' => 'datetime',
        'hideComments' => 'string',
        'WO_purchaseOrderID' => 'integer',
        'WO_PeriodFrom' => 'datetime',
        'WO_PeriodTo' => 'datetime',
        'WO_NoOfAutoGenerationTimes' => 'integer',
        'WO_NoOfGeneratedTimes' => 'integer',
        'WO_fullyGenerated' => 'integer',
        'WO_amendYN' => 'integer',
        'WO_amendRequestedDate' => 'datetime',
        'WO_amendRequestedByEmpSystemID' => 'integer',
        'WO_amendRequestedByEmpID' => 'string',
        'WO_confirmedYN' => 'integer',
        'WO_confirmedDate' => 'datetime',
        'WO_confirmedByEmpID' => 'string',
        'WO_terminateYN' => 'integer',
        'WO_terminatedDate' => 'datetime',
        'WO_terminatedByEmpID' => 'string',
        'WO_terminateComments' => 'string',
        'partiallyGRVAllowed' => 'integer',
        'logisticsAvailable' => 'integer',
        'vatRegisteredYN' => 'integer',
        'manuallyClosed' => 'integer',
        'manuallyClosedByEmpSystemID' => 'integer',
        'manuallyClosedByEmpID' => 'string',
        'manuallyClosedByEmpName' => 'string',
        'manuallyClosedDate' => 'datetime',
        'manuallyClosedComment' => 'string',
        'supCategoryICVMasterID' => 'integer',
        'supCategorySubICVID' => 'integer',
        'createdUserGroup' => 'string',
        'createdPcID' => 'string',
        'createdUserSystemID' => 'integer',
        'createdUserID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUserSystemID' => 'integer',
        'modifiedUser' => 'string',
        'createdDateTime' => 'datetime',
        'isSelected' => 'boolean',
        'timeStamp' => 'datetime',
        'allocateItemToSegment' => 'integer',
        'workOrderGenerateID' => 'integer',
        'rcmActivated' => 'integer',
        'poTypeID' => 'integer',
        'categoryID' => 'integer',
        'upload_job_status' => 'integer',
        'isBulkItemJobRun' => 'integer',
        'vat_number' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function erpPurchaseorderdetails()
    {
        return $this->hasMany(\App\Models\ErpPurchaseorderdetail::class, 'purchaseOrderMasterID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function poWisePaymentTermConfigs()
    {
        return $this->hasMany(\App\Models\PoWisePaymentTermConfig::class, 'purchaseOrderID');
    }

    public function purchaseOrderDetails()
    {
        return $this->belongsTo(PurchaseOrderDetail::class, 'purchaseOrderID', 'purchaseOrderMasterID');
    }

    public function currency()
    {
        return $this->belongsTo(CurrencyMaster::class, 'supplierTransactionCurrencyID', 'currencyID');
    }

    public static function getPurchaseOrder($contractID, $companyId)
    {
        return PurchaseOrderMaster::select('purchaseOrderID', 'purchaseOrderCode', 'poTotalSupplierTransactionCurrency',
            'poConfirmedYN', 'approved', 'refferedBackYN', 'createdDateTime', 'supplierTransactionCurrencyID')
            ->where('companySystemID', $companyId)
            ->whereHas('purchaseOrderDetails', function ($q) use ($contractID)
            {
                $q->where('contractID', $contractID);
            })
            ->with(['purchaseOrderDetails' => function ($q) use ($contractID)
            {
                $q->select('purchaseOrderDetailsID', 'purchaseOrderMasterID')
                ->where('contractID', $contractID);
            },'currency' => function ($q)
            {
                $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
            }
            ])
            ->get();
    }
}
