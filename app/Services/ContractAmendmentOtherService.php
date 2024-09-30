<?php

namespace App\Services;

use App\Helpers\General;
use App\Models\ContractPaymentTerms;
use App\Models\ContractPaymentTermsAmd;
use App\Utilities\ContractManagementUtils;

class ContractAmendmentOtherService
{
    public static function getContractPaymentTermAmd($contractHistoryUuid, $amdUuid)
    {
        $contractHistory = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
        if(empty($contractHistory))
        {
            GeneralService::sendException('Contract history not found.');
        }
        return ContractPaymentTermsAmd::getContractPaymentTermAmd($contractHistory['id'], $amdUuid);
    }
    public static function updateContractPaymentTerms($contractHistoryId,$contractId)
    {
        try
        {
            $amdRecords = ContractPaymentTermsAmd::getContractPaymentTermsAmd($contractHistoryId, true);
            $newPaymentTermsAmd = ContractPaymentTermsAmd::newContractPaymentTermsAmd($contractHistoryId);
            $amdRecordIds = $amdRecords->pluck('id');

            $amdRecords->each(function ($amdRecord)
            {
                $masterRecord = ContractPaymentTerms::find($amdRecord->id);

                if ($masterRecord)
                {
                    $masterRecord->fill($amdRecord->toArray());
                    $masterRecord->save();
                }
            });

            ContractPaymentTerms::deletePaymentTermsFromAmd($amdRecordIds, $contractId);

            $newPaymentTermsAmd->each(function ($newPaymentTerm) use ($contractId)
            {
                ContractPaymentTerms::create([
                    'uuid' => $newPaymentTerm->uuid,
                    'contract_id' => $contractId,
                    'title' => $newPaymentTerm->title,
                    'description' => $newPaymentTerm->description,
                    'company_id' => $newPaymentTerm->company_id,
                    'created_by' => General::currentEmployeeId(),
                ]);
            });

        } catch (\Exception $ex)
        {
            GeneralService::sendException($ex->getMessage());
        }
    }
}
