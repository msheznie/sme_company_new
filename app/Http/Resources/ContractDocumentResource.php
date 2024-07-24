<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractDocumentResource extends JsonResource
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
            'id' => $this->id,
            'uuid' => $this->uuid,
            'documentType' => $this->documentType,
            'documentName' => $this->documentName,
            'documentDescription' => $this->documentDescription,
            'attachedDate' => $this->attachedDate,
            'followingRequest' => $this->followingRequest,
            'status' => $this->status,
            'receivedBy' => $this->receivedBy,
            'receivedDate' => $this->receivedDate,
            'receivedFormat' => $this->receivedFormat,
            'documentVersionNumber' => $this->documentVersionNumber,
            'documentResponsiblePerson' => $this->documentResponsiblePerson,
            'documentExpiryDate' => $this->documentExpiryDate,
            'returnedBy' => $this->returnedBy,
            'returnedDate' => $this->returnedDate,
            'returnedTo' => $this->returnedTo,
            'companySystemID' => $this->companySystemID,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
