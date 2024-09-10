<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BookInvSuppDet
 * @package App\Models
 * @version September 6, 2024, 4:43 pm +04
 *
 * @property integer $bookingSuppMasInvAutoID
 * @property integer $unbilledgrvAutoID
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $supplierID
 * @property integer $purchaseOrderID
 * @property integer $grvAutoID
 * @property string $grvType
 * @property integer $supplierTransactionCurrencyID
 * @property number $supplierTransactionCurrencyER
 * @property integer $companyReportingCurrencyID
 * @property number $companyReportingER
 * @property integer $localCurrencyID
 * @property number $localCurrencyER
 * @property number $supplierInvoOrderedAmount
 * @property number $supplierInvoAmount
 * @property number $transSupplierInvoAmount
 * @property number $localSupplierInvoAmount
 * @property number $rptSupplierInvoAmount
 * @property number $totTransactionAmount
 * @property number $totLocalAmount
 * @property number $totRptAmount
 * @property number $VATAmount
 * @property number $VATAmountLocal
 * @property number $VATAmountRpt
 * @property integer $isAddon
 * @property integer $invoiceBeforeGRVYN
 * @property integer $timesReferred
 * @property string|\Carbon\Carbon $timeStamp
 */
class BookInvSuppDet extends Model
{

    use HasFactory;

    public $table = 'erp_bookinvsuppdet';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';

    protected $primaryKey = 'bookingSupInvoiceDetAutoID';

    public $fillable = [
        'bookingSuppMasInvAutoID',
        'unbilledgrvAutoID',
        'companySystemID',
        'companyID',
        'supplierID',
        'purchaseOrderID',
        'grvAutoID',
        'grvType',
        'supplierTransactionCurrencyID',
        'supplierTransactionCurrencyER',
        'companyReportingCurrencyID',
        'companyReportingER',
        'localCurrencyID',
        'localCurrencyER',
        'supplierInvoOrderedAmount',
        'supplierInvoAmount',
        'transSupplierInvoAmount',
        'localSupplierInvoAmount',
        'rptSupplierInvoAmount',
        'totTransactionAmount',
        'totLocalAmount',
        'totRptAmount',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'isAddon',
        'invoiceBeforeGRVYN',
        'timesReferred',
        'timeStamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'bookingSupInvoiceDetAutoID' => 'integer',
        'bookingSuppMasInvAutoID' => 'integer',
        'unbilledgrvAutoID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'supplierID' => 'integer',
        'purchaseOrderID' => 'integer',
        'grvAutoID' => 'integer',
        'grvType' => 'string',
        'supplierTransactionCurrencyID' => 'integer',
        'supplierTransactionCurrencyER' => 'float',
        'companyReportingCurrencyID' => 'integer',
        'companyReportingER' => 'float',
        'localCurrencyID' => 'integer',
        'localCurrencyER' => 'float',
        'supplierInvoOrderedAmount' => 'float',
        'supplierInvoAmount' => 'float',
        'transSupplierInvoAmount' => 'float',
        'localSupplierInvoAmount' => 'float',
        'rptSupplierInvoAmount' => 'float',
        'totTransactionAmount' => 'float',
        'totLocalAmount' => 'float',
        'totRptAmount' => 'float',
        'VATAmount' => 'float',
        'VATAmountLocal' => 'float',
        'VATAmountRpt' => 'float',
        'isAddon' => 'integer',
        'invoiceBeforeGRVYN' => 'integer',
        'timesReferred' => 'integer',
        'timeStamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function grvMaster()
    {
        return $this->belongsTo(GRVMaster::class, 'grvAutoID', 'grvAutoID');
    }
    public static function getPurchaseOrdersLikedWithSi($purchaseOrderIds)
    {
        $bookingSuppMasInvAutoID = BookInvSuppDet::whereIn('purchaseOrderID', $purchaseOrderIds)
            ->groupBy('bookingSuppMasInvAutoID')
            ->whereNotNull('purchaseOrderID')
            ->get(['bookingSuppMasInvAutoID']);
        return !empty($bookingSuppMasInvAutoID) ? collect($bookingSuppMasInvAutoID)
            ->pluck('bookingSuppMasInvAutoID') : [];
    }
    public static function getSum($id, $field)
    {
        return BookInvSuppDet::where('bookingSuppMasInvAutoID', $id)
            ->sum($field);
    }
}
