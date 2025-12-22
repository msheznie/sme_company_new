<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMIntentsMasterResource extends JsonResource
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
            'cmIntent_id' => $this->cmIntent_id,
            'cmIntent_detail' => $this->cmIntent_detail,
            'cim_active' => $this->cim_active,
            'timestamp' => $this->timestamp
        ];
    }
}
