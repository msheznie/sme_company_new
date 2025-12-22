<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractMilestonePenaltyDetailResource extends JsonResource
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
            'milestone_penalty_master_id' => $this->milestone_penalty_master_id,
            'milestone_title' => $this->milestone_title,
            'milestone_amount' => $this->milestone_amount,
            'penalty_percentage' => $this->penalty_percentage,
            'penalty_amount' => $this->penalty_amount,
            'penalty_start_date' => $this->penalty_start_date,
            'penalty_frequency' => $this->penalty_frequency,
            'due_in' => $this->due_in,
            'due_penalty_amount' => $this->due_penalty_amount,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
