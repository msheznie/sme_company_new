<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PurchaseOrderDetail
 * @package App\Models
 * @version August 11, 2024, 7:38 pm +04
 *
 * @property \App\Models\ErpPurchaseordermaster $purchaseordermasterid
 * @property integer $purchaseOrderMasterID
 * @property integer $purchaseProcessDetailID
 * @property integer $POProcessMasterID
 * @property integer $WO_purchaseOrderMasterID
 * @property integer $WP_purchaseOrderDetailsID
 * @property integer $purchaseRequestDetailsID
 * @property integer $purchaseRequestID
 * @property integer $companySystemID
 * @property string $companyID
 * @property string $departmentID
 * @property integer $serviceLineSystemID
 * @property string $serviceLineCode
 * @property integer $madeLocallyYN
 * @property integer $itemCode
 * @property string $itemPrimaryCode
 * @property string $itemDescription
 * @property integer $itemFinanceCategoryID
 * @property integer $itemFinanceCategorySubID
 * @property integer $financeGLcodebBSSystemID
 * @property string $financeGLcodebBS
 * @property integer $financeGLcodePLSystemID
 * @property string $financeGLcodePL
 * @property integer $includePLForGRVYN
 * @property string $supplierPartNumber
 * @property integer $unitOfMeasure
 * @property integer $altUnit
 * @property number $altUnitValue
 * @property integer $itemClientReferenceNumberMasterID
 * @property string $clientReferenceNumber
 * @property number $requestedQty
 * @property number $noQty
 * @property number $balanceQty
 * @property integer $noOfDays
 * @property number $unitCost
 * @property number $discountPercentage
 * @property number $discountAmount
 * @property number $netAmount
 * @property number $markupPercentage
 * @property number $markupTransactionAmount
 * @property number $markupLocalAmount
 * @property number $markupReportingAmount
 * @property integer $budgetYear
 * @property integer $prBelongsYear
 * @property integer $isAccrued
 * @property number $budjetAmtLocal
 * @property number $budjetAmtRpt
 * @property string $comment
 * @property integer $supplierDefaultCurrencyID
 * @property number $supplierDefaultER
 * @property integer $supplierItemCurrencyID
 * @property number $foreignToLocalER
 * @property integer $companyReportingCurrencyID
 * @property number $companyReportingER
 * @property integer $localCurrencyID
 * @property number $localCurrencyER
 * @property number $addonDistCost
 * @property number $GRVcostPerUnitLocalCur
 * @property number $GRVcostPerUnitSupDefaultCur
 * @property number $GRVcostPerUnitSupTransCur
 * @property number $GRVcostPerUnitComRptCur
 * @property number $addonPurchaseReturnCost
 * @property number $purchaseRetcostPerUnitLocalCur
 * @property number $purchaseRetcostPerUniSupDefaultCur
 * @property number $purchaseRetcostPerUnitTranCur
 * @property number $purchaseRetcostPerUnitRptCur
 * @property number $receivedQty
 * @property integer $GRVSelectedYN
 * @property integer $goodsRecievedYN
 * @property integer $logisticSelectedYN
 * @property integer $logisticRecievedYN
 * @property integer $isAccruedYN
 * @property integer $accrualJVID
 * @property integer $timesReferred
 * @property number $totalWHTAmount
 * @property number $WHTBearedBySupplier
 * @property number $WHTBearedByCompany
 * @property number $VATPercentage
 * @property number $VATAmount
 * @property number $VATAmountLocal
 * @property number $VATAmountRpt
 * @property integer $VATApplicableOn
 * @property integer $manuallyClosed
 * @property integer $manuallyClosedByEmpSystemID
 * @property string $manuallyClosedByEmpID
 * @property string $manuallyClosedByEmpName
 * @property string|\Carbon\Carbon $manuallyClosedDate
 * @property string $manuallyClosedComment
 * @property string $createdUserGroup
 * @property string $createdPcID
 * @property string $createdUserID
 * @property string $modifiedPc
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $createdDateTime
 * @property integer $supplierCatalogDetailID
 * @property integer $supplierCatalogMasterID
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $detail_project_id
 * @property integer $vatMasterCategoryID
 * @property integer $vatSubCategoryID
 * @property number $exempt_vat_portion
 * @property string $contractID
 * @property string $contractDescription
 */
class PurchaseOrderDetail extends Model
{

    use HasFactory;

    public $table = 'erp_purchaseorderdetails';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'purchaseOrderMasterID',
        'purchaseProcessDetailID',
        'POProcessMasterID',
        'WO_purchaseOrderMasterID',
        'WP_purchaseOrderDetailsID',
        'purchaseRequestDetailsID',
        'purchaseRequestID',
        'companySystemID',
        'companyID',
        'departmentID',
        'serviceLineSystemID',
        'serviceLineCode',
        'madeLocallyYN',
        'itemCode',
        'itemPrimaryCode',
        'itemDescription',
        'itemFinanceCategoryID',
        'itemFinanceCategorySubID',
        'financeGLcodebBSSystemID',
        'financeGLcodebBS',
        'financeGLcodePLSystemID',
        'financeGLcodePL',
        'includePLForGRVYN',
        'supplierPartNumber',
        'unitOfMeasure',
        'altUnit',
        'altUnitValue',
        'itemClientReferenceNumberMasterID',
        'clientReferenceNumber',
        'requestedQty',
        'noQty',
        'balanceQty',
        'noOfDays',
        'unitCost',
        'discountPercentage',
        'discountAmount',
        'netAmount',
        'markupPercentage',
        'markupTransactionAmount',
        'markupLocalAmount',
        'markupReportingAmount',
        'budgetYear',
        'prBelongsYear',
        'isAccrued',
        'budjetAmtLocal',
        'budjetAmtRpt',
        'comment',
        'supplierDefaultCurrencyID',
        'supplierDefaultER',
        'supplierItemCurrencyID',
        'foreignToLocalER',
        'companyReportingCurrencyID',
        'companyReportingER',
        'localCurrencyID',
        'localCurrencyER',
        'addonDistCost',
        'GRVcostPerUnitLocalCur',
        'GRVcostPerUnitSupDefaultCur',
        'GRVcostPerUnitSupTransCur',
        'GRVcostPerUnitComRptCur',
        'addonPurchaseReturnCost',
        'purchaseRetcostPerUnitLocalCur',
        'purchaseRetcostPerUniSupDefaultCur',
        'purchaseRetcostPerUnitTranCur',
        'purchaseRetcostPerUnitRptCur',
        'receivedQty',
        'GRVSelectedYN',
        'goodsRecievedYN',
        'logisticSelectedYN',
        'logisticRecievedYN',
        'isAccruedYN',
        'accrualJVID',
        'timesReferred',
        'totalWHTAmount',
        'WHTBearedBySupplier',
        'WHTBearedByCompany',
        'VATPercentage',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'VATApplicableOn',
        'manuallyClosed',
        'manuallyClosedByEmpSystemID',
        'manuallyClosedByEmpID',
        'manuallyClosedByEmpName',
        'manuallyClosedDate',
        'manuallyClosedComment',
        'createdUserGroup',
        'createdPcID',
        'createdUserID',
        'modifiedPc',
        'modifiedUser',
        'createdDateTime',
        'supplierCatalogDetailID',
        'supplierCatalogMasterID',
        'timeStamp',
        'detail_project_id',
        'vatMasterCategoryID',
        'vatSubCategoryID',
        'exempt_vat_portion',
        'contractID',
        'contractDescription'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'purchaseOrderDetailsID' => 'integer',
        'purchaseOrderMasterID' => 'integer',
        'purchaseProcessDetailID' => 'integer',
        'POProcessMasterID' => 'integer',
        'WO_purchaseOrderMasterID' => 'integer',
        'WP_purchaseOrderDetailsID' => 'integer',
        'purchaseRequestDetailsID' => 'integer',
        'purchaseRequestID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'departmentID' => 'string',
        'serviceLineSystemID' => 'integer',
        'serviceLineCode' => 'string',
        'madeLocallyYN' => 'integer',
        'itemCode' => 'integer',
        'itemPrimaryCode' => 'string',
        'itemDescription' => 'string',
        'itemFinanceCategoryID' => 'integer',
        'itemFinanceCategorySubID' => 'integer',
        'financeGLcodebBSSystemID' => 'integer',
        'financeGLcodebBS' => 'string',
        'financeGLcodePLSystemID' => 'integer',
        'financeGLcodePL' => 'string',
        'includePLForGRVYN' => 'integer',
        'supplierPartNumber' => 'string',
        'unitOfMeasure' => 'integer',
        'altUnit' => 'integer',
        'altUnitValue' => 'float',
        'itemClientReferenceNumberMasterID' => 'integer',
        'clientReferenceNumber' => 'string',
        'requestedQty' => 'float',
        'noQty' => 'float',
        'balanceQty' => 'float',
        'noOfDays' => 'integer',
        'unitCost' => 'float',
        'discountPercentage' => 'float',
        'discountAmount' => 'float',
        'netAmount' => 'float',
        'markupPercentage' => 'float',
        'markupTransactionAmount' => 'float',
        'markupLocalAmount' => 'float',
        'markupReportingAmount' => 'float',
        'budgetYear' => 'integer',
        'prBelongsYear' => 'integer',
        'isAccrued' => 'integer',
        'budjetAmtLocal' => 'float',
        'budjetAmtRpt' => 'float',
        'comment' => 'string',
        'supplierDefaultCurrencyID' => 'integer',
        'supplierDefaultER' => 'float',
        'supplierItemCurrencyID' => 'integer',
        'foreignToLocalER' => 'float',
        'companyReportingCurrencyID' => 'integer',
        'companyReportingER' => 'float',
        'localCurrencyID' => 'integer',
        'localCurrencyER' => 'float',
        'addonDistCost' => 'float',
        'GRVcostPerUnitLocalCur' => 'float',
        'GRVcostPerUnitSupDefaultCur' => 'float',
        'GRVcostPerUnitSupTransCur' => 'float',
        'GRVcostPerUnitComRptCur' => 'float',
        'addonPurchaseReturnCost' => 'float',
        'purchaseRetcostPerUnitLocalCur' => 'float',
        'purchaseRetcostPerUniSupDefaultCur' => 'float',
        'purchaseRetcostPerUnitTranCur' => 'float',
        'purchaseRetcostPerUnitRptCur' => 'float',
        'receivedQty' => 'float',
        'GRVSelectedYN' => 'integer',
        'goodsRecievedYN' => 'integer',
        'logisticSelectedYN' => 'integer',
        'logisticRecievedYN' => 'integer',
        'isAccruedYN' => 'integer',
        'accrualJVID' => 'integer',
        'timesReferred' => 'integer',
        'totalWHTAmount' => 'float',
        'WHTBearedBySupplier' => 'float',
        'WHTBearedByCompany' => 'float',
        'VATPercentage' => 'float',
        'VATAmount' => 'float',
        'VATAmountLocal' => 'float',
        'VATAmountRpt' => 'float',
        'VATApplicableOn' => 'integer',
        'manuallyClosed' => 'integer',
        'manuallyClosedByEmpSystemID' => 'integer',
        'manuallyClosedByEmpID' => 'string',
        'manuallyClosedByEmpName' => 'string',
        'manuallyClosedDate' => 'datetime',
        'manuallyClosedComment' => 'string',
        'createdUserGroup' => 'string',
        'createdPcID' => 'string',
        'createdUserID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUser' => 'string',
        'createdDateTime' => 'datetime',
        'supplierCatalogDetailID' => 'integer',
        'supplierCatalogMasterID' => 'integer',
        'timeStamp' => 'datetime',
        'detail_project_id' => 'integer',
        'vatMasterCategoryID' => 'integer',
        'vatSubCategoryID' => 'integer',
        'exempt_vat_portion' => 'float',
        'contractID' => 'string',
        'contractDescription' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function purchaseordermasterid()
    {
        return $this->belongsTo(\App\Models\ErpPurchaseordermaster::class, 'purchaseOrderMasterID');
    }
}
