<?php

namespace App\Models;

use App\helper\Helper;
use App\Helpers\General;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Company
 * @package App\Models
 * @version February 16, 2024, 5:54 pm +04
 *
 * @property string $CompanyID
 * @property string $CompanyName
 * @property string $CompanyNameLocalized
 * @property string $LocalName
 * @property integer $MasterLevel
 * @property integer $CompanyLevel
 * @property integer $masterComapanySystemID
 * @property string $masterComapanyID
 * @property integer $masterCompanySystemIDReorting
 * @property string $masterComapanyIDReporting
 * @property string $companyShortCode
 * @property integer $orgListOrder
 * @property integer $orgListSordOrder
 * @property integer $sortOrder
 * @property integer $listOrder
 * @property string $CompanyAddress
 * @property string $CompanyAddressSecondaryLanguage
 * @property integer $companyCountry
 * @property integer $CompanyTelephone
 * @property integer $CompanyFax
 * @property string $CompanyEmail
 * @property string $CompanyURL
 * @property string|\Carbon\Carbon $SubscriptionStarted
 * @property string|\Carbon\Carbon $SubscriptionUpTo
 * @property string $ContactPerson
 * @property integer $ContactPersonTelephone
 * @property integer $ContactPersonFax
 * @property string $ContactPersonEmail
 * @property string $registrationNumber
 * @property string $companyLogo
 * @property string $logoPath
 * @property string $companyGroupLogo
 * @property integer $reportingCurrency
 * @property integer $localCurrencyID
 * @property string $mainFormName
 * @property string $menuInitialImage
 * @property string $menuInitialSelectedImage
 * @property number $policyItemIssueTollerence
 * @property number $policyAddonPercentage
 * @property integer $policyPOAppDayDiff
 * @property integer $policyStockAdjWacCurrentYN
 * @property integer $policyDepreciationRunDate
 * @property integer $isGroup
 * @property integer $isAttachementYN
 * @property string $reportingCriteria
 * @property string $reportingCriteriaFormQuery
 * @property string $supplierReportingCriteria
 * @property string $supplierReportingCriteriaFormQuery
 * @property string $supplierPOSavReportingCriteria
 * @property string $supplierPOSavReportingCriteriaFormQuery
 * @property string $supplierPOSpentReportingCriteriaFormQuery
 * @property integer $exchangeGainLossGLCodeSystemID
 * @property string $exchangeGainLossGLCode
 * @property string $exchangeLossGLCode
 * @property string $exchangeGainGLCode
 * @property string $exchangeProvisionGLCode
 * @property string $exchangeProvisionGLCodeAR
 * @property integer $isApprovalByServiceLine
 * @property integer $isApprovalByServiceLineFinance
 * @property integer $isTaxYN
 * @property integer $isActive
 * @property integer $isActiveGroup
 * @property integer $showInCombo
 * @property integer $allowBackDatedGRV
 * @property integer $allowCustomerInvWithoutContractID
 * @property integer $checkMaxQty
 * @property integer $itemCodeMustInPR
 * @property integer $op_OnOpenPopUpYN
 * @property integer $showInNewRILRQHSE
 * @property integer $vatRegisteredYN
 * @property string $vatRegistratonNumber
 * @property integer $vatInputGLCodeSystemID
 * @property string $vatInputGLCode
 * @property integer $vatOutputGLCodeSystemID
 * @property string $vatOutputGLCode
 * @property string $createdUserGroup
 * @property string $createdPcID
 * @property string $createdUserID
 * @property string $modifiedPc
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $isDemo
 * @property boolean $isHrmsIntergrated
 * @property integer $pmsErpIntegrated
 * @property string $jsrsNumber
 * @property string|\Carbon\Carbon $jsrsExpiryDate
 * @property string $taxCardNo
 * @property number $revenuePercentageForInterCompanyInventoryTransfer
 * @property number $revenuePercentageForInterCompanyAssetTransfer
 * @property string $qhseApiKey
 * @property integer $isAttachmentFromS3
 * @property string $helpDeskApiKey
 * @property string $helpDeskUrl
 * @property integer $helpDeskProductId
 * @property string $helpDeskTenant
 * @property integer $group_two
 * @property integer $group_type
 * @property number $holding_percentage
 * @property string|\Carbon\Carbon $holding_updated_date
 */
class Company extends Model
{

    public $table = 'companymaster';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'companySystemID';
    protected $appends = ['logo_url'];

    public $fillable = [
        'CompanyID',
        'CompanyName',
        'CompanyNameLocalized',
        'LocalName',
        'MasterLevel',
        'CompanyLevel',
        'masterComapanySystemID',
        'masterComapanyID',
        'masterCompanySystemIDReorting',
        'masterComapanyIDReporting',
        'companyShortCode',
        'orgListOrder',
        'orgListSordOrder',
        'sortOrder',
        'listOrder',
        'CompanyAddress',
        'CompanyAddressSecondaryLanguage',
        'companyCountry',
        'CompanyTelephone',
        'CompanyFax',
        'CompanyEmail',
        'CompanyURL',
        'SubscriptionStarted',
        'SubscriptionUpTo',
        'ContactPerson',
        'ContactPersonTelephone',
        'ContactPersonFax',
        'ContactPersonEmail',
        'registrationNumber',
        'companyLogo',
        'logoPath',
        'companyGroupLogo',
        'reportingCurrency',
        'localCurrencyID',
        'mainFormName',
        'menuInitialImage',
        'menuInitialSelectedImage',
        'policyItemIssueTollerence',
        'policyAddonPercentage',
        'policyPOAppDayDiff',
        'policyStockAdjWacCurrentYN',
        'policyDepreciationRunDate',
        'isGroup',
        'isAttachementYN',
        'reportingCriteria',
        'reportingCriteriaFormQuery',
        'supplierReportingCriteria',
        'supplierReportingCriteriaFormQuery',
        'supplierPOSavReportingCriteria',
        'supplierPOSavReportingCriteriaFormQuery',
        'supplierPOSpentReportingCriteriaFormQuery',
        'exchangeGainLossGLCodeSystemID',
        'exchangeGainLossGLCode',
        'exchangeLossGLCode',
        'exchangeGainGLCode',
        'exchangeProvisionGLCode',
        'exchangeProvisionGLCodeAR',
        'isApprovalByServiceLine',
        'isApprovalByServiceLineFinance',
        'isTaxYN',
        'isActive',
        'isActiveGroup',
        'showInCombo',
        'allowBackDatedGRV',
        'allowCustomerInvWithoutContractID',
        'checkMaxQty',
        'itemCodeMustInPR',
        'op_OnOpenPopUpYN',
        'showInNewRILRQHSE',
        'vatRegisteredYN',
        'vatRegistratonNumber',
        'vatInputGLCodeSystemID',
        'vatInputGLCode',
        'vatOutputGLCodeSystemID',
        'vatOutputGLCode',
        'createdUserGroup',
        'createdPcID',
        'createdUserID',
        'modifiedPc',
        'modifiedUser',
        'createdDateTime',
        'timeStamp',
        'isDemo',
        'isHrmsIntergrated',
        'pmsErpIntegrated',
        'jsrsNumber',
        'jsrsExpiryDate',
        'taxCardNo',
        'revenuePercentageForInterCompanyInventoryTransfer',
        'revenuePercentageForInterCompanyAssetTransfer',
        'qhseApiKey',
        'isAttachmentFromS3',
        'helpDeskApiKey',
        'helpDeskUrl',
        'helpDeskProductId',
        'helpDeskTenant',
        'group_two',
        'group_type',
        'holding_percentage',
        'holding_updated_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'companySystemID' => 'integer',
        'CompanyID' => 'string',
        'CompanyName' => 'string',
        'CompanyNameLocalized' => 'string',
        'LocalName' => 'string',
        'MasterLevel' => 'integer',
        'CompanyLevel' => 'integer',
        'masterComapanySystemID' => 'integer',
        'masterComapanyID' => 'string',
        'masterCompanySystemIDReorting' => 'integer',
        'masterComapanyIDReporting' => 'string',
        'companyShortCode' => 'string',
        'orgListOrder' => 'integer',
        'orgListSordOrder' => 'integer',
        'sortOrder' => 'integer',
        'listOrder' => 'integer',
        'CompanyAddress' => 'string',
        'CompanyAddressSecondaryLanguage' => 'string',
        'companyCountry' => 'integer',
        'CompanyTelephone' => 'integer',
        'CompanyFax' => 'integer',
        'CompanyEmail' => 'string',
        'CompanyURL' => 'string',
        'SubscriptionStarted' => 'datetime',
        'SubscriptionUpTo' => 'datetime',
        'ContactPerson' => 'string',
        'ContactPersonTelephone' => 'integer',
        'ContactPersonFax' => 'integer',
        'ContactPersonEmail' => 'string',
        'registrationNumber' => 'string',
        'companyLogo' => 'string',
        'logoPath' => 'string',
        'companyGroupLogo' => 'string',
        'reportingCurrency' => 'integer',
        'localCurrencyID' => 'integer',
        'mainFormName' => 'string',
        'menuInitialImage' => 'string',
        'menuInitialSelectedImage' => 'string',
        'policyItemIssueTollerence' => 'float',
        'policyAddonPercentage' => 'float',
        'policyPOAppDayDiff' => 'integer',
        'policyStockAdjWacCurrentYN' => 'integer',
        'policyDepreciationRunDate' => 'integer',
        'isGroup' => 'integer',
        'isAttachementYN' => 'integer',
        'reportingCriteria' => 'string',
        'reportingCriteriaFormQuery' => 'string',
        'supplierReportingCriteria' => 'string',
        'supplierReportingCriteriaFormQuery' => 'string',
        'supplierPOSavReportingCriteria' => 'string',
        'supplierPOSavReportingCriteriaFormQuery' => 'string',
        'supplierPOSpentReportingCriteriaFormQuery' => 'string',
        'exchangeGainLossGLCodeSystemID' => 'integer',
        'exchangeGainLossGLCode' => 'string',
        'exchangeLossGLCode' => 'string',
        'exchangeGainGLCode' => 'string',
        'exchangeProvisionGLCode' => 'string',
        'exchangeProvisionGLCodeAR' => 'string',
        'isApprovalByServiceLine' => 'integer',
        'isApprovalByServiceLineFinance' => 'integer',
        'isTaxYN' => 'integer',
        'isActive' => 'integer',
        'isActiveGroup' => 'integer',
        'showInCombo' => 'integer',
        'allowBackDatedGRV' => 'integer',
        'allowCustomerInvWithoutContractID' => 'integer',
        'checkMaxQty' => 'integer',
        'itemCodeMustInPR' => 'integer',
        'op_OnOpenPopUpYN' => 'integer',
        'showInNewRILRQHSE' => 'integer',
        'vatRegisteredYN' => 'integer',
        'vatRegistratonNumber' => 'string',
        'vatInputGLCodeSystemID' => 'integer',
        'vatInputGLCode' => 'string',
        'vatOutputGLCodeSystemID' => 'integer',
        'vatOutputGLCode' => 'string',
        'createdUserGroup' => 'string',
        'createdPcID' => 'string',
        'createdUserID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUser' => 'string',
        'createdDateTime' => 'datetime',
        'timeStamp' => 'datetime',
        'isDemo' => 'integer',
        'isHrmsIntergrated' => 'boolean',
        'pmsErpIntegrated' => 'integer',
        'jsrsNumber' => 'string',
        'jsrsExpiryDate' => 'datetime',
        'taxCardNo' => 'string',
        'revenuePercentageForInterCompanyInventoryTransfer' => 'float',
        'revenuePercentageForInterCompanyAssetTransfer' => 'float',
        'qhseApiKey' => 'string',
        'isAttachmentFromS3' => 'integer',
        'helpDeskApiKey' => 'string',
        'helpDeskUrl' => 'string',
        'helpDeskProductId' => 'integer',
        'helpDeskTenant' => 'string',
        'group_two' => 'integer',
        'group_type' => 'integer',
        'holding_percentage' => 'float',
        'holding_updated_date' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'CompanyID' => 'nullable|string|max:255',
        'CompanyName' => 'nullable|string',
        'CompanyNameLocalized' => 'nullable|string|max:255',
        'LocalName' => 'nullable|string|max:45',
        'MasterLevel' => 'nullable|integer',
        'CompanyLevel' => 'nullable|integer',
        'masterComapanySystemID' => 'nullable|integer',
        'masterComapanyID' => 'nullable|string|max:45',
        'masterCompanySystemIDReorting' => 'nullable|integer',
        'masterComapanyIDReporting' => 'nullable|string|max:45',
        'companyShortCode' => 'nullable|string|max:45',
        'orgListOrder' => 'nullable|integer',
        'orgListSordOrder' => 'nullable|integer',
        'sortOrder' => 'nullable|integer',
        'listOrder' => 'nullable|integer',
        'CompanyAddress' => 'nullable|string',
        'CompanyAddressSecondaryLanguage' => 'nullable|string',
        'companyCountry' => 'nullable|integer',
        'CompanyTelephone' => 'nullable|integer',
        'CompanyFax' => 'nullable|integer',
        'CompanyEmail' => 'nullable|string|max:255',
        'CompanyURL' => 'nullable|string|max:255',
        'SubscriptionStarted' => 'nullable',
        'SubscriptionUpTo' => 'nullable',
        'ContactPerson' => 'nullable|string|max:255',
        'ContactPersonTelephone' => 'nullable|integer',
        'ContactPersonFax' => 'nullable|integer',
        'ContactPersonEmail' => 'nullable|string|max:255',
        'registrationNumber' => 'nullable|string|max:150',
        'companyLogo' => 'nullable|string|max:255',
        'logoPath' => 'nullable|string|max:255',
        'companyGroupLogo' => 'nullable|string|max:255',
        'reportingCurrency' => 'nullable|integer',
        'localCurrencyID' => 'nullable|integer',
        'mainFormName' => 'nullable|string|max:100',
        'menuInitialImage' => 'nullable|string|max:45',
        'menuInitialSelectedImage' => 'nullable|string|max:45',
        'policyItemIssueTollerence' => 'nullable|numeric',
        'policyAddonPercentage' => 'nullable|numeric',
        'policyPOAppDayDiff' => 'nullable|integer',
        'policyStockAdjWacCurrentYN' => 'nullable|integer',
        'policyDepreciationRunDate' => 'nullable|integer',
        'isGroup' => 'nullable|integer',
        'isAttachementYN' => 'nullable|integer',
        'reportingCriteria' => 'nullable|string',
        'reportingCriteriaFormQuery' => 'nullable|string',
        'supplierReportingCriteria' => 'nullable|string',
        'supplierReportingCriteriaFormQuery' => 'nullable|string',
        'supplierPOSavReportingCriteria' => 'nullable|string',
        'supplierPOSavReportingCriteriaFormQuery' => 'nullable|string',
        'supplierPOSpentReportingCriteriaFormQuery' => 'nullable|string',
        'exchangeGainLossGLCodeSystemID' => 'nullable|integer',
        'exchangeGainLossGLCode' => 'nullable|string|max:100',
        'exchangeLossGLCode' => 'nullable|string|max:100',
        'exchangeGainGLCode' => 'nullable|string|max:100',
        'exchangeProvisionGLCode' => 'nullable|string|max:100',
        'exchangeProvisionGLCodeAR' => 'nullable|string|max:100',
        'isApprovalByServiceLine' => 'nullable|integer',
        'isApprovalByServiceLineFinance' => 'nullable|integer',
        'isTaxYN' => 'nullable|integer',
        'isActive' => 'nullable|integer',
        'isActiveGroup' => 'nullable|integer',
        'showInCombo' => 'nullable|integer',
        'allowBackDatedGRV' => 'nullable|integer',
        'allowCustomerInvWithoutContractID' => 'nullable|integer',
        'checkMaxQty' => 'nullable|integer',
        'itemCodeMustInPR' => 'nullable|integer',
        'op_OnOpenPopUpYN' => 'nullable|integer',
        'showInNewRILRQHSE' => 'nullable|integer',
        'vatRegisteredYN' => 'nullable|integer',
        'vatRegistratonNumber' => 'nullable|string|max:100',
        'vatInputGLCodeSystemID' => 'nullable|integer',
        'vatInputGLCode' => 'nullable|string|max:45',
        'vatOutputGLCodeSystemID' => 'nullable|integer',
        'vatOutputGLCode' => 'nullable|string|max:45',
        'createdUserGroup' => 'nullable|string|max:255',
        'createdPcID' => 'nullable|string|max:255',
        'createdUserID' => 'nullable|string|max:255',
        'modifiedPc' => 'nullable|string|max:255',
        'modifiedUser' => 'nullable|string|max:255',
        'createdDateTime' => 'nullable',
        'timeStamp' => 'nullable',
        'isDemo' => 'nullable|integer',
        'isHrmsIntergrated' => 'nullable|boolean',
        'pmsErpIntegrated' => 'nullable|integer',
        'jsrsNumber' => 'nullable|string|max:255',
        'jsrsExpiryDate' => 'nullable',
        'taxCardNo' => 'nullable|string|max:255',
        'revenuePercentageForInterCompanyInventoryTransfer' => 'nullable|numeric',
        'revenuePercentageForInterCompanyAssetTransfer' => 'nullable|numeric',
        'qhseApiKey' => 'nullable|string|max:255',
        'isAttachmentFromS3' => 'nullable|integer',
        'helpDeskApiKey' => 'nullable|string|max:255',
        'helpDeskUrl' => 'nullable|string|max:255',
        'helpDeskProductId' => 'nullable|integer',
        'helpDeskTenant' => 'nullable|string|max:255',
        'group_two' => 'nullable|integer',
        'group_type' => 'nullable|integer',
        'holding_percentage' => 'nullable|numeric',
        'holding_updated_date' => 'nullable'
    ];

    public function getLogoUrlAttribute()
    {
        return General::checkPolicy($this->masterCompanySystemIDReorting, 50)
            ? General::getFileUrlFromS3($this->logoPath)
            : $this->logoPath;
    }

    public static function getLocalCurrencyID($companySystemID)
    {
        $company = Company::find($companySystemID);

        return $company ? $company->localCurrencyID : "";
    }

    public static function getData($id)
    {
        return self::where([
            ['companySystemID', '=', $id],
            ['isActive', '=', 1]
        ])->exists();
    }

    public static function getCompanyData($id)
    {
        return self::where([
            ['companySystemID', '=', $id],
            ['isActive', '=', 1]
        ])->first();
    }

}
