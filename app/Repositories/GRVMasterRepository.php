<?php

namespace App\Repositories;

use App\Models\GRVMaster;
use App\Repositories\BaseRepository;

/**
 * Class GRVMasterRepository
 * @package App\Repositories
 * @version September 9, 2024, 12:31 pm +04
*/

class GRVMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return GRVMaster::class;
    }
}
