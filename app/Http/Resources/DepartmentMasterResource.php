<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentMasterResource extends JsonResource
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
            'DepartmentMasterID' => $this->DepartmentMasterID,
            'DepartmentDes' => $this->DepartmentDes,
            'parent_department_id' => $this->parent_department_id,
            'is_root_department' => $this->is_root_department,
            'Erp_companyID' => $this->Erp_companyID,
            'SchMasterID' => $this->SchMasterID,
            'BranchID' => $this->BranchID,
            'SortOrder' => $this->SortOrder,
            'hod_id' => $this->hod_id,
            'isActive' => $this->isActive,
            'created_by' => $this->created_by,
            'CreatedUserName' => $this->CreatedUserName,
            'CreatedDate' => $this->CreatedDate,
            'CreatedPC' => $this->CreatedPC,
            'ModifiedUserName' => $this->ModifiedUserName,
            'Timestamp' => $this->Timestamp,
            'ModifiedPC' => $this->ModifiedPC
        ];
    }
}
