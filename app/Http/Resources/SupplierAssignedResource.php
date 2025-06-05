<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierAssignedResource extends JsonResource
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
            'supplierAssignedID' => $this->supplierAssignedID,
            'supplierCodeSytem' => $this->supplierCodeSytem,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'uniqueTextcode' => $this->uniqueTextcode,
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
            'supplierImportanceID' => $this->supplierImportanceID,
            'supplierNatureID' => $this->supplierNatureID,
            'supplierTypeID' => $this->supplierTypeID,
            'WHTApplicable' => $this->WHTApplicable,
            'vatEligible' => $this->vatEligible,
            'vatNumber' => $this->vatNumber,
            'vatPercentage' => $this->vatPercentage,
            'supCategoryICVMasterID' => $this->supCategoryICVMasterID,
            'supCategorySubICVID' => $this->supCategorySubICVID,
            'isLCCYN' => $this->isLCCYN,
            'isMarkupPercentage' => $this->isMarkupPercentage,
            'markupPercentage' => $this->markupPercentage,
            'isRelatedPartyYN' => $this->isRelatedPartyYN,
            'isCriticalYN' => $this->isCriticalYN,
            'jsrsNo' => $this->jsrsNo,
            'jsrsExpiry' => $this->jsrsExpiry,
            'isActive' => $this->isActive,
            'isAssigned' => $this->isAssigned,
            'timestamp' => $this->timestamp,
            'isBlocked' => $this->isBlocked,
            'blockedBy' => $this->blockedBy,
            'blockedDate' => $this->blockedDate,
            'blockedReason' => $this->blockedReason,
            'createdFrom' => $this->createdFrom,
            'advanceAccountSystemID' => $this->advanceAccountSystemID,
            'AdvanceAccount' => $this->AdvanceAccount
        ];
    }
}
