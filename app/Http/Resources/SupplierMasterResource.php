<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierMasterResource extends JsonResource
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
            'supplierCodeSystem' => $this->supplierCodeSystem,
            'uniqueTextcode' => $this->uniqueTextcode,
            'primaryCompanySystemID' => $this->primaryCompanySystemID,
            'primaryCompanyID' => $this->primaryCompanyID,
            'documentSystemID' => $this->documentSystemID,
            'documentID' => $this->documentID,
            'primarySupplierCode' => $this->primarySupplierCode,
            'secondarySupplierCode' => $this->secondarySupplierCode,
            'supplierName' => $this->supplierName,
            'liabilityAccountSysemID' => $this->liabilityAccountSysemID,
            'liabilityAccount' => $this->liabilityAccount,
            'UnbilledGRVAccountSystemID' => $this->UnbilledGRVAccountSystemID,
            'UnbilledGRVAccount' => $this->UnbilledGRVAccount,
            'address' => $this->address,
            'countryID' => $this->countryID,
            'supplierCountryID' => $this->supplierCountryID,
            'telephone' => $this->telephone,
            'fax' => $this->fax,
            'supEmail' => $this->supEmail,
            'webAddress' => $this->webAddress,
            'currency' => $this->currency,
            'nameOnPaymentCheque' => $this->nameOnPaymentCheque,
            'creditLimit' => $this->creditLimit,
            'creditPeriod' => $this->creditPeriod,
            'supCategoryMasterID' => $this->supCategoryMasterID,
            'supCategorySubID' => $this->supCategorySubID,
            'supplier_category_id' => $this->supplier_category_id,
            'supplier_group_id' => $this->supplier_group_id,
            'registrationNumber' => $this->registrationNumber,
            'registrationExprity' => $this->registrationExprity,
            'approvedYN' => $this->approvedYN,
            'approvedEmpSystemID' => $this->approvedEmpSystemID,
            'approvedby' => $this->approvedby,
            'approvedDate' => $this->approvedDate,
            'approvedComment' => $this->approvedComment,
            'isActive' => $this->isActive,
            'isSupplierForiegn' => $this->isSupplierForiegn,
            'supplierConfirmedYN' => $this->supplierConfirmedYN,
            'supplierConfirmedEmpID' => $this->supplierConfirmedEmpID,
            'supplierConfirmedEmpSystemID' => $this->supplierConfirmedEmpSystemID,
            'supplierConfirmedEmpName' => $this->supplierConfirmedEmpName,
            'supplierConfirmedDate' => $this->supplierConfirmedDate,
            'isCriticalYN' => $this->isCriticalYN,
            'interCompanyYN' => $this->interCompanyYN,
            'companyLinkedToSystemID' => $this->companyLinkedToSystemID,
            'companyLinkedTo' => $this->companyLinkedTo,
            'linkCustomerYN' => $this->linkCustomerYN,
            'linkCustomerID' => $this->linkCustomerID,
            'createdUserGroup' => $this->createdUserGroup,
            'createdPcID' => $this->createdPcID,
            'createdUserID' => $this->createdUserID,
            'modifiedPc' => $this->modifiedPc,
            'modifiedUser' => $this->modifiedUser,
            'createdDateTime' => $this->createdDateTime,
            'createdFrom' => $this->createdFrom,
            'isDirect' => $this->isDirect,
            'supplierImportanceID' => $this->supplierImportanceID,
            'supplierNatureID' => $this->supplierNatureID,
            'supplierTypeID' => $this->supplierTypeID,
            'WHTApplicable' => $this->WHTApplicable,
            'vatEligible' => $this->vatEligible,
            'vatNumber' => $this->vatNumber,
            'vatPercentage' => $this->vatPercentage,
            'retentionPercentage' => $this->retentionPercentage,
            'supCategoryICVMasterID' => $this->supCategoryICVMasterID,
            'supCategorySubICVID' => $this->supCategorySubICVID,
            'isLCCYN' => $this->isLCCYN,
            'isSMEYN' => $this->isSMEYN,
            'isMarkupPercentage' => $this->isMarkupPercentage,
            'markupPercentage' => $this->markupPercentage,
            'RollLevForApp_curr' => $this->RollLevForApp_curr,
            'refferedBackYN' => $this->refferedBackYN,
            'timesReferred' => $this->timesReferred,
            'jsrsNo' => $this->jsrsNo,
            'jsrsExpiry' => $this->jsrsExpiry,
            'timestamp' => $this->timestamp,
            'createdUserSystemID' => $this->createdUserSystemID,
            'modifiedUserSystemID' => $this->modifiedUserSystemID,
            'isBlocked' => $this->isBlocked,
            'blockedBy' => $this->blockedBy,
            'blockedDate' => $this->blockedDate,
            'blockedReason' => $this->blockedReason,
            'last_activity' => $this->last_activity,
            'advanceAccountSystemID' => $this->advanceAccountSystemID,
            'AdvanceAccount' => $this->AdvanceAccount
        ];
    }
}
