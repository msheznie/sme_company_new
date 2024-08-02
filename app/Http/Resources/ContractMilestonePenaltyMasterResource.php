<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractMilestonePenaltyMasterResource extends JsonResource
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
            'contract_id' => $this->contract_id,
            'minimum_penalty_percentage' => $this->minimum_penalty_percentage,
            'minimum_penalty_amount' => $this->minimum_penalty_amount,
            'maximum_penalty_percentage' => $this->maximum_penalty_percentage,
            'maximum_penalty_amount' => $this->maximum_penalty_amount,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
