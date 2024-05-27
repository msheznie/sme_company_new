<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErpApprovalLevelResource extends JsonResource
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
            'approvalLevelID' => $this->approvalLevelID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'departmentSystemID' => $this->departmentSystemID,
            'departmentID' => $this->departmentID,
            'serviceLineWise' => $this->serviceLineWise,
            'serviceLineSystemID' => $this->serviceLineSystemID,
            'serviceLineCode' => $this->serviceLineCode,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'levelDescription' => $this->levelDescription,
            'noOfLevels' => $this->noOfLevels,
            'valueWise' => $this->valueWise,
            'valueFrom' => $this->valueFrom,
            'valueTo' => $this->valueTo,
            'isCategoryWiseApproval' => $this->isCategoryWiseApproval,
            'categoryID' => $this->categoryID,
            'isActive' => $this->isActive,
            'is_deleted' => $this->is_deleted,
            'timeStamp' => $this->timeStamp
        ];
    }
}
