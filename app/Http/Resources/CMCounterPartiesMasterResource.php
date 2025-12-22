<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMCounterPartiesMasterResource extends JsonResource
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
            'cmCounterParty_id' => $this->cmCounterParty_id,
            'cmCounterParty_name' => $this->cmCounterParty_name,
            'cpt_active' => $this->cpt_active,
            'timestamp' => $this->timestamp
        ];
    }
}
