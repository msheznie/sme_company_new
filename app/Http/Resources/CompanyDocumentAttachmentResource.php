<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDocumentAttachmentResource extends JsonResource
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
            'companyDocumentAttachmentID' => $this->companyDocumentAttachmentID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'docRefNumber' => $this->docRefNumber,
            'isAttachmentYN' => $this->isAttachmentYN,
            'sendEmailYN' => $this->sendEmailYN,
            'codeGeneratorFormat' => $this->codeGeneratorFormat,
            'isAmountApproval' => $this->isAmountApproval,
            'isServiceLineAccess' => $this->isServiceLineAccess,
            'isServiceLineApproval' => $this->isServiceLineApproval,
            'isCategoryApproval' => $this->isCategoryApproval,
            'blockYN' => $this->blockYN,
            'enableAttachmentAfterApproval' => $this->enableAttachmentAfterApproval,
            'timeStamp' => $this->timeStamp
        ];
    }
}
