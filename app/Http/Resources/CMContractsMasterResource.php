<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMContractsMasterResource extends JsonResource
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
            'cmMaster_id' => $this->cmMaster_id,
            'cmMaster_description' => $this->cmMaster_description,
            'ctm_active' => $this->ctm_active,
            'timestamp' => $this->timestamp
        ];
    }
}
