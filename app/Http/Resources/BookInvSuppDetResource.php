<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookInvSuppDetResource extends JsonResource
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
            'bookingSupInvoiceDetAutoID' => $this->bookingSupInvoiceDetAutoID,
            'bookingSuppMasInvAutoID' => $this->bookingSuppMasInvAutoID,
            'unbilledgrvAutoID' => $this->unbilledgrvAutoID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'supplierID' => $this->supplierID,
            'purchaseOrderID' => $this->purchaseOrderID,
            'grvAutoID' => $this->grvAutoID,
            'grvType' => $this->grvType,
            'supplierTransactionCurrencyID' => $this->supplierTransactionCurrencyID,
            'supplierTransactionCurrencyER' => $this->supplierTransactionCurrencyER,
            'companyReportingCurrencyID' => $this->companyReportingCurrencyID,
            'companyReportingER' => $this->companyReportingER,
            'localCurrencyID' => $this->localCurrencyID,
            'localCurrencyER' => $this->localCurrencyER,
            'supplierInvoOrderedAmount' => $this->supplierInvoOrderedAmount,
            'supplierInvoAmount' => $this->supplierInvoAmount,
            'transSupplierInvoAmount' => $this->transSupplierInvoAmount,
            'localSupplierInvoAmount' => $this->localSupplierInvoAmount,
            'rptSupplierInvoAmount' => $this->rptSupplierInvoAmount,
            'totTransactionAmount' => $this->totTransactionAmount,
            'totLocalAmount' => $this->totLocalAmount,
            'totRptAmount' => $this->totRptAmount,
            'VATAmount' => $this->VATAmount,
            'VATAmountLocal' => $this->VATAmountLocal,
            'VATAmountRpt' => $this->VATAmountRpt,
            'isAddon' => $this->isAddon,
            'invoiceBeforeGRVYN' => $this->invoiceBeforeGRVYN,
            'timesReferred' => $this->timesReferred,
            'timeStamp' => $this->timeStamp
        ];
    }
}
