<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThirdPartyIntegrationKeysResource extends JsonResource
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
            'company_id' => $this->company_id,
            'third_party_system_id' => $this->third_party_system_id,
            'api_key' => $this->api_key,
            'api_external_key' => $this->api_external_key,
            'api_external_url' => $this->api_external_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expiryDate' => $this->expiryDate
        ];
    }
}
