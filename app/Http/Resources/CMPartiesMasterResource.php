<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMPartiesMasterResource extends JsonResource
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
            'cmParty_id' => $this->cmParty_id,
            'cmParty_name' => $this->cmParty_name,
            'cpm_active' => $this->cpm_active,
            'timestamp' => $this->timestamp
        ];
    }
}
