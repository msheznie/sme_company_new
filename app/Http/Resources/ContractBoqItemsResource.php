<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractBoqItemsResource extends JsonResource
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
            'contractId' => $this->contractId,
            'itemId' => $this->itemId,
            'description' => $this->description,
            'minQty' => $this->minQty,
            'maxQty' => $this->maxQty,
            'qty' => $this->qty,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
