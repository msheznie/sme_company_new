<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpApprovalRoleResource extends JsonResource
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
            'rollMasterID' => $this->rollMasterID,
            'rollDescription' => $this->rollDescription,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'departmentSystemID' => $this->departmentSystemID,
            'departmentID' => $this->departmentID,
            'serviceLineSystemID' => $this->serviceLineSystemID,
            'serviceLineID' => $this->serviceLineID,
            'rollLevel' => $this->rollLevel,
            'approvalLevelID' => $this->approvalLevelID,
            'approvalGroupID' => $this->approvalGroupID,
            'timeStamp' => $this->timeStamp
        ];
    }
}
