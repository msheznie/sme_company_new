<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMContractSectionsMasterResource extends JsonResource
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
            'cmSection_id' => $this->cmSection_id,
            'cmSection_detail' => $this->cmSection_detail,
            'csm_active' => $this->csm_active,
            'timestamp' => $this->timestamp
        ];
    }
}
