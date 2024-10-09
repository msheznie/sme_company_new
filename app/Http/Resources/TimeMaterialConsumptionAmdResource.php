<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeMaterialConsumptionAmdResource extends JsonResource
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
            'level_no' => $this->level_no,
            'uuid' => $this->uuid,
            'contract_id' => $this->contract_id,
            'item' => $this->item,
            'description' => $this->description,
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'uom_id' => $this->uom_id,
            'amount' => $this->amount,
            'boq_id' => $this->boq_id,
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
