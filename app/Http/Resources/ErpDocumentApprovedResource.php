<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpDocumentApprovedResource extends JsonResource
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
            'documentApprovedID' => $this->documentApprovedID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'departmentSystemID' => $this->departmentSystemID,
            'departmentID' => $this->departmentID,
            'serviceLineSystemID' => $this->serviceLineSystemID,
            'serviceLineCode' => $this->serviceLineCode,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'documentSystemCode' => $this->documentSystemCode,
            'documentCode' => $this->documentCode,
            'documentDate' => $this->documentDate,
            'approvalLevelID' => $this->approvalLevelID,
            'rollID' => $this->rollID,
            'approvalGroupID' => $this->approvalGroupID,
            'rollLevelOrder' => $this->rollLevelOrder,
            'employeeSystemID' => $this->employeeSystemID,
            'employeeID' => $this->employeeID,
            'docConfirmedDate' => $this->docConfirmedDate,
            'docConfirmedByEmpSystemID' => $this->docConfirmedByEmpSystemID,
            'docConfirmedByEmpID' => $this->docConfirmedByEmpID,
            'preRollApprovedDate' => $this->preRollApprovedDate,
            'approvedYN' => $this->approvedYN,
            'approvedDate' => $this->approvedDate,
            'approvedComments' => $this->approvedComments,
            'rejectedYN' => $this->rejectedYN,
            'rejectedDate' => $this->rejectedDate,
            'rejectedComments' => $this->rejectedComments,
            'myApproveFlag' => $this->myApproveFlag,
            'isDeligationApproval' => $this->isDeligationApproval,
            'approvedForEmpID' => $this->approvedForEmpID,
            'isApprovedFromPC' => $this->isApprovedFromPC,
            'approvedPCID' => $this->approvedPCID,
            'reference_email' => $this->reference_email,
            'timeStamp' => $this->timeStamp,
            'status' => $this->status
        ];
    }
}
