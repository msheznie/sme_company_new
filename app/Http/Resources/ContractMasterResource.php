<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractMasterResource extends JsonResource
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
            'contractCode' => $this->contractCode,
            'title' => $this->title,
            'contractType' => $this->contractType,
            'counterParty' => $this->counterParty,
            'counterPartyName' => $this->counterPartyName,
            'referenceCode' => $this->referenceCode,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status,
            'companySystemID' => $this->companySystemID,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
