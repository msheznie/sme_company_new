<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMContractStatusHistoryAmdResource extends JsonResource
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
            'contract_history_id' => $this->contract_history_id,
            'amd_id' => $this->amd_id,
            'uuid' => $this->uuid,
            'contractID' => $this->contractID,
            'milestoneID' => $this->milestoneID,
            'changedFrom' => $this->changedFrom,
            'changedTo' => $this->changedTo,
            'companySystemID' => $this->companySystemID,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
