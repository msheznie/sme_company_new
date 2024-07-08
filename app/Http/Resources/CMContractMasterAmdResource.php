<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMContractMasterAmdResource extends JsonResource
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
            'contract_history_id' => $this->contract_history_id,
            'uuid' => $this->uuid,
            'contractCode' => $this->contractCode,
            'title' => $this->title,
            'description' => $this->description,
            'contractType' => $this->contractType,
            'counterParty' => $this->counterParty,
            'counterPartyName' => $this->counterPartyName,
            'referenceCode' => $this->referenceCode,
            'contractOwner' => $this->contractOwner,
            'contractAmount' => $this->contractAmount,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'agreementSignDate' => $this->agreementSignDate,
            'notifyDays' => $this->notifyDays,
            'contractTermPeriod' => $this->contractTermPeriod,
            'contractRenewalDate' => $this->contractRenewalDate,
            'contractExtensionDate' => $this->contractExtensionDate,
            'contractTerminateDate' => $this->contractTerminateDate,
            'contractRevisionDate' => $this->contractRevisionDate,
            'primaryCounterParty' => $this->primaryCounterParty,
            'primaryEmail' => $this->primaryEmail,
            'primaryPhoneNumber' => $this->primaryPhoneNumber,
            'secondaryCounterParty' => $this->secondaryCounterParty,
            'secondaryEmail' => $this->secondaryEmail,
            'secondaryPhoneNumber' => $this->secondaryPhoneNumber,
            'documentMasterId' => $this->documentMasterId,
            'status' => $this->status,
            'companySystemID' => $this->companySystemID,
            'confirmed_yn' => $this->confirmed_yn,
            'confirmed_date' => $this->confirmed_date,
            'confirm_by' => $this->confirm_by,
            'confirmed_comment' => $this->confirmed_comment,
            'rollLevelOrder' => $this->rollLevelOrder,
            'refferedBackYN' => $this->refferedBackYN,
            'timesReferred' => $this->timesReferred,
            'approved_yn' => $this->approved_yn,
            'approved_by' => $this->approved_by,
            'approved_date' => $this->approved_date,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_amendment' => $this->is_amendment,
            'is_addendum' => $this->is_addendum,
            'is_renewal' => $this->is_renewal,
            'is_extension' => $this->is_extension,
            'is_revision' => $this->is_revision,
            'is_termination' => $this->is_termination,
            'parent_id' => $this->parent_id,
            'tender_id' => $this->tender_id
        ];
    }
}
