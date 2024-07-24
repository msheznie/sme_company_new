<?php

namespace App\Repositories;

use App\Models\SupplierMaster;
use App\Repositories\BaseRepository;

/**
 * Class SupplierMasterRepository
 * @package App\Repositories
 * @version March 8, 2024, 3:05 pm +04
*/

class SupplierMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uniqueTextcode',
        'primaryCompanySystemID',
        'primaryCompanyID',
        'documentSystemID',
        'documentID',
        'primarySupplierCode',
        'secondarySupplierCode',
        'supplierName',
        'liabilityAccountSysemID',
        'liabilityAccount',
        'UnbilledGRVAccountSystemID',
        'UnbilledGRVAccount',
        'address',
        'countryID',
        'supplierCountryID',
        'telephone',
        'fax',
        'supEmail',
        'webAddress',
        'currency',
        'nameOnPaymentCheque',
        'creditLimit',
        'creditPeriod',
        'supCategoryMasterID',
        'supCategorySubID',
        'supplier_category_id',
        'supplier_group_id',
        'registrationNumber',
        'registrationExprity',
        'approvedYN',
        'approvedEmpSystemID',
        'approvedby',
        'approvedDate',
        'approvedComment',
        'isActive',
        'isSupplierForiegn',
        'supplierConfirmedYN',
        'supplierConfirmedEmpID',
        'supplierConfirmedEmpSystemID',
        'supplierConfirmedEmpName',
        'supplierConfirmedDate',
        'isCriticalYN',
        'interCompanyYN',
        'companyLinkedToSystemID',
        'companyLinkedTo',
        'linkCustomerYN',
        'linkCustomerID',
        'createdUserGroup',
        'createdPcID',
        'createdUserID',
        'modifiedPc',
        'modifiedUser',
        'createdDateTime',
        'createdFrom',
        'isDirect',
        'supplierImportanceID',
        'supplierNatureID',
        'supplierTypeID',
        'WHTApplicable',
        'vatEligible',
        'vatNumber',
        'vatPercentage',
        'retentionPercentage',
        'supCategoryICVMasterID',
        'supCategorySubICVID',
        'isLCCYN',
        'isSMEYN',
        'isMarkupPercentage',
        'markupPercentage',
        'RollLevForApp_curr',
        'refferedBackYN',
        'timesReferred',
        'jsrsNo',
        'jsrsExpiry',
        'timestamp',
        'createdUserSystemID',
        'modifiedUserSystemID',
        'isBlocked',
        'blockedBy',
        'blockedDate',
        'blockedReason',
        'last_activity',
        'advanceAccountSystemID',
        'AdvanceAccount'
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
        return SupplierMaster::class;
    }
}
