<?php

namespace App\Services;

use App\Helpers\Email;
use App\Models\CMContractScenarioAssign;
use App\Models\ContractHistory;
use App\Models\ContractMaster;
use App\Models\ContractUserAssign;
use App\Services\ContractHistoryService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;

class ActivateContractService
{
    public static function activateContract()
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $contractList = ContractMaster::getCurrentInactiveContract($todayDate);
        $contractListChild = ContractHistory::getInActiveChildData($todayDate);
        Log::info('API Email send start');
        self::processContracts($contractList);
        if(!empty($contractListChild))
        {

            foreach ($contractListChild as $value)
            {
                $status = ContractHistoryService::checkContractDateBetween(
                    $value->contractMaster->startDate,
                    $value->contractMaster->endDate
                );
                ContractHistoryService::updateOrInsertStatus
                (
                    $value->contract_id, $status, $value->company_id, null,true
                );

                self::updateContractChildMaster($status, $value->company_id, $value->contract_id);
            }
        }


    }

    private static function processContracts($contractList)
    {
        if ($contractList && $contractList->isNotEmpty())
        {
            $contractIds = $contractList->pluck('id')->toArray();
            ContractMaster::whereIn('id', $contractIds)->update(['status' => -1]);

            foreach ($contractList as $contract)
            {
                $contractId = $contract->id;
                $currentStatus = -1;
                $companyId = $contract->companySystemID;
                ContractHistoryService::insertHistoryStatus($contractId, $currentStatus, $companyId, null, true);
            }
        }
    }

    public function updateContractChildMaster($status, $companyId, $contractId)
    {
            $data = [
                'status'  => $status
            ];

            ContractMaster::where('companySystemID', $companyId)
                ->where('id', $contractId)
                ->update($data);

    }

    public static function reminderContractExpiry()
    {
        $results = CMContractScenarioAssign::getReminderContractExpiryBefore();
        $contractIds = $results->pluck('contract_id')->toArray();
        $userDetails = ContractUserAssign::getReminderContractExpiryUsers($contractIds);

        $existingUserIds = $userDetails->pluck('userId')->toArray();

        foreach ($results as $result)
        {
            $contractOwnerId = $result->contractMaster->contractOwner;
            $counterPartyNameId = $result->contractMaster->counterPartyName;

            if ($contractOwnerId && !in_array($contractOwnerId, $existingUserIds))
            {
                $userDetails->push([
                    'userId' => $contractOwnerId,
                    'contractId' => $result->contract_id,
                    'contractCode' => $result->contractMaster->contractCode,
                    'title' => $result->contractMaster->title,
                    'contractOwner' => $result->contractMaster->contractOwner,
                    'counterPartyName' => $result->contractMaster->counterPartyName,
                    'companySystemID' => $result->contractMaster->companySystemID,
                    'endDate' => $result->contractMaster->endDate,
                ]);
            }

            if ($counterPartyNameId && !in_array($counterPartyNameId, $existingUserIds))
            {
                $userDetails->push([
                    'userId' => $counterPartyNameId,
                    'contractId' => $result->contract_id,
                    'contractCode' => $result->contractMaster->contractCode,
                    'title' => $result->contractMaster->title,
                    'contractOwner' => $result->contractMaster->contractOwner,
                    'counterPartyName' => $result->contractMaster->counterPartyName,
                    'companySystemID' => $result->contractMaster->companySystemID,
                    'endDate' => $result->contractMaster->endDate,
                ]);
            }
        }
        self::sendEmail($userDetails, 1);
    }

    public static function reminderContractExpiryAfter()
    {
        $results = CMContractScenarioAssign::getReminderContractExpiryAfter();

        $contractIds = $results->pluck('contract_id')->toArray();

        $userDetails = ContractUserAssign::getReminderContractExpiryUsers($contractIds);

        $userIds = $userDetails->pluck('userId')->toArray();

        $additionalDetails = $results->map(function ($result) use ($userIds)
        {
            $contractOwner = $result->contractMaster->contractOwner;
            $counterPartyName = $result->contractMaster->counterPartyName;

            $details = [];

            if ($contractOwner && !in_array($contractOwner, $userIds))
            {
                $details[] = [
                    'userId' => $contractOwner,
                    'contractId' => $result->contractMaster->id,
                    'contractCode' => $result->contractMaster->contractCode,
                    'title' => $result->contractMaster->title,
                    'contractOwner' => $contractOwner,
                    'counterPartyName' => $result->contractMaster->counterPartyName,
                    'companySystemID' => $result->contractMaster->companySystemID,
                    'endDate' => $result->contractMaster->endDate,
                ];
                $userIds[] = $contractOwner;
            }

            if ($counterPartyName && !in_array($counterPartyName, $userIds))
            {
                $details[] = [
                    'userId' => $counterPartyName,
                    'contractId' => $result->contractMaster->id,
                    'contractCode' => $result->contractMaster->contractCode,
                    'title' => $result->contractMaster->title,
                    'contractOwner' => $result->contractMaster->contractOwner,
                    'counterPartyName' => $counterPartyName,
                    'companySystemID' => $result->contractMaster->companySystemID,
                    'endDate' => $result->contractMaster->endDate,
                ];
                $userIds[] = $counterPartyName;
            }

            return $details;
        })->flatten(1);

        $userDetails = $userDetails->merge($additionalDetails);
        self::sendEmail($userDetails, 2);
    }

    public static function sendEmail($userDetails, $type)
    {
        $emails = [];
        if($type == 1)
        {
            $subject = 'Contract Expiry Notice';
        } else
        {
            $subject = 'Contract Expired';
        }


        foreach ($userDetails as $dt)
        {
            $contractCode = $dt['contractCode'];
            $title = $dt['title'] ?? 'N/A';
            $endDate = $dt['endDate'] ?? 'N/A';
            if ($endDate !== 'N/A')
            {
                $date = new DateTime($endDate);
                $endDateFormatted = $date->format('Y-m-d');
            }
            else
            {
                $endDateFormatted = 'N/A';
            }
            $userId = $dt['userId'] ?? 'N/A';
            $companySystemID = $dt['companySystemID'];

            if($type == 1)
            {
                $body = "<p>
            This is a reminder that Contract Code " . $contractCode . ", titled " . $title .
                    ", will expire on " . $endDateFormatted .
                    ". Please review and renew as necessary to ensure continued service.</p>
        <p>Thank you</p>";
            }
            else
            {
                $body = "<p>Please be reminded that Contract ID " . $contractCode . ", titled " . $title .",
                has expired on " . $endDateFormatted .".
                Kindly review and renew the contract at your earliest convenience to
                ensure uninterrupted service.</p>
                        <p>Thank you</p>";
            }

            $emails[] = [
                'empSystemID' => $userId,
                'companySystemID' => $companySystemID,
                'alertMessage' => $subject,
                'emailAlertMessage' => $body,
                'error_tag' => $subject,
                'error_msg' => '<b>The Employee: ' . $userId .
                    '</b>- Mail ID is invalid!',
                'db' =>  ""
            ];
        }
        Email::sendBulkEmailSupplier($emails);
        return true;
    }
}
