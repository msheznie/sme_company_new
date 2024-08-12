<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpDirectInvoiceDetailsResource extends JsonResource
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
            'directInvoiceDetailsID' => $this->directInvoiceDetailsID,
            'directInvoiceAutoID' => $this->directInvoiceAutoID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'serviceLineSystemID' => $this->serviceLineSystemID,
            'serviceLineCode' => $this->serviceLineCode,
            'chartOfAccountSystemID' => $this->chartOfAccountSystemID,
            'glCode' => $this->glCode,
            'glCodeDes' => $this->glCodeDes,
            'comments' => $this->comments,
            'percentage' => $this->percentage,
            'DIAmountCurrency' => $this->DIAmountCurrency,
            'DIAmountCurrencyER' => $this->DIAmountCurrencyER,
            'DIAmount' => $this->DIAmount,
            'localCurrency' => $this->localCurrency,
            'localCurrencyER' => $this->localCurrencyER,
            'localAmount' => $this->localAmount,
            'comRptCurrency' => $this->comRptCurrency,
            'comRptCurrencyER' => $this->comRptCurrencyER,
            'comRptAmount' => $this->comRptAmount,
            'budgetYear' => $this->budgetYear,
            'isExtraAddon' => $this->isExtraAddon,
            'timesReferred' => $this->timesReferred,
            'timeStamp' => $this->timeStamp,
            'detail_project_id' => $this->detail_project_id,
            'vatMasterCategoryID' => $this->vatMasterCategoryID,
            'vatSubCategoryID' => $this->vatSubCategoryID,
            'VATAmount' => $this->VATAmount,
            'VATAmountLocal' => $this->VATAmountLocal,
            'VATAmountRpt' => $this->VATAmountRpt,
            'VATPercentage' => $this->VATPercentage,
            'netAmount' => $this->netAmount,
            'netAmountLocal' => $this->netAmountLocal,
            'netAmountRpt' => $this->netAmountRpt,
            'exempt_vat_portion' => $this->exempt_vat_portion,
            'deductionType' => $this->deductionType,
            'purchaseOrderID' => $this->purchaseOrderID,
            'whtApplicable' => $this->whtApplicable,
            'whtAmount' => $this->whtAmount,
            'whtEdited' => $this->whtEdited,
            'contractID' => $this->contractID,
            'contractDescription' => $this->contractDescription
        ];
    }
}
