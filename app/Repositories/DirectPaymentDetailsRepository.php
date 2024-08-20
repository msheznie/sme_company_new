<?php

namespace App\Repositories;

use App\Models\DirectPaymentDetails;
use App\Repositories\BaseRepository;

/**
 * Class DirectPaymentDetailsRepository
 * @package App\Repositories
 * @version August 11, 2024, 2:45 pm +04
*/

class DirectPaymentDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'directPaymentAutoID',
        'companySystemID',
        'companyID',
        'serviceLineSystemID',
        'serviceLineCode',
        'supplierID',
        'expenseClaimMasterAutoID',
        'chartOfAccountSystemID',
        'glCode',
        'glCodeDes',
        'glCodeIsBank',
        'comments',
        'deductionType',
        'supplierTransCurrencyID',
        'supplierTransER',
        'DPAmountCurrency',
        'DPAmountCurrencyER',
        'DPAmount',
        'bankAmount',
        'bankCurrencyID',
        'bankCurrencyER',
        'localCurrency',
        'localCurrencyER',
        'localAmount',
        'comRptCurrency',
        'comRptCurrencyER',
        'comRptAmount',
        'budgetYear',
        'timesReferred',
        'relatedPartyYN',
        'pettyCashYN',
        'glCompanySystemID',
        'glCompanyID',
        'vatMasterCategoryID',
        'vatSubCategoryID',
        'vatAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'VATPercentage',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'toBankID',
        'toBankAccountID',
        'toBankCurrencyID',
        'toBankCurrencyER',
        'toBankAmount',
        'toBankGlCodeSystemID',
        'toBankGlCode',
        'toBankGLDescription',
        'toCompanyLocalCurrencyID',
        'toCompanyLocalCurrencyER',
        'toCompanyLocalCurrencyAmount',
        'toCompanyRptCurrencyID',
        'toCompanyRptCurrencyER',
        'toCompanyRptCurrencyAmount',
        'timeStamp',
        'detail_project_id',
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
        return DirectPaymentDetails::class;
    }
}
