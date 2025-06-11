<?php

namespace App\Repositories;

use App\Models\SupplierAssigned;
use App\Repositories\BaseRepository;

/**
 * Class SupplierAssignedRepository
 * @package App\Repositories
 * @version May 29, 2025, 3:12 pm +04
*/

class SupplierAssignedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'supplierCodeSytem',
        'companySystemID',
        'companyID',
        'uniqueTextcode',
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
        'supplierImportanceID',
        'supplierNatureID',
        'supplierTypeID',
        'WHTApplicable',
        'vatEligible',
        'vatNumber',
        'vatPercentage',
        'supCategoryICVMasterID',
        'supCategorySubICVID',
        'isLCCYN',
        'isMarkupPercentage',
        'markupPercentage',
        'isRelatedPartyYN',
        'isCriticalYN',
        'jsrsNo',
        'jsrsExpiry',
        'isActive',
        'isAssigned',
        'timestamp',
        'isBlocked',
        'blockedBy',
        'blockedDate',
        'blockedReason',
        'createdFrom',
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
        return SupplierAssigned::class;
    }
}
