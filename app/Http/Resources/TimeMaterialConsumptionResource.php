<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeMaterialConsumptionResource extends JsonResource
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
            'item' => $this->item,
            'description' => $this->description,
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
            'price' => $this->price,
            'amount' => $this->amount,
            'boq_id' => $this->boq_id,
            'currency_id' => $this->currency_id,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
