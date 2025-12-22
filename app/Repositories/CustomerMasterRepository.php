<?php

namespace App\Repositories;

use App\Models\CustomerMaster;
use App\Repositories\BaseRepository;

/**
 * Class CustomerMasterRepository
 * @package App\Repositories
 * @version March 8, 2024, 4:54 pm +04
*/

class CustomerMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'primaryCompanySystemID',
        'primaryCompanyID',
        'documentSystemID',
        'documentID',
        'lastSerialOrder',
        'CutomerCode',
        'customerShortCode',
        'customerCategoryID',
        'custGLAccountSystemID',
        'custGLaccount',
        'custUnbilledAccountSystemID',
        'custUnbilledAccount',
        'CustomerName',
        'customerSecondLanguage',
        'ReportTitle',
        'reportTitleSecondLanguage',
        'customerAddress1',
        'addressOneSecondLanguage',
        'customerAddress2',
        'addressTwoSecondLanguage',
        'customerCity',
        'customerCountry',
        'CustWebsite',
        'creditLimit',
        'creditDays',
        'customerLogo',
        'interCompanyYN',
        'companyLinkedToSystemID',
        'companyLinkedTo',
        'isCustomerActive',
        'isAllowedQHSE',
        'vendorCode',
        'vatEligible',
        'vatNumber',
        'vatPercentage',
        'isSupplierForiegn',
        'approvedYN',
        'approvedEmpSystemID',
        'approvedEmpID',
        'approvedDate',
        'approvedComment',
        'confirmedYN',
        'confirmedEmpSystemID',
        'confirmedEmpID',
        'confirmedEmpName',
        'confirmedDate',
        'RollLevForApp_curr',
        'refferedBackYN',
        'timesReferred',
        'createdUserGroup',
        'createdUserID',
        'createdDateTime',
        'createdPcID',
        'modifiedPc',
        'modifiedUser',
        'timeStamp',
        'createdFrom',
        'consignee_name',
        'consignee_address',
        'payment_terms',
        'consignee_contact_no',
        'customer_registration_no',
        'customer_registration_expiry_date',
        'custAdvanceAccountSystemID',
        'custAdvanceAccount'
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
        return CustomerMaster::class;
    }
}
