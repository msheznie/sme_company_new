<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyPolicyMasterResource extends JsonResource
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
            'companyPolicyMasterAutoID' => $this->companyPolicyMasterAutoID,
            'companyPolicyCategoryID' => $this->companyPolicyCategoryID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'documentID' => $this->documentID,
            'isYesNO' => $this->isYesNO,
            'policyValue' => $this->policyValue,
            'createdByUserID' => $this->createdByUserID,
            'createdByUserName' => $this->createdByUserName,
            'createdByPCID' => $this->createdByPCID,
            'modifiedByUserID' => $this->modifiedByUserID,
            'createdDateTime' => $this->createdDateTime,
            'timestamp' => $this->timestamp
        ];
    }
}
