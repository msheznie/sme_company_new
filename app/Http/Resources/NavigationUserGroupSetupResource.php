<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NavigationUserGroupSetupResource extends JsonResource
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
            'userGroupID' => $this->userGroupID,
            'companyID' => $this->companyID,
            'navigationMenuID' => $this->navigationMenuID,
            'description' => $this->description,
            'masterID' => $this->masterID,
            'url' => $this->url,
            'pageID' => $this->pageID,
            'pageTitle' => $this->pageTitle,
            'pageIcon' => $this->pageIcon,
            'levelNo' => $this->levelNo,
            'sortOrder' => $this->sortOrder,
            'isSubExist' => $this->isSubExist,
            'readonly' => $this->readonly,
            'create' => $this->create,
            'update' => $this->update,
            'delete' => $this->delete,
            'print' => $this->print,
            'export' => $this->export,
            'timestamp' => $this->timestamp,
            'isPortalYN' => $this->isPortalYN,
            'externalLink' => $this->externalLink
        ];
    }
}
