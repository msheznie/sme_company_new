<?php

namespace App\Repositories;

use App\Models\PaySupplierInvoiceDetail;
use App\Repositories\BaseRepository;

/**
 * Class PaySupplierInvoiceDetailRepository
 * @package App\Repositories
 * @version September 9, 2024, 9:11 am +04
*/

class PaySupplierInvoiceDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'PayMasterAutoId',
        'documentID',
        'documentSystemID',
        'apAutoID',
        'matchingDocID',
        'companySystemID',
        'companyID',
        'addedDocumentSystemID',
        'addedDocumentID',
        'bookingInvSystemCode',
        'bookingInvDocCode',
        'bookingInvoiceDate',
        'addedDocumentType',
        'supplierCodeSystem',
        'employeeSystemID',
        'supplierInvoiceNo',
        'supplierInvoiceDate',
        'supplierTransCurrencyID',
        'supplierTransER',
        'supplierInvoiceAmount',
        'supplierDefaultCurrencyID',
        'supplierDefaultCurrencyER',
        'supplierDefaultAmount',
        'localCurrencyID',
        'localER',
        'localAmount',
        'comRptCurrencyID',
        'comRptER',
        'comRptAmount',
        'supplierPaymentCurrencyID',
        'supplierPaymentER',
        'supplierPaymentAmount',
        'paymentBalancedAmount',
        'paymentSupplierDefaultAmount',
        'paymentLocalAmount',
        'paymentComRptAmount',
        'retentionVatAmount',
        'timesReferred',
        'isRetention',
        'modifiedUserID',
        'modifiedPCID',
        'createdDateTime',
        'createdUserSystemID',
        'createdUserID',
        'createdPcID',
        'timeStamp',
        'purchaseOrderID',
        'VATAmount',
        'VATAmountRpt',
        'VATAmountLocal',
        'VATPercentage',
        'vatMasterCategoryID',
        'vatSubCategoryID'
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
        return PaySupplierInvoiceDetail::class;
    }
}
