<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\BaseRepository;

/**
 * Class CompanyRepository
 * @package App\Repositories
 * @version February 16, 2024, 5:54 pm +04
*/

class CompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Company::class;
    }

    public static function checkIsCompanyGroup($companyID): bool
    {
        $isCompaniesGroup = Company::where('companySystemID', $companyID)->where('isGroup', -1)->exists();
        return (bool)$isCompaniesGroup;
    }


    public static function getGroupCompany($selectedCompanyId, $excludeSameCompany = false): array
    {
        $companiesByGroup = Company::with(['child' => function($q) use($selectedCompanyId,$excludeSameCompany){
            if($excludeSameCompany){
                $q->where("companySystemID",'!=', $selectedCompanyId);
            }
        }])
            ->where("masterCompanySystemIDReorting", $selectedCompanyId)
            ->get();

        $groupCompany = [];
        if ($companiesByGroup) {
            foreach ($companiesByGroup as $val) {
                if ($val['child']) {
                    foreach ($val['child'] as $val1) {
                        $groupCompany[] = array('companySystemID' => $val1["companySystemID"], 'CompanyID' => $val1["CompanyID"], 'CompanyName' => $val1["CompanyName"]);
                    }
                } else {
                    $groupCompany[] = array('companySystemID' => $val["companySystemID"], 'CompanyID' => $val["CompanyID"], 'CompanyName' => $val["CompanyName"]);
                }
            }
        }
        return array_column($groupCompany, 'companySystemID');
    }
}
