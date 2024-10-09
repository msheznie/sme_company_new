<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierContactDetailsResource extends JsonResource
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
            'supplierContactID' => $this->supplierContactID,
            'supplierID' => $this->supplierID,
            'contactTypeID' => $this->contactTypeID,
            'contactPersonName' => $this->contactPersonName,
            'contactPersonTelephone' => $this->contactPersonTelephone,
            'contactPersonFax' => $this->contactPersonFax,
            'contactPersonEmail' => $this->contactPersonEmail,
            'isDefault' => $this->isDefault,
            'timestamp' => $this->timestamp
        ];
    }
}
