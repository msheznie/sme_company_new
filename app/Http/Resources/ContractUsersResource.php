<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractUsersResource extends JsonResource
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
            'contractUserId' => $this->contractUserId,
            'contractUserType' => $this->contractUserType,
            'contractUserCode' => $this->contractUserCode,
            'contractUserName' => $this->contractUserName,
            'isActive' => $this->isActive,
            'companySystemId' => $this->companySystemId,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
