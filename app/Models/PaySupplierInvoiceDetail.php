<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PaySupplierInvoiceDetail
 * @package App\Models
 * @version September 9, 2024, 9:11 am +04
 *
 * @property integer $PayMasterAutoId
 * @property string $documentID
 * @property integer $documentSystemID
 * @property integer $apAutoID
 * @property integer $matchingDocID
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $addedDocumentSystemID
 * @property string $addedDocumentID
 * @property integer $bookingInvSystemCode
 * @property string $bookingInvDocCode
 * @property string|\Carbon\Carbon $bookingInvoiceDate
 * @property integer $addedDocumentType
 * @property integer $supplierCodeSystem
 * @property integer $employeeSystemID
 * @property string $supplierInvoiceNo
 * @property string|\Carbon\Carbon $supplierInvoiceDate
 * @property integer $supplierTransCurrencyID
 * @property number $supplierTransER
 * @property number $supplierInvoiceAmount
 * @property integer $supplierDefaultCurrencyID
 * @property number $supplierDefaultCurrencyER
 * @property number $supplierDefaultAmount
 * @property integer $localCurrencyID
 * @property number $localER
 * @property number $localAmount
 * @property integer $comRptCurrencyID
 * @property number $comRptER
 * @property number $comRptAmount
 * @property integer $supplierPaymentCurrencyID
 * @property number $supplierPaymentER
 * @property number $supplierPaymentAmount
 * @property number $paymentBalancedAmount
 * @property number $paymentSupplierDefaultAmount
 * @property number $paymentLocalAmount
 * @property number $paymentComRptAmount
 * @property number $retentionVatAmount
 * @property integer $timesReferred
 * @property boolean $isRetention
 * @property string $modifiedUserID
 * @property string $modifiedPCID
 * @property string|\Carbon\Carbon $createdDateTime
 * @property integer $createdUserSystemID
 * @property string $createdUserID
 * @property string $createdPcID
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $purchaseOrderID
 * @property number $VATAmount
 * @property number $VATAmountRpt
 * @property number $VATAmountLocal
 * @property number $VATPercentage
 * @property integer $vatMasterCategoryID
 * @property integer $vatSubCategoryID
 */
class PaySupplierInvoiceDetail extends Model
{
    use HasFactory;

    public $table = 'erp_paysupplierinvoicedetail';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timestamp';

    protected $primaryKey  = 'payDetailAutoID';


    public $fillable = [
        'PayMasterAutoId',
        'documentID',
        'documentSystemID',
        'apAutoID',
        'matchingDocID',
        'companySystemID',
        'companyID',
        'addedDocumentSystemID',
        'addedDocumentID',
        'bookingInvSystemCode',
        'bookingInvDocCode',
        'bookingInvoiceDate',
        'addedDocumentType',
        'supplierCodeSystem',
        'employeeSystemID',
        'supplierInvoiceNo',
        'supplierInvoiceDate',
        'supplierTransCurrencyID',
        'supplierTransER',
        'supplierInvoiceAmount',
        'supplierDefaultCurrencyID',
        'supplierDefaultCurrencyER',
        'supplierDefaultAmount',
        'localCurrencyID',
        'localER',
        'localAmount',
        'comRptCurrencyID',
        'comRptER',
        'comRptAmount',
        'supplierPaymentCurrencyID',
        'supplierPaymentER',
        'supplierPaymentAmount',
        'paymentBalancedAmount',
        'paymentSupplierDefaultAmount',
        'paymentLocalAmount',
        'paymentComRptAmount',
        'retentionVatAmount',
        'timesReferred',
        'isRetention',
        'modifiedUserID',
        'modifiedPCID',
        'createdDateTime',
        'createdUserSystemID',
        'createdUserID',
        'createdPcID',
        'timeStamp',
        'purchaseOrderID',
        'VATAmount',
        'VATAmountRpt',
        'VATAmountLocal',
        'VATPercentage',
        'vatMasterCategoryID',
        'vatSubCategoryID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'payDetailAutoID' => 'integer',
        'PayMasterAutoId' => 'integer',
        'documentID' => 'string',
        'documentSystemID' => 'integer',
        'apAutoID' => 'integer',
        'matchingDocID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'addedDocumentSystemID' => 'integer',
        'addedDocumentID' => 'string',
        'bookingInvSystemCode' => 'integer',
        'bookingInvDocCode' => 'string',
        'bookingInvoiceDate' => 'datetime',
        'addedDocumentType' => 'integer',
        'supplierCodeSystem' => 'integer',
        'employeeSystemID' => 'integer',
        'supplierInvoiceNo' => 'string',
        'supplierInvoiceDate' => 'datetime',
        'supplierTransCurrencyID' => 'integer',
        'supplierTransER' => 'float',
        'supplierInvoiceAmount' => 'float',
        'supplierDefaultCurrencyID' => 'integer',
        'supplierDefaultCurrencyER' => 'float',
        'supplierDefaultAmount' => 'float',
        'localCurrencyID' => 'integer',
        'localER' => 'float',
        'localAmount' => 'float',
        'comRptCurrencyID' => 'integer',
        'comRptER' => 'float',
        'comRptAmount' => 'float',
        'supplierPaymentCurrencyID' => 'integer',
        'supplierPaymentER' => 'float',
        'supplierPaymentAmount' => 'float',
        'paymentBalancedAmount' => 'float',
        'paymentSupplierDefaultAmount' => 'float',
        'paymentLocalAmount' => 'float',
        'paymentComRptAmount' => 'float',
        'retentionVatAmount' => 'float',
        'timesReferred' => 'integer',
        'isRetention' => 'boolean',
        'modifiedUserID' => 'string',
        'modifiedPCID' => 'string',
        'createdDateTime' => 'datetime',
        'createdUserSystemID' => 'integer',
        'createdUserID' => 'string',
        'createdPcID' => 'string',
        'timeStamp' => 'datetime',
        'purchaseOrderID' => 'integer',
        'VATAmount' => 'float',
        'VATAmountRpt' => 'float',
        'VATAmountLocal' => 'float',
        'VATPercentage' => 'float',
        'vatMasterCategoryID' => 'integer',
        'vatSubCategoryID' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function poMaster()
    {
        return $this->belongsTo(PurchaseOrderMaster::class, 'purchaseOrderID', 'purchaseOrderID');
    }
    public static function getPaySupplierInvoice($invoiceArr)
    {
        $paySupplierInvoice = PaySupplierInvoiceDetail::whereIn('bookingInvSystemCode', $invoiceArr)
            ->groupBy('PayMasterAutoId')
            ->whereNotNull('bookingInvSystemCode')
            ->get(['PayMasterAutoId']);
        return !empty($paySupplierInvoice) ? collect($paySupplierInvoice)
            ->pluck('PayMasterAutoId') : [];
    }
    public static function getSum($id, $field)
    {
        return PaySupplierInvoiceDetail::where('PayMasterAutoId', $id)
            ->sum($field);
    }
}
