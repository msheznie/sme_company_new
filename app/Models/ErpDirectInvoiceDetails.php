<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpDirectInvoiceDetails
 * @package App\Models
 * @version August 10, 2024, 9:33 am +04
 *
 * @property integer $directInvoiceAutoID
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $serviceLineSystemID
 * @property string $serviceLineCode
 * @property integer $chartOfAccountSystemID
 * @property string $glCode
 * @property string $glCodeDes
 * @property string $comments
 * @property number $percentage
 * @property integer $DIAmountCurrency
 * @property number $DIAmountCurrencyER
 * @property number $DIAmount
 * @property integer $localCurrency
 * @property number $localCurrencyER
 * @property number $localAmount
 * @property integer $comRptCurrency
 * @property number $comRptCurrencyER
 * @property number $comRptAmount
 * @property integer $budgetYear
 * @property integer $isExtraAddon
 * @property integer $timesReferred
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $detail_project_id
 * @property integer $vatMasterCategoryID
 * @property integer $vatSubCategoryID
 * @property number $VATAmount
 * @property number $VATAmountLocal
 * @property number $VATAmountRpt
 * @property number $VATPercentage
 * @property number $netAmount
 * @property number $netAmountLocal
 * @property number $netAmountRpt
 * @property number $exempt_vat_portion
 * @property integer $deductionType
 * @property integer $purchaseOrderID
 * @property boolean $whtApplicable
 * @property number $whtAmount
 * @property boolean $whtEdited
 * @property string $contractID
 * @property string $contractDescription
 */
class ErpDirectInvoiceDetails extends Model
{
    public $table = 'erp_directinvoicedetails';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';

    protected $primaryKey = 'directInvoiceDetailsID';



    public $fillable = [
        'directInvoiceAutoID',
        'companySystemID',
        'companyID',
        'serviceLineSystemID',
        'serviceLineCode',
        'chartOfAccountSystemID',
        'glCode',
        'glCodeDes',
        'comments',
        'percentage',
        'DIAmountCurrency',
        'DIAmountCurrencyER',
        'DIAmount',
        'localCurrency',
        'localCurrencyER',
        'localAmount',
        'comRptCurrency',
        'comRptCurrencyER',
        'comRptAmount',
        'budgetYear',
        'isExtraAddon',
        'timesReferred',
        'timeStamp',
        'detail_project_id',
        'vatMasterCategoryID',
        'vatSubCategoryID',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'VATPercentage',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'exempt_vat_portion',
        'deductionType',
        'purchaseOrderID',
        'whtApplicable',
        'whtAmount',
        'whtEdited',
        'contractID',
        'contractDescription'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'directInvoiceDetailsID' => 'integer',
        'directInvoiceAutoID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'serviceLineSystemID' => 'integer',
        'serviceLineCode' => 'string',
        'chartOfAccountSystemID' => 'integer',
        'glCode' => 'string',
        'glCodeDes' => 'string',
        'comments' => 'string',
        'percentage' => 'float',
        'DIAmountCurrency' => 'integer',
        'DIAmountCurrencyER' => 'float',
        'DIAmount' => 'float',
        'localCurrency' => 'integer',
        'localCurrencyER' => 'float',
        'localAmount' => 'float',
        'comRptCurrency' => 'integer',
        'comRptCurrencyER' => 'float',
        'comRptAmount' => 'float',
        'budgetYear' => 'integer',
        'isExtraAddon' => 'integer',
        'timesReferred' => 'integer',
        'timeStamp' => 'datetime',
        'detail_project_id' => 'integer',
        'vatMasterCategoryID' => 'integer',
        'vatSubCategoryID' => 'integer',
        'VATAmount' => 'float',
        'VATAmountLocal' => 'float',
        'VATAmountRpt' => 'float',
        'VATPercentage' => 'float',
        'netAmount' => 'float',
        'netAmountLocal' => 'float',
        'netAmountRpt' => 'float',
        'exempt_vat_portion' => 'float',
        'deductionType' => 'integer',
        'purchaseOrderID' => 'integer',
        'whtApplicable' => 'boolean',
        'whtAmount' => 'float',
        'whtEdited' => 'boolean',
        'contractID' => 'string',
        'contractDescription' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function getContractLinkedWithErp($contractUuid, $companySystemID)
    {
        $directInvoiceAutoIDs = ErpDirectInvoiceDetails::where('contractID', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->groupBy('directInvoiceAutoID')
            ->get(['directInvoiceAutoID']);
        return !empty($directInvoiceAutoIDs) ? collect($directInvoiceAutoIDs)->pluck('directInvoiceAutoID') : [];
    }
}
