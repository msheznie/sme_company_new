<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMContractTypeSectionsResource extends JsonResource
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
            'ct_sectionId' => $this->ct_sectionId,
            'contract_typeId' => $this->contract_typeId,
            'cmSection_id' => $this->cmSection_id,
            'is_enabled' => $this->is_enabled,
            'companySystemID' => $this->companySystemID,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at
        ];
    }
}
