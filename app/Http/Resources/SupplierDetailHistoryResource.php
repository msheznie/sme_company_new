<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierDetailHistoryResource extends JsonResource
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
            'user_id' => $this->user_id,
            'tenant_id' => $this->tenant_id,
            'form_section_id' => $this->form_section_id,
            'form_group_id' => $this->form_group_id,
            'form_field_id' => $this->form_field_id,
            'form_data_id' => $this->form_data_id,
            'sort' => $this->sort,
            'value' => $this->value,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'master_id' => $this->master_id
        ];
    }
}
