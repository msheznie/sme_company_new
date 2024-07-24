<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpDocumentMasterResource extends JsonResource
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
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'documentDescription' => $this->documentDescription,
            'departmentSystemID' => $this->departmentSystemID,
            'departmentID' => $this->departmentID,
            'isPrintable' => $this->isPrintable,
            'timeStamp' => $this->timeStamp
        ];
    }
}
