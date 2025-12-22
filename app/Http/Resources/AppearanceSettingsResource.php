<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppearanceSettingsResource extends JsonResource
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
            'appearance_system_id' => $this->appearance_system_id,
            'appearance_element_id' => $this->appearance_element_id,
            'value' => $this->value,
            'updated_at' => $this->updated_at
        ];
    }
}
