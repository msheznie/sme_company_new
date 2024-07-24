<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractUserAssignResource extends JsonResource
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
            'uuid' => $this->uuid,
            'contractId' => $this->contractId,
            'userGroupId' => $this->userGroupId,
            'userId' => $this->userId,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
