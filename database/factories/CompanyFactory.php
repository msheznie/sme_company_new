<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'CompanyID' => $this->faker->word,
        'CompanyName' => $this->faker->text,
        'CompanyNameLocalized' => $this->faker->word,
        'LocalName' => $this->faker->word,
        'MasterLevel' => $this->faker->randomDigitNotNull,
        'CompanyLevel' => $this->faker->randomDigitNotNull,
        'masterComapanySystemID' => $this->faker->randomDigitNotNull,
        'masterComapanyID' => $this->faker->word,
        'masterCompanySystemIDReorting' => $this->faker->randomDigitNotNull,
        'masterComapanyIDReporting' => $this->faker->word,
        'companyShortCode' => $this->faker->word,
        'orgListOrder' => $this->faker->randomDigitNotNull,
        'orgListSordOrder' => $this->faker->randomDigitNotNull,
        'sortOrder' => $this->faker->randomDigitNotNull,
        'listOrder' => $this->faker->randomDigitNotNull,
        'CompanyAddress' => $this->faker->text,
        'CompanyAddressSecondaryLanguage' => $this->faker->text,
        'companyCountry' => $this->faker->randomDigitNotNull,
        'CompanyTelephone' => $this->faker->randomDigitNotNull,
        'CompanyFax' => $this->faker->randomDigitNotNull,
        'CompanyEmail' => $this->faker->word,
        'CompanyURL' => $this->faker->word,
        'SubscriptionStarted' => $this->faker->date('Y-m-d H:i:s'),
        'SubscriptionUpTo' => $this->faker->date('Y-m-d H:i:s'),
        'ContactPerson' => $this->faker->word,
        'ContactPersonTelephone' => $this->faker->randomDigitNotNull,
        'ContactPersonFax' => $this->faker->randomDigitNotNull,
        'ContactPersonEmail' => $this->faker->word,
        'registrationNumber' => $this->faker->word,
        'companyLogo' => $this->faker->word,
        'logoPath' => $this->faker->word,
        'companyGroupLogo' => $this->faker->word,
        'reportingCurrency' => $this->faker->randomDigitNotNull,
        'localCurrencyID' => $this->faker->randomDigitNotNull,
        'mainFormName' => $this->faker->word,
        'menuInitialImage' => $this->faker->word,
        'menuInitialSelectedImage' => $this->faker->word,
        'policyItemIssueTollerence' => $this->faker->randomDigitNotNull,
        'policyAddonPercentage' => $this->faker->randomDigitNotNull,
        'policyPOAppDayDiff' => $this->faker->randomDigitNotNull,
        'policyStockAdjWacCurrentYN' => $this->faker->randomDigitNotNull,
        'policyDepreciationRunDate' => $this->faker->randomDigitNotNull,
        'isGroup' => $this->faker->randomDigitNotNull,
        'isAttachementYN' => $this->faker->randomDigitNotNull,
        'reportingCriteria' => $this->faker->text,
        'reportingCriteriaFormQuery' => $this->faker->text,
        'supplierReportingCriteria' => $this->faker->text,
        'supplierReportingCriteriaFormQuery' => $this->faker->text,
        'supplierPOSavReportingCriteria' => $this->faker->text,
        'supplierPOSavReportingCriteriaFormQuery' => $this->faker->text,
        'supplierPOSpentReportingCriteriaFormQuery' => $this->faker->text,
        'exchangeGainLossGLCodeSystemID' => $this->faker->randomDigitNotNull,
        'exchangeGainLossGLCode' => $this->faker->word,
        'exchangeLossGLCode' => $this->faker->word,
        'exchangeGainGLCode' => $this->faker->word,
        'exchangeProvisionGLCode' => $this->faker->word,
        'exchangeProvisionGLCodeAR' => $this->faker->word,
        'isApprovalByServiceLine' => $this->faker->randomDigitNotNull,
        'isApprovalByServiceLineFinance' => $this->faker->randomDigitNotNull,
        'isTaxYN' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->randomDigitNotNull,
        'isActiveGroup' => $this->faker->randomDigitNotNull,
        'showInCombo' => $this->faker->randomDigitNotNull,
        'allowBackDatedGRV' => $this->faker->randomDigitNotNull,
        'allowCustomerInvWithoutContractID' => $this->faker->randomDigitNotNull,
        'checkMaxQty' => $this->faker->randomDigitNotNull,
        'itemCodeMustInPR' => $this->faker->randomDigitNotNull,
        'op_OnOpenPopUpYN' => $this->faker->randomDigitNotNull,
        'showInNewRILRQHSE' => $this->faker->randomDigitNotNull,
        'vatRegisteredYN' => $this->faker->randomDigitNotNull,
        'vatRegistratonNumber' => $this->faker->word,
        'vatInputGLCodeSystemID' => $this->faker->randomDigitNotNull,
        'vatInputGLCode' => $this->faker->word,
        'vatOutputGLCodeSystemID' => $this->faker->randomDigitNotNull,
        'vatOutputGLCode' => $this->faker->word,
        'createdUserGroup' => $this->faker->word,
        'createdPcID' => $this->faker->word,
        'createdUserID' => $this->faker->word,
        'modifiedPc' => $this->faker->word,
        'modifiedUser' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'isDemo' => $this->faker->randomDigitNotNull,
        'isHrmsIntergrated' => $this->faker->word,
        'pmsErpIntegrated' => $this->faker->randomDigitNotNull,
        'jsrsNumber' => $this->faker->word,
        'jsrsExpiryDate' => $this->faker->date('Y-m-d H:i:s'),
        'taxCardNo' => $this->faker->word,
        'revenuePercentageForInterCompanyInventoryTransfer' => $this->faker->randomDigitNotNull,
        'revenuePercentageForInterCompanyAssetTransfer' => $this->faker->randomDigitNotNull,
        'qhseApiKey' => $this->faker->word,
        'isAttachmentFromS3' => $this->faker->randomDigitNotNull,
        'helpDeskApiKey' => $this->faker->word,
        'helpDeskUrl' => $this->faker->word,
        'helpDeskProductId' => $this->faker->randomDigitNotNull,
        'helpDeskTenant' => $this->faker->word,
        'group_two' => $this->faker->randomDigitNotNull,
        'group_type' => $this->faker->randomDigitNotNull,
        'holding_percentage' => $this->faker->randomDigitNotNull,
        'holding_updated_date' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
