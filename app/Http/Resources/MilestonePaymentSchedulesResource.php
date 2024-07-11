<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilestonePaymentSchedulesResource extends JsonResource
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
            'milestone_id' => $this->milestone_id,
            'description' => $this->description,
            'percentage' => $this->percentage,
            'amount' => $this->amount,
            'payment_due_date' => $this->payment_due_date,
            'actual_payment_date' => $this->actual_payment_date,
            'milestone_due_date' => $this->milestone_due_date,
            'currency_id' => $this->currency_id,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
