<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpEmployeesDepartmentsResource extends JsonResource
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
            'employeesDepartmentsID' => $this->employeesDepartmentsID,
            'employeeSystemID' => $this->employeeSystemID,
            'employeeID' => $this->employeeID,
            'employeeGroupID' => $this->employeeGroupID,
            'companySystemID' => $this->companySystemID,
            'companyId' => $this->companyId,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'departmentSystemID' => $this->departmentSystemID,
            'departmentID' => $this->departmentID,
            'ServiceLineSystemID' => $this->ServiceLineSystemID,
            'ServiceLineID' => $this->ServiceLineID,
            'warehouseSystemCode' => $this->warehouseSystemCode,
            'reportingManagerID' => $this->reportingManagerID,
            'isDefault' => $this->isDefault,
            'dischargedYN' => $this->dischargedYN,
            'approvalDeligated' => $this->approvalDeligated,
            'approvalDeligatedFromEmpID' => $this->approvalDeligatedFromEmpID,
            'approvalDeligatedFrom' => $this->approvalDeligatedFrom,
            'approvalDeligatedTo' => $this->approvalDeligatedTo,
            'dmsIsUploadEnable' => $this->dmsIsUploadEnable,
            'isActive' => $this->isActive,
            'activatedDate' => $this->activatedDate,
            'activatedByEmpID' => $this->activatedByEmpID,
            'activatedByEmpSystemID' => $this->activatedByEmpSystemID,
            'removedYN' => $this->removedYN,
            'removedByEmpID' => $this->removedByEmpID,
            'removedByEmpSystemID' => $this->removedByEmpSystemID,
            'removedDate' => $this->removedDate,
            'createdDate' => $this->createdDate,
            'createdByEmpSystemID' => $this->createdByEmpSystemID,
            'timeStamp' => $this->timeStamp,
            'deligateDetaileID' => $this->deligateDetaileID
        ];
    }
}
