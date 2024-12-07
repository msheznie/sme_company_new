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
        $contractList = ContractMaster::getCurrentInactiveContract();
        $contractListChild = ContractHistory::getInActiveChildData();

        self::processContracts($contractList, false);
        self::processContracts($contractListChild, true);
    }

    private static function processContracts($contractList, $fromChild)
    {
        if(!empty($contractList))
        {
            foreach ($contractList as $value)
            {
                $alreadyActiveContract = false;
                if($fromChild)
                {
                    $startDate = $value->contractMaster->startDate;
                    $endDate = Carbon::parse($value->contractMaster->endDate)
                        ->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                } else
                {
                    $startDate = $value->startDate;
                    $endDate = Carbon::parse($value->endDate)
                        ->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                }
                $status = ContractHistoryService::checkContractDateBetween(
                    $startDate,
                    $endDate
                );
                if($status == -1)
                {
                    $alreadyActiveContract = ContractHistoryService::checkAlreadyActiveContract($value->contract_id);
                }
                ContractHistoryService::updateOrInsertStatus
                (
                    $value->contract_id, $status, $value->company_id, null,true
                );
                if(!$alreadyActiveContract)
                {
                    self::updateContractMaster($status, $value->company_id, $value->contract_id);
                }
            }
        }
    }

    public function updateContractMaster($status, $companyId, $contractId)
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
                $body = "<p>Please be reminded that Contract Code " . $contractCode . ", titled " . $title .",
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
