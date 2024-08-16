<?php

namespace App\Repositories;

use App\Models\ErpBookingSupplierMaster;
use App\Repositories\BaseRepository;

/**
 * Class ErpBookingSupplierMasterRepository
 * @package App\Repositories
 * @version August 10, 2024, 9:14 am +04
*/

class ErpBookingSupplierMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companySystemID',
        'companyID',
        'documentSystemID',
        'documentID',
        'projectID',
        'serialNo',
        'companyFinanceYearID',
        'FYBiggin',
        'FYEnd',
        'companyFinancePeriodID',
        'FYPeriodDateFrom',
        'FYPeriodDateTo',
        'bookingInvCode',
        'bookingDate',
        'comments',
        'secondaryRefNo',
        'supplierID',
        'supplierGLCodeSystemID',
        'supplierGLCode',
        'UnbilledGRVAccountSystemID',
        'UnbilledGRVAccount',
        'supplierInvoiceNo',
        'supplierInvoiceDate',
        'custInvoiceDirectAutoID',
        'supplierTransactionCurrencyID',
        'supplierTransactionCurrencyER',
        'companyReportingCurrencyID',
        'companyReportingER',
        'localCurrencyID',
        'localCurrencyER',
        'bookingAmountTrans',
        'bookingAmountLocal',
        'bookingAmountRpt',
        'confirmedYN',
        'confirmedByEmpSystemID',
        'confirmedByEmpID',
        'confirmedByName',
        'confirmedDate',
        'approved',
        'approvedDate',
        'approvedByUserID',
        'approvedByUserSystemID',
        'postedDate',
        'documentType',
        'refferedBackYN',
        'timesReferred',
        'RollLevForApp_curr',
        'interCompanyTransferYN',
        'createdUserGroup',
        'createdUserSystemID',
        'createdUserID',
        'createdPcID',
        'modifiedUserSystemID',
        'modifiedUser',
        'modifiedPc',
        'createdDateTime',
        'createdDateAndTime',
        'cancelYN',
        'cancelComment',
        'cancelDate',
        'canceledByEmpSystemID',
        'canceledByEmpID',
        'canceledByEmpName',
        'timestamp',
        'rcmActivated',
        'vatRegisteredYN',
        'isLocalSupplier',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'retentionVatAmount',
        'retentionDueDate',
        'retentionAmount',
        'retentionPercentage',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'VATPercentage',
        'serviceLineSystemID',
        'wareHouseSystemCode',
        'supplierVATEligible',
        'employeeID',
        'employeeControlAcID',
        'createMonthlyDeduction',
        'deliveryAppoinmentID',
        'whtApplicableYN',
        'whtType',
        'whtApplicable',
        'whtAmount',
        'whtEdited',
        'whtPercentage',
        'isWHTApplicableVat'
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
        return ErpBookingSupplierMaster::class;
    }
}
