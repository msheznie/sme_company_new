<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpEmployeeNavigationResource extends JsonResource
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
            'employeeSystemID' => $this->employeeSystemID,
            'userGroupID' => $this->userGroupID,
            'companyID' => $this->companyID,
            'timestamp' => $this->timestamp
        ];
    }
}
