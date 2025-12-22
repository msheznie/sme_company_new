<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesResource extends JsonResource
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
            'employeeSystemID' => $this->employeeSystemID,
            'empID' => $this->empID,
            'hrmsEmpID' => $this->hrmsEmpID,
            'serial' => $this->serial,
            'empLeadingText' => $this->empLeadingText,
            'empUserName' => $this->empUserName,
            'empTitle' => $this->empTitle,
            'empInitial' => $this->empInitial,
            'empName' => $this->empName,
            'empName_O' => $this->empName_O,
            'empFullName' => $this->empFullName,
            'empSurname' => $this->empSurname,
            'empSurname_O' => $this->empSurname_O,
            'empFirstName' => $this->empFirstName,
            'empFirstName_O' => $this->empFirstName_O,
            'empFamilyName' => $this->empFamilyName,
            'empFamilyName_O' => $this->empFamilyName_O,
            'empFatherName' => $this->empFatherName,
            'empFatherName_O' => $this->empFatherName_O,
            'empManagerAttached' => $this->empManagerAttached,
            'empDateRegistered' => $this->empDateRegistered,
            'empTelOffice' => $this->empTelOffice,
            'empTelMobile' => $this->empTelMobile,
            'empLandLineNo' => $this->empLandLineNo,
            'extNo' => $this->extNo,
            'empFax' => $this->empFax,
            'empEmail' => $this->empEmail,
            'empLocation' => $this->empLocation,
            'empDateTerminated' => $this->empDateTerminated,
            'empLoginActive' => $this->empLoginActive,
            'empActive' => $this->empActive,
            'userGroupID' => $this->userGroupID,
            'empCompanySystemID' => $this->empCompanySystemID,
            'empCompanyID' => $this->empCompanyID,
            'religion' => $this->religion,
            'isLoggedIn' => $this->isLoggedIn,
            'isLoggedOutFailYN' => $this->isLoggedOutFailYN,
            'logingFlag' => $this->logingFlag,
            'isSuperAdmin' => $this->isSuperAdmin,
            'discharegedYN' => $this->discharegedYN,
            'isFinalSettlementDone' => $this->isFinalSettlementDone,
            'hrusergroupID' => $this->hrusergroupID,
            'employmentType' => $this->employmentType,
            'isConsultant' => $this->isConsultant,
            'isTrainee' => $this->isTrainee,
            'is3rdParty' => $this->is3rdParty,
            '3rdPartyCompanyName' => $this->3rdPartyCompanyName,
            'gender' => $this->gender,
            'designation' => $this->designation,
            'nationality' => $this->nationality,
            'isManager' => $this->isManager,
            'isApproval' => $this->isApproval,
            'isDashBoard' => $this->isDashBoard,
            'isAdmin' => $this->isAdmin,
            'isBasicUser' => $this->isBasicUser,
            'ActivationCode' => $this->ActivationCode,
            'ActivationFlag' => $this->ActivationFlag,
            'isHR_admin' => $this->isHR_admin,
            'isLock' => $this->isLock,
            'basicDataIngCount' => $this->basicDataIngCount,
            'opRptManagerAccess' => $this->opRptManagerAccess,
            'isSupportAdmin' => $this->isSupportAdmin,
            'isHSEadmin' => $this->isHSEadmin,
            'excludeObjectivesYN' => $this->excludeObjectivesYN,
            'machineID' => $this->machineID,
            'timestamp' => $this->timestamp,
            'createdFrom' => $this->createdFrom,
            'isNewPortal' => $this->isNewPortal,
            'uuid' => $this->uuid
        ];
    }
}
