<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class GRVMaster
 * @package App\Models
 * @version September 9, 2024, 12:31 pm +04
 *
 * @property integer $grvTypeID
 * @property string $grvType
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $serviceLineSystemID
 * @property string $serviceLineCode
 * @property string $companyAddress
 * @property integer $companyFinanceYearID
 * @property integer $companyFinancePeriodID
 * @property string|\Carbon\Carbon $FYBiggin
 * @property string|\Carbon\Carbon $FYEnd
 * @property integer $documentSystemID
 * @property string $documentID
 * @property integer $projectID
 * @property string|\Carbon\Carbon $grvDate
 * @property string|\Carbon\Carbon $stampDate
 * @property integer $grvSerialNo
 * @property string $grvPrimaryCode
 * @property string $grvDoRefNo
 * @property string $grvNarration
 * @property integer $grvLocation
 * @property string $grvDOpersonName
 * @property string $grvDOpersonResID
 * @property string $grvDOpersonTelNo
 * @property string $grvDOpersonVehicleNo
 * @property integer $supplierID
 * @property string $supplierPrimaryCode
 * @property string $supplierName
 * @property string $supplierAddress
 * @property string $supplierTelephone
 * @property string $supplierFax
 * @property string $supplierEmail
 * @property integer $liabilityAccountSysemID
 * @property string $liabilityAccount
 * @property integer $UnbilledGRVAccountSystemID
 * @property string $UnbilledGRVAccount
 * @property integer $localCurrencyID
 * @property number $localCurrencyER
 * @property integer $companyReportingCurrencyID
 * @property number $companyReportingER
 * @property integer $supplierDefaultCurrencyID
 * @property number $supplierDefaultER
 * @property integer $supplierTransactionCurrencyID
 * @property number $supplierTransactionER
 * @property integer $grvConfirmedYN
 * @property integer $grvConfirmedByEmpSystemID
 * @property string $grvConfirmedByEmpID
 * @property string $grvConfirmedByName
 * @property string|\Carbon\Carbon $grvConfirmedDate
 * @property integer $grvCancelledYN
 * @property integer $grvCancelledBySystemID
 * @property string $grvCancelledBy
 * @property string $grvCancelledByName
 * @property string|\Carbon\Carbon $grvCancelledDate
 * @property string $grvCancelledComment
 * @property number $grvTotalComRptCurrency
 * @property number $grvTotalLocalCurrency
 * @property number $grvTotalSupplierDefaultCurrency
 * @property number $grvTotalSupplierTransactionCurrency
 * @property number $grvDiscountPercentage
 * @property number $grvDiscountAmount
 * @property integer $approved
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedByUserID
 * @property integer $approvedByUserSystemID
 * @property string|\Carbon\Carbon $postedDate
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property integer $RollLevForApp_curr
 * @property integer $invoiceBeforeGRVYN
 * @property integer $deliveryConfirmedYN
 * @property integer $interCompanyTransferYN
 * @property integer $FromCompanySystemID
 * @property string $FromCompanyID
 * @property integer $capitalizedYN
 * @property integer $isMarkupUpdated
 * @property string $createdUserGroup
 * @property string $createdPcID
 * @property integer $createdUserSystemID
 * @property string $createdUserID
 * @property string $modifiedPc
 * @property integer $modifiedUserSystemID
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $pullType
 * @property integer $mfqJobID
 * @property integer $vatRegisteredYN
 * @property integer $deliveryAppoinmentID
 */
class GRVMaster extends Model
{

    use HasFactory;

    public $table = 'erp_grvmaster';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timeStamp';

    protected $primaryKey  = 'grvAutoID';



    public $fillable = [
        'grvTypeID',
        'grvType',
        'companySystemID',
        'companyID',
        'serviceLineSystemID',
        'serviceLineCode',
        'companyAddress',
        'companyFinanceYearID',
        'companyFinancePeriodID',
        'FYBiggin',
        'FYEnd',
        'documentSystemID',
        'documentID',
        'projectID',
        'grvDate',
        'stampDate',
        'grvSerialNo',
        'grvPrimaryCode',
        'grvDoRefNo',
        'grvNarration',
        'grvLocation',
        'grvDOpersonName',
        'grvDOpersonResID',
        'grvDOpersonTelNo',
        'grvDOpersonVehicleNo',
        'supplierID',
        'supplierPrimaryCode',
        'supplierName',
        'supplierAddress',
        'supplierTelephone',
        'supplierFax',
        'supplierEmail',
        'liabilityAccountSysemID',
        'liabilityAccount',
        'UnbilledGRVAccountSystemID',
        'UnbilledGRVAccount',
        'localCurrencyID',
        'localCurrencyER',
        'companyReportingCurrencyID',
        'companyReportingER',
        'supplierDefaultCurrencyID',
        'supplierDefaultER',
        'supplierTransactionCurrencyID',
        'supplierTransactionER',
        'grvConfirmedYN',
        'grvConfirmedByEmpSystemID',
        'grvConfirmedByEmpID',
        'grvConfirmedByName',
        'grvConfirmedDate',
        'grvCancelledYN',
        'grvCancelledBySystemID',
        'grvCancelledBy',
        'grvCancelledByName',
        'grvCancelledDate',
        'grvCancelledComment',
        'grvTotalComRptCurrency',
        'grvTotalLocalCurrency',
        'grvTotalSupplierDefaultCurrency',
        'grvTotalSupplierTransactionCurrency',
        'grvDiscountPercentage',
        'grvDiscountAmount',
        'approved',
        'approvedDate',
        'approvedByUserID',
        'approvedByUserSystemID',
        'postedDate',
        'refferedBackYN',
        'timesReferred',
        'RollLevForApp_curr',
        'invoiceBeforeGRVYN',
        'deliveryConfirmedYN',
        'interCompanyTransferYN',
        'FromCompanySystemID',
        'FromCompanyID',
        'capitalizedYN',
        'isMarkupUpdated',
        'createdUserGroup',
        'createdPcID',
        'createdUserSystemID',
        'createdUserID',
        'modifiedPc',
        'modifiedUserSystemID',
        'modifiedUser',
        'createdDateTime',
        'timeStamp',
        'pullType',
        'mfqJobID',
        'vatRegisteredYN',
        'deliveryAppoinmentID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'grvAutoID' => 'integer',
        'grvTypeID' => 'integer',
        'grvType' => 'string',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'serviceLineSystemID' => 'integer',
        'serviceLineCode' => 'string',
        'companyAddress' => 'string',
        'companyFinanceYearID' => 'integer',
        'companyFinancePeriodID' => 'integer',
        'FYBiggin' => 'datetime',
        'FYEnd' => 'datetime',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'projectID' => 'integer',
        'grvDate' => 'datetime',
        'stampDate' => 'datetime',
        'grvSerialNo' => 'integer',
        'grvPrimaryCode' => 'string',
        'grvDoRefNo' => 'string',
        'grvNarration' => 'string',
        'grvLocation' => 'integer',
        'grvDOpersonName' => 'string',
        'grvDOpersonResID' => 'string',
        'grvDOpersonTelNo' => 'string',
        'grvDOpersonVehicleNo' => 'string',
        'supplierID' => 'integer',
        'supplierPrimaryCode' => 'string',
        'supplierName' => 'string',
        'supplierAddress' => 'string',
        'supplierTelephone' => 'string',
        'supplierFax' => 'string',
        'supplierEmail' => 'string',
        'liabilityAccountSysemID' => 'integer',
        'liabilityAccount' => 'string',
        'UnbilledGRVAccountSystemID' => 'integer',
        'UnbilledGRVAccount' => 'string',
        'localCurrencyID' => 'integer',
        'localCurrencyER' => 'float',
        'companyReportingCurrencyID' => 'integer',
        'companyReportingER' => 'float',
        'supplierDefaultCurrencyID' => 'integer',
        'supplierDefaultER' => 'float',
        'supplierTransactionCurrencyID' => 'integer',
        'supplierTransactionER' => 'float',
        'grvConfirmedYN' => 'integer',
        'grvConfirmedByEmpSystemID' => 'integer',
        'grvConfirmedByEmpID' => 'string',
        'grvConfirmedByName' => 'string',
        'grvConfirmedDate' => 'datetime',
        'grvCancelledYN' => 'integer',
        'grvCancelledBySystemID' => 'integer',
        'grvCancelledBy' => 'string',
        'grvCancelledByName' => 'string',
        'grvCancelledDate' => 'datetime',
        'grvCancelledComment' => 'string',
        'grvTotalComRptCurrency' => 'float',
        'grvTotalLocalCurrency' => 'float',
        'grvTotalSupplierDefaultCurrency' => 'float',
        'grvTotalSupplierTransactionCurrency' => 'float',
        'grvDiscountPercentage' => 'float',
        'grvDiscountAmount' => 'float',
        'approved' => 'integer',
        'approvedDate' => 'datetime',
        'approvedByUserID' => 'string',
        'approvedByUserSystemID' => 'integer',
        'postedDate' => 'datetime',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'RollLevForApp_curr' => 'integer',
        'invoiceBeforeGRVYN' => 'integer',
        'deliveryConfirmedYN' => 'integer',
        'interCompanyTransferYN' => 'integer',
        'FromCompanySystemID' => 'integer',
        'FromCompanyID' => 'string',
        'capitalizedYN' => 'integer',
        'isMarkupUpdated' => 'integer',
        'createdUserGroup' => 'string',
        'createdPcID' => 'string',
        'createdUserSystemID' => 'integer',
        'createdUserID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUserSystemID' => 'integer',
        'modifiedUser' => 'string',
        'createdDateTime' => 'datetime',
        'timeStamp' => 'datetime',
        'pullType' => 'integer',
        'mfqJobID' => 'integer',
        'vatRegisteredYN' => 'integer',
        'deliveryAppoinmentID' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
