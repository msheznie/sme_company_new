<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebEmployeeProfileResource extends JsonResource
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
            'empPorfileID' => $this->empPorfileID,
            'employeeSystemID' => $this->employeeSystemID,
            'empID' => $this->empID,
            'profileImage' => $this->profileImage,
            'modifiedDate' => $this->modifiedDate,
            'timestamp' => $this->timestamp
        ];
    }
}
