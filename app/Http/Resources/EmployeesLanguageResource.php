<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesLanguageResource extends JsonResource
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
            'employeeID' => $this->employeeID,
            'languageID' => $this->languageID,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
