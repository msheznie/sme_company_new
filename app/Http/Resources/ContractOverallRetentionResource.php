<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractOverallRetentionResource extends JsonResource
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
            'contractAmount' => $this->contractAmount,
            'retentionPercentage' => $this->retentionPercentage,
            'retentionAmount' => $this->retentionAmount,
            'startDate' => $this->startDate,
            'dueDate' => $this->dueDate,
            'retentionWithholdPeriod' => $this->retentionWithholdPeriod,
            'companySystemId' => $this->companySystemId,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
