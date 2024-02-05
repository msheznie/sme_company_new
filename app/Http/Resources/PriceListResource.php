<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceListResource extends JsonResource
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
            'item_code' => $this->item_code,
            'item_description' => $this->item_description,
            'part_number' => $this->part_number,
            'uom' => $this->uom,
            'delivery_lead_time' => $this->delivery_lead_time,
            'currency_id' => $this->currency_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
