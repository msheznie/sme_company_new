<?php

namespace App\Services;

use App\Helpers\General;
use App\Models\CMContractMileStoneAmd;
use App\Models\CMContractOverallRetentionAmd;
use App\Models\ContractMilestoneRetention;
use App\Models\ContractMilestoneRetentionAmd;
use App\Models\ContractPaymentTerms;
use App\Models\ContractPaymentTermsAmd;
use App\Models\ContractSettingDetail;
use App\Utilities\ContractManagementUtils;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
    public static function getMilestoneRetentionAmd($contractHistoryUuid, $amdUuid)
    {
        $contractHistory = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
        if(empty($contractHistory))
        {
            GeneralService::sendException('Contract history not found.');
        }
        return ContractMilestoneRetentionAmd::getMilestoneRetentionAmdForUpdate($amdUuid, $contractHistory['id']);
    }
    public static function getMilestoneAmountAmd($contractHistoryID)
    {
        return CMContractMileStoneAmd::getMilestoneAmountAmd($contractHistoryID);
    }
    public function getMilestoneRetentionAmdCount($contractUuid, $companySystemID)
    {
        return ContractMilestoneRetentionAmd::getMilestoneRetentionAmdCount($contractUuid, $companySystemID);
    }
    public static function paymentTermsAmdValidation($contractHistoryID, $activeSectionIDs)
    {
        if (in_array(6, $activeSectionIDs))
        {
            $paymentTerms = ContractPaymentTermsAmd::getContractPaymentTermsAmd($contractHistoryID);
            if(count($paymentTerms) == 0)
            {
                GeneralService::sendException('At least one milestone payment schedule should be available to send the
                 contract history for approval');
            }
        }
    }
    public static function milestoneAndOverallRetentionValidation($contractHistoryID, $contractMaster, $contractId,
                                                           $activeSectionIDs, $companySystemID)
    {
        $activeSectionDetail = ContractSettingDetail::getActiveSections($contractId);
        $activeSectionDetailsIDs = collect($activeSectionDetail)->pluck('sectionDetailId')->toArray();
        $contractStartDate = Carbon::parse($contractMaster['startDate'])->format('Y-m-d');
        $contractEndDate = Carbon::parse($contractMaster['endDate'])->format('Y-m-d');

        if (in_array(4, $activeSectionIDs))
        {
            if(in_array(4, $activeSectionDetailsIDs))
            {
                $checkValidRetentionStartDate = CMContractOverallRetentionAmd::checkValidDate(
                    $contractHistoryID, true, $contractStartDate
                );
                if($checkValidRetentionStartDate)
                {
                    GeneralService::sendException('Overall retention start date should be equal to or after the
                     contract commencement date');
                }
                $checkValidRetentionDueDate = CMContractOverallRetentionAmd::checkValidDate(
                    $contractHistoryID, false, $contractEndDate
                );
                if($checkValidRetentionDueDate)
                {
                    GeneralService::sendException('Overall retention due date should be below or equal to the
                     contract end date');
                }
            }
            else
            {
                $checkMilestoneExists = ContractMilestoneRetentionAmd::getContractMilestoneRetentionAmd(
                    $contractHistoryID, $contractId, $companySystemID
                );
                if(empty($checkMilestoneExists))
                {
                    GeneralService::sendException(trans('common.at_least_one_milestone_retention_should_be_available'));
                }
                $checkMRIsFilled = ContractMilestoneRetentionAmd::checkMilestoneRetentionFilled($contractHistoryID,
                    $companySystemID);
                if($checkMRIsFilled)
                {
                    GeneralService::sendException('Please fill all required fields in milestone retention.');
                }

                $checkValidRetentionStartDate = ContractMilestoneRetentionAmd::checkValidDate(
                    $contractHistoryID, true, $contractStartDate
                );
                if($checkValidRetentionStartDate)
                {
                    GeneralService::sendException('Milestone retention start date should be equal to or after the
                     contract commencement date');
                }
                $checkValidRetentionDueDate = ContractMilestoneRetentionAmd::checkValidDate(
                    $contractHistoryID, false, $contractEndDate
                );
                if($checkValidRetentionDueDate)
                {
                    GeneralService::sendException('Milestone retention due date should be below or equal to the
                     contract end date');
                }
            }
        }
        return true;
    }
    public static function updateContractMilestoneRetention($contractHistoryId,$contractId, $companySystemID)
    {
        try
        {
            $amdRecords = ContractMilestoneRetentionAmd::getContractMilestoneRetentionAmd(
                $contractHistoryId, $contractId, $companySystemID, true);
            $newMRetentionAmds = ContractMilestoneRetentionAmd::newContractMilestoneRetentionAmd($contractHistoryId);
            $amdRecordIds = $amdRecords->pluck('id');

            $amdRecords->each(function ($amdRecord)
            {
                $masterRecord = ContractMilestoneRetention::find($amdRecord->id);

                if ($masterRecord)
                {
                    $masterRecord->fill($amdRecord->toArray());
                    $masterRecord->save();
                }
            });

            ContractMilestoneRetention::deleteMilestoneRetention($amdRecordIds, $contractId);

            $newMRetentionAmds->each(function ($newMRetentionAmd) use ($contractId)
            {
                ContractMilestoneRetention::create([
                    'uuid' => $newMRetentionAmd->uuid,
                    'contractId' => $contractId,
                    'milestoneId' => $newMRetentionAmd->milestoneId,
                    'retentionPercentage' => $newMRetentionAmd->retentionPercentage,
                    'retentionAmount' => $newMRetentionAmd->retentionAmount ?? 0,
                    'startDate' => $newMRetentionAmd->startDate,
                    'dueDate' => $newMRetentionAmd->dueDate,
                    'withholdPeriod' => $newMRetentionAmd->withholdPeriod,
                    'paymentStatus' => $newMRetentionAmd->paymentStatus,
                    'companySystemId' => $newMRetentionAmd->companySystemId,
                    'created_by' => General::currentEmployeeId(),
                ]);
            });

        } catch (\Exception $ex)
        {
            GeneralService::sendException($ex->getMessage());
        }
    }

}
