<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaySupplierInvoiceDetailResource extends JsonResource
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
            'payDetailAutoID' => $this->payDetailAutoID,
            'PayMasterAutoId' => $this->PayMasterAutoId,
            'documentID' => $this->documentID,
            'documentSystemID' => $this->documentSystemID,
            'apAutoID' => $this->apAutoID,
            'matchingDocID' => $this->matchingDocID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'addedDocumentSystemID' => $this->addedDocumentSystemID,
            'addedDocumentID' => $this->addedDocumentID,
            'bookingInvSystemCode' => $this->bookingInvSystemCode,
            'bookingInvDocCode' => $this->bookingInvDocCode,
            'bookingInvoiceDate' => $this->bookingInvoiceDate,
            'addedDocumentType' => $this->addedDocumentType,
            'supplierCodeSystem' => $this->supplierCodeSystem,
            'employeeSystemID' => $this->employeeSystemID,
            'supplierInvoiceNo' => $this->supplierInvoiceNo,
            'supplierInvoiceDate' => $this->supplierInvoiceDate,
            'supplierTransCurrencyID' => $this->supplierTransCurrencyID,
            'supplierTransER' => $this->supplierTransER,
            'supplierInvoiceAmount' => $this->supplierInvoiceAmount,
            'supplierDefaultCurrencyID' => $this->supplierDefaultCurrencyID,
            'supplierDefaultCurrencyER' => $this->supplierDefaultCurrencyER,
            'supplierDefaultAmount' => $this->supplierDefaultAmount,
            'localCurrencyID' => $this->localCurrencyID,
            'localER' => $this->localER,
            'localAmount' => $this->localAmount,
            'comRptCurrencyID' => $this->comRptCurrencyID,
            'comRptER' => $this->comRptER,
            'comRptAmount' => $this->comRptAmount,
            'supplierPaymentCurrencyID' => $this->supplierPaymentCurrencyID,
            'supplierPaymentER' => $this->supplierPaymentER,
            'supplierPaymentAmount' => $this->supplierPaymentAmount,
            'paymentBalancedAmount' => $this->paymentBalancedAmount,
            'paymentSupplierDefaultAmount' => $this->paymentSupplierDefaultAmount,
            'paymentLocalAmount' => $this->paymentLocalAmount,
            'paymentComRptAmount' => $this->paymentComRptAmount,
            'retentionVatAmount' => $this->retentionVatAmount,
            'timesReferred' => $this->timesReferred,
            'isRetention' => $this->isRetention,
            'modifiedUserID' => $this->modifiedUserID,
            'modifiedPCID' => $this->modifiedPCID,
            'createdDateTime' => $this->createdDateTime,
            'createdUserSystemID' => $this->createdUserSystemID,
            'createdUserID' => $this->createdUserID,
            'createdPcID' => $this->createdPcID,
            'timeStamp' => $this->timeStamp,
            'purchaseOrderID' => $this->purchaseOrderID,
            'VATAmount' => $this->VATAmount,
            'VATAmountRpt' => $this->VATAmountRpt,
            'VATAmountLocal' => $this->VATAmountLocal,
            'VATPercentage' => $this->VATPercentage,
            'vatMasterCategoryID' => $this->vatMasterCategoryID,
            'vatSubCategoryID' => $this->vatSubCategoryID
        ];
    }
}
