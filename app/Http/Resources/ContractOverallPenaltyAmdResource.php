<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractOverallPenaltyAmdResource extends JsonResource
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
            'overall_penalty_id' => $this->overall_penalty_id,
            'contract_history_id' => $this->contract_history_id,
            'uuid' => $this->uuid,
            'contract_id' => $this->contract_id,
            'minimum_penalty_percentage' => $this->minimum_penalty_percentage,
            'minimum_penalty_amount' => $this->minimum_penalty_amount,
            'maximum_penalty_percentage' => $this->maximum_penalty_percentage,
            'maximum_penalty_amount' => $this->maximum_penalty_amount,
            'actual_percentage' => $this->actual_percentage,
            'actual_penalty_amount' => $this->actual_penalty_amount,
            'penalty_circulation_start_date' => $this->penalty_circulation_start_date,
            'actual_penalty_start_date' => $this->actual_penalty_start_date,
            'penalty_circulation_frequency' => $this->penalty_circulation_frequency,
            'due_in' => $this->due_in,
            'due_penalty_amount' => $this->due_penalty_amount,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
