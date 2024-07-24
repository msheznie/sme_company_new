<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractMilestoneRetentionResource extends JsonResource
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
            'milestoneId' => $this->milestoneId,
            'retentionPercentage' => $this->retentionPercentage,
            'retentionAmount' => $this->retentionAmount,
            'startDate' => $this->startDate,
            'dueDate' => $this->dueDate,
            'withholdPeriod' => $this->withholdPeriod,
            'paymentStatus' => $this->paymentStatus,
            'companySystemId' => $this->companySystemId,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
