<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThirdPartySystemsResource extends JsonResource
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
            'description' => $this->description,
            'status' => $this->status,
            'moduleID' => $this->moduleID,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'isDefault' => $this->isDefault,
            'comment' => $this->comment
        ];
    }
}
