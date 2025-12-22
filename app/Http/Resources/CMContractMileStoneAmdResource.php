<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMContractMileStoneAmdResource extends JsonResource
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
            'amd_id' => $this->amd_id,
            'contract_history_id' => $this->contract_history_id,
            'uuid' => $this->uuid,
            'contractID' => $this->contractID,
            'title' => $this->title,
            'status' => $this->status,
            'companySystemID' => $this->companySystemID,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'description' => $this->description
        ];
    }
}
