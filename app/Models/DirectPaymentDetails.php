<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DirectPaymentDetails
 * @package App\Models
 * @version August 11, 2024, 2:45 pm +04
 *
 * @property integer $directPaymentAutoID
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $serviceLineSystemID
 * @property string $serviceLineCode
 * @property integer $supplierID
 * @property integer $expenseClaimMasterAutoID
 * @property integer $chartOfAccountSystemID
 * @property string $glCode
 * @property string $glCodeDes
 * @property integer $glCodeIsBank
 * @property string $comments
 * @property integer $deductionType
 * @property integer $supplierTransCurrencyID
 * @property number $supplierTransER
 * @property integer $DPAmountCurrency
 * @property number $DPAmountCurrencyER
 * @property number $DPAmount
 * @property number $bankAmount
 * @property integer $bankCurrencyID
 * @property number $bankCurrencyER
 * @property integer $localCurrency
 * @property number $localCurrencyER
 * @property number $localAmount
 * @property integer $comRptCurrency
 * @property number $comRptCurrencyER
 * @property number $comRptAmount
 * @property integer $budgetYear
 * @property integer $timesReferred
 * @property integer $relatedPartyYN
 * @property integer $pettyCashYN
 * @property integer $glCompanySystemID
 * @property string $glCompanyID
 * @property integer $vatMasterCategoryID
 * @property integer $vatSubCategoryID
 * @property number $vatAmount
 * @property number $VATAmountLocal
 * @property number $VATAmountRpt
 * @property number $VATPercentage
 * @property number $netAmount
 * @property number $netAmountLocal
 * @property number $netAmountRpt
 * @property integer $toBankID
 * @property integer $toBankAccountID
 * @property integer $toBankCurrencyID
 * @property number $toBankCurrencyER
 * @property number $toBankAmount
 * @property integer $toBankGlCodeSystemID
 * @property string $toBankGlCode
 * @property string $toBankGLDescription
 * @property integer $toCompanyLocalCurrencyID
 * @property number $toCompanyLocalCurrencyER
 * @property number $toCompanyLocalCurrencyAmount
 * @property integer $toCompanyRptCurrencyID
 * @property number $toCompanyRptCurrencyER
 * @property number $toCompanyRptCurrencyAmount
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $detail_project_id
 * @property string $contractID
 * @property string $contractDescription
 */
class DirectPaymentDetails extends Model
{
    public $table = 'erp_directpaymentdetails';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';

    protected $primaryKey  = 'directPaymentDetailsID';

    public $fillable = [
        'directPaymentAutoID',
        'companySystemID',
        'companyID',
        'serviceLineSystemID',
        'serviceLineCode',
        'supplierID',
        'expenseClaimMasterAutoID',
        'chartOfAccountSystemID',
        'glCode',
        'glCodeDes',
        'glCodeIsBank',
        'comments',
        'deductionType',
        'supplierTransCurrencyID',
        'supplierTransER',
        'DPAmountCurrency',
        'DPAmountCurrencyER',
        'DPAmount',
        'bankAmount',
        'bankCurrencyID',
        'bankCurrencyER',
        'localCurrency',
        'localCurrencyER',
        'localAmount',
        'comRptCurrency',
        'comRptCurrencyER',
        'comRptAmount',
        'budgetYear',
        'timesReferred',
        'relatedPartyYN',
        'pettyCashYN',
        'glCompanySystemID',
        'glCompanyID',
        'vatMasterCategoryID',
        'vatSubCategoryID',
        'vatAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'VATPercentage',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'toBankID',
        'toBankAccountID',
        'toBankCurrencyID',
        'toBankCurrencyER',
        'toBankAmount',
        'toBankGlCodeSystemID',
        'toBankGlCode',
        'toBankGLDescription',
        'toCompanyLocalCurrencyID',
        'toCompanyLocalCurrencyER',
        'toCompanyLocalCurrencyAmount',
        'toCompanyRptCurrencyID',
        'toCompanyRptCurrencyER',
        'toCompanyRptCurrencyAmount',
        'timeStamp',
        'detail_project_id',
        'contractID',
        'contractDescription'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'directPaymentDetailsID' => 'integer',
        'directPaymentAutoID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'serviceLineSystemID' => 'integer',
        'serviceLineCode' => 'string',
        'supplierID' => 'integer',
        'expenseClaimMasterAutoID' => 'integer',
        'chartOfAccountSystemID' => 'integer',
        'glCode' => 'string',
        'glCodeDes' => 'string',
        'glCodeIsBank' => 'integer',
        'comments' => 'string',
        'deductionType' => 'integer',
        'supplierTransCurrencyID' => 'integer',
        'supplierTransER' => 'float',
        'DPAmountCurrency' => 'integer',
        'DPAmountCurrencyER' => 'float',
        'DPAmount' => 'float',
        'bankAmount' => 'float',
        'bankCurrencyID' => 'integer',
        'bankCurrencyER' => 'float',
        'localCurrency' => 'integer',
        'localCurrencyER' => 'float',
        'localAmount' => 'float',
        'comRptCurrency' => 'integer',
        'comRptCurrencyER' => 'float',
        'comRptAmount' => 'float',
        'budgetYear' => 'integer',
        'timesReferred' => 'integer',
        'relatedPartyYN' => 'integer',
        'pettyCashYN' => 'integer',
        'glCompanySystemID' => 'integer',
        'glCompanyID' => 'string',
        'vatMasterCategoryID' => 'integer',
        'vatSubCategoryID' => 'integer',
        'vatAmount' => 'float',
        'VATAmountLocal' => 'float',
        'VATAmountRpt' => 'float',
        'VATPercentage' => 'float',
        'netAmount' => 'float',
        'netAmountLocal' => 'float',
        'netAmountRpt' => 'float',
        'toBankID' => 'integer',
        'toBankAccountID' => 'integer',
        'toBankCurrencyID' => 'integer',
        'toBankCurrencyER' => 'float',
        'toBankAmount' => 'float',
        'toBankGlCodeSystemID' => 'integer',
        'toBankGlCode' => 'string',
        'toBankGLDescription' => 'string',
        'toCompanyLocalCurrencyID' => 'integer',
        'toCompanyLocalCurrencyER' => 'float',
        'toCompanyLocalCurrencyAmount' => 'float',
        'toCompanyRptCurrencyID' => 'integer',
        'toCompanyRptCurrencyER' => 'float',
        'toCompanyRptCurrencyAmount' => 'float',
        'timeStamp' => 'datetime',
        'detail_project_id' => 'integer',
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


}
