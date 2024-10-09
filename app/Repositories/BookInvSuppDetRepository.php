<?php

namespace App\Repositories;

use App\Models\BookInvSuppDet;
use App\Repositories\BaseRepository;

/**
 * Class BookInvSuppDetRepository
 * @package App\Repositories
 * @version September 6, 2024, 4:43 pm +04
*/

class BookInvSuppDetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bookingSuppMasInvAutoID',
        'unbilledgrvAutoID',
        'companySystemID',
        'companyID',
        'supplierID',
        'purchaseOrderID',
        'grvAutoID',
        'grvType',
        'supplierTransactionCurrencyID',
        'supplierTransactionCurrencyER',
        'companyReportingCurrencyID',
        'companyReportingER',
        'localCurrencyID',
        'localCurrencyER',
        'supplierInvoOrderedAmount',
        'supplierInvoAmount',
        'transSupplierInvoAmount',
        'localSupplierInvoAmount',
        'rptSupplierInvoAmount',
        'totTransactionAmount',
        'totLocalAmount',
        'totRptAmount',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'isAddon',
        'invoiceBeforeGRVYN',
        'timesReferred',
        'timeStamp'
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
        return BookInvSuppDet::class;
    }
}
