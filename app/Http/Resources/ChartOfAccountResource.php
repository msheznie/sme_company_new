<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountResource extends JsonResource
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
            'chartOfAccountSystemID' => $this->chartOfAccountSystemID,
            'primaryCompanySystemID' => $this->primaryCompanySystemID,
            'primaryCompanyID' => $this->primaryCompanyID,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'AccountCode' => $this->AccountCode,
            'AccountDescription' => $this->AccountDescription,
            'masterAccount' => $this->masterAccount,
            'catogaryBLorPLID' => $this->catogaryBLorPLID,
            'catogaryBLorPL' => $this->catogaryBLorPL,
            'controllAccountYN' => $this->controllAccountYN,
            'controlAccountsSystemID' => $this->controlAccountsSystemID,
            'controlAccounts' => $this->controlAccounts,
            'isApproved' => $this->isApproved,
            'approvedBySystemID' => $this->approvedBySystemID,
            'approvedBy' => $this->approvedBy,
            'approvedDate' => $this->approvedDate,
            'approvedComment' => $this->approvedComment,
            'isActive' => $this->isActive,
            'isBank' => $this->isBank,
            'AllocationID' => $this->AllocationID,
            'relatedPartyYN' => $this->relatedPartyYN,
            'interCompanySystemID' => $this->interCompanySystemID,
            'interCompanyID' => $this->interCompanyID,
            'confirmedYN' => $this->confirmedYN,
            'confirmedEmpSystemID' => $this->confirmedEmpSystemID,
            'confirmedEmpID' => $this->confirmedEmpID,
            'confirmedEmpName' => $this->confirmedEmpName,
            'confirmedEmpDate' => $this->confirmedEmpDate,
            'isMasterAccount' => $this->isMasterAccount,
            'RollLevForApp_curr' => $this->RollLevForApp_curr,
            'refferedBackYN' => $this->refferedBackYN,
            'timesReferred' => $this->timesReferred,
            'createdPcID' => $this->createdPcID,
            'createdUserGroup' => $this->createdUserGroup,
            'createdUserID' => $this->createdUserID,
            'createdDateTime' => $this->createdDateTime,
            'modifiedPc' => $this->modifiedPc,
            'modifiedUser' => $this->modifiedUser,
            'timestamp' => $this->timestamp,
            'reportTemplateCategory' => $this->reportTemplateCategory
        ];
    }
}
