<?php

namespace App\Repositories;

use App\Models\ErpDirectInvoiceDetails;
use App\Repositories\BaseRepository;

/**
 * Class ErpDirectInvoiceDetailsRepository
 * @package App\Repositories
 * @version August 10, 2024, 9:33 am +04
*/

class ErpDirectInvoiceDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'directInvoiceAutoID',
        'companySystemID',
        'companyID',
        'serviceLineSystemID',
        'serviceLineCode',
        'chartOfAccountSystemID',
        'glCode',
        'glCodeDes',
        'comments',
        'percentage',
        'DIAmountCurrency',
        'DIAmountCurrencyER',
        'DIAmount',
        'localCurrency',
        'localCurrencyER',
        'localAmount',
        'comRptCurrency',
        'comRptCurrencyER',
        'comRptAmount',
        'budgetYear',
        'isExtraAddon',
        'timesReferred',
        'timeStamp',
        'detail_project_id',
        'vatMasterCategoryID',
        'vatSubCategoryID',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'VATPercentage',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'exempt_vat_portion',
        'deductionType',
        'purchaseOrderID',
        'whtApplicable',
        'whtAmount',
        'whtEdited',
        'contractID',
        'contractDescription'
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
        return ErpDirectInvoiceDetails::class;
    }
}
