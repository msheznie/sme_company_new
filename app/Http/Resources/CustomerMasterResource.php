<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerMasterResource extends JsonResource
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
            'customerCodeSystem' => $this->customerCodeSystem,
            'primaryCompanySystemID' => $this->primaryCompanySystemID,
            'primaryCompanyID' => $this->primaryCompanyID,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'lastSerialOrder' => $this->lastSerialOrder,
            'CutomerCode' => $this->CutomerCode,
            'customerShortCode' => $this->customerShortCode,
            'customerCategoryID' => $this->customerCategoryID,
            'custGLAccountSystemID' => $this->custGLAccountSystemID,
            'custGLaccount' => $this->custGLaccount,
            'custUnbilledAccountSystemID' => $this->custUnbilledAccountSystemID,
            'custUnbilledAccount' => $this->custUnbilledAccount,
            'CustomerName' => $this->CustomerName,
            'customerSecondLanguage' => $this->customerSecondLanguage,
            'ReportTitle' => $this->ReportTitle,
            'reportTitleSecondLanguage' => $this->reportTitleSecondLanguage,
            'customerAddress1' => $this->customerAddress1,
            'addressOneSecondLanguage' => $this->addressOneSecondLanguage,
            'customerAddress2' => $this->customerAddress2,
            'addressTwoSecondLanguage' => $this->addressTwoSecondLanguage,
            'customerCity' => $this->customerCity,
            'customerCountry' => $this->customerCountry,
            'CustWebsite' => $this->CustWebsite,
            'creditLimit' => $this->creditLimit,
            'creditDays' => $this->creditDays,
            'customerLogo' => $this->customerLogo,
            'interCompanyYN' => $this->interCompanyYN,
            'companyLinkedToSystemID' => $this->companyLinkedToSystemID,
            'companyLinkedTo' => $this->companyLinkedTo,
            'isCustomerActive' => $this->isCustomerActive,
            'isAllowedQHSE' => $this->isAllowedQHSE,
            'vendorCode' => $this->vendorCode,
            'vatEligible' => $this->vatEligible,
            'vatNumber' => $this->vatNumber,
            'vatPercentage' => $this->vatPercentage,
            'isSupplierForiegn' => $this->isSupplierForiegn,
            'approvedYN' => $this->approvedYN,
            'approvedEmpSystemID' => $this->approvedEmpSystemID,
            'approvedEmpID' => $this->approvedEmpID,
            'approvedDate' => $this->approvedDate,
            'approvedComment' => $this->approvedComment,
            'confirmedYN' => $this->confirmedYN,
            'confirmedEmpSystemID' => $this->confirmedEmpSystemID,
            'confirmedEmpID' => $this->confirmedEmpID,
            'confirmedEmpName' => $this->confirmedEmpName,
            'confirmedDate' => $this->confirmedDate,
            'RollLevForApp_curr' => $this->RollLevForApp_curr,
            'refferedBackYN' => $this->refferedBackYN,
            'timesReferred' => $this->timesReferred,
            'createdUserGroup' => $this->createdUserGroup,
            'createdUserID' => $this->createdUserID,
            'createdDateTime' => $this->createdDateTime,
            'createdPcID' => $this->createdPcID,
            'modifiedPc' => $this->modifiedPc,
            'modifiedUser' => $this->modifiedUser,
            'timeStamp' => $this->timeStamp,
            'createdFrom' => $this->createdFrom,
            'consignee_name' => $this->consignee_name,
            'consignee_address' => $this->consignee_address,
            'payment_terms' => $this->payment_terms,
            'consignee_contact_no' => $this->consignee_contact_no,
            'customer_registration_no' => $this->customer_registration_no,
            'customer_registration_expiry_date' => $this->customer_registration_expiry_date,
            'custAdvanceAccountSystemID' => $this->custAdvanceAccountSystemID,
            'custAdvanceAccount' => $this->custAdvanceAccount
        ];
    }
}
