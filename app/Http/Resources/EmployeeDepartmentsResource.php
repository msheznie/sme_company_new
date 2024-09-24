<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDepartmentsResource extends JsonResource
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
            'EmpDepartmentID' => $this->EmpDepartmentID,
            'EmpID' => $this->EmpID,
            'DepartmentMasterID' => $this->DepartmentMasterID,
            'isPrimary' => $this->isPrimary,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'Erp_companyID' => $this->Erp_companyID,
            'SchMasterID' => $this->SchMasterID,
            'BranchID' => $this->BranchID,
            'AcademicYearID' => $this->AcademicYearID,
            'isActive' => $this->isActive,
            'CreatedUserName' => $this->CreatedUserName,
            'CreatedDate' => $this->CreatedDate,
            'CreatedPC' => $this->CreatedPC,
            'ModifiedUserName' => $this->ModifiedUserName,
            'Timestamp' => $this->Timestamp,
            'ModifiedPC' => $this->ModifiedPC
        ];
    }
}
