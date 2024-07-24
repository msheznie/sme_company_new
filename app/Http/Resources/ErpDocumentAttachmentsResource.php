<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpDocumentAttachmentsResource extends JsonResource
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
            'attachmentID' => $this->attachmentID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'documentSystemCode' => $this->documentSystemCode,
            'approvalLevelOrder' => $this->approvalLevelOrder,
            'attachmentDescription' => $this->attachmentDescription,
            'location' => $this->location,
            'path' => $this->path,
            'originalFileName' => $this->originalFileName,
            'myFileName' => $this->myFileName,
            'docExpirtyDate' => $this->docExpirtyDate,
            'attachmentType' => $this->attachmentType,
            'sizeInKbs' => $this->sizeInKbs,
            'isUploaded' => $this->isUploaded,
            'pullFromAnotherDocument' => $this->pullFromAnotherDocument,
            'parent_id' => $this->parent_id,
            'timeStamp' => $this->timeStamp,
            'envelopType' => $this->envelopType,
            'order_number' => $this->order_number
        ];
    }
}
