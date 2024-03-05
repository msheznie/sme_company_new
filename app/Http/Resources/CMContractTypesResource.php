<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMContractTypesResource extends JsonResource
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
            'contract_typeId' => $this->contract_typeId,
            'cm_type_name' => $this->cm_type_name,
            'cmMaster_id' => $this->cmMaster_id,
            'cmIntent_id' => $this->cmIntent_id,
            'cmPartyA_id' => $this->cmPartyA_id,
            'cmPartyB_id' => $this->cmPartyB_id,
            'cmCounterParty_id' => $this->cmCounterParty_id,
            'cm_type_description' => $this->cm_type_description,
            'ct_active' => $this->ct_active,
            'companySystemID' => $this->companySystemID,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at
        ];
    }
}
