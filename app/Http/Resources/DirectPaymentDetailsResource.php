<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DirectPaymentDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'directPaymentDetailsID' => $this->directPaymentDetailsID,
            'directPaymentAutoID' => $this->directPaymentAutoID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'serviceLineSystemID' => $this->serviceLineSystemID,
            'serviceLineCode' => $this->serviceLineCode,
            'supplierID' => $this->supplierID,
            'expenseClaimMasterAutoID' => $this->expenseClaimMasterAutoID,
            'chartOfAccountSystemID' => $this->chartOfAccountSystemID,
            'glCode' => $this->glCode,
            'glCodeDes' => $this->glCodeDes,
            'glCodeIsBank' => $this->glCodeIsBank,
            'comments' => $this->comments,
            'deductionType' => $this->deductionType,
            'supplierTransCurrencyID' => $this->supplierTransCurrencyID,
            'supplierTransER' => $this->supplierTransER,
            'DPAmountCurrency' => $this->DPAmountCurrency,
            'DPAmountCurrencyER' => $this->DPAmountCurrencyER,
            'DPAmount' => $this->DPAmount,
            'bankAmount' => $this->bankAmount,
            'bankCurrencyID' => $this->bankCurrencyID,
            'bankCurrencyER' => $this->bankCurrencyER,
            'localCurrency' => $this->localCurrency,
            'localCurrencyER' => $this->localCurrencyER,
            'localAmount' => $this->localAmount,
            'comRptCurrency' => $this->comRptCurrency,
            'comRptCurrencyER' => $this->comRptCurrencyER,
            'comRptAmount' => $this->comRptAmount,
            'budgetYear' => $this->budgetYear,
            'timesReferred' => $this->timesReferred,
            'relatedPartyYN' => $this->relatedPartyYN,
            'pettyCashYN' => $this->pettyCashYN,
            'glCompanySystemID' => $this->glCompanySystemID,
            'glCompanyID' => $this->glCompanyID,
            'vatMasterCategoryID' => $this->vatMasterCategoryID,
            'vatSubCategoryID' => $this->vatSubCategoryID,
            'vatAmount' => $this->vatAmount,
            'VATAmountLocal' => $this->VATAmountLocal,
            'VATAmountRpt' => $this->VATAmountRpt,
            'VATPercentage' => $this->VATPercentage,
            'netAmount' => $this->netAmount,
            'netAmountLocal' => $this->netAmountLocal,
            'netAmountRpt' => $this->netAmountRpt,
            'toBankID' => $this->toBankID,
            'toBankAccountID' => $this->toBankAccountID,
            'toBankCurrencyID' => $this->toBankCurrencyID,
            'toBankCurrencyER' => $this->toBankCurrencyER,
            'toBankAmount' => $this->toBankAmount,
            'toBankGlCodeSystemID' => $this->toBankGlCodeSystemID,
            'toBankGlCode' => $this->toBankGlCode,
            'toBankGLDescription' => $this->toBankGLDescription,
            'toCompanyLocalCurrencyID' => $this->toCompanyLocalCurrencyID,
            'toCompanyLocalCurrencyER' => $this->toCompanyLocalCurrencyER,
            'toCompanyLocalCurrencyAmount' => $this->toCompanyLocalCurrencyAmount,
            'toCompanyRptCurrencyID' => $this->toCompanyRptCurrencyID,
            'toCompanyRptCurrencyER' => $this->toCompanyRptCurrencyER,
            'toCompanyRptCurrencyAmount' => $this->toCompanyRptCurrencyAmount,
            'timeStamp' => $this->timeStamp,
            'detail_project_id' => $this->detail_project_id,
            'contractID' => $this->contractID,
            'contractDescription' => $this->contractDescription
        ];
    }
}
