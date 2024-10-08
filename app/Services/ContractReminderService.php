<?php

namespace App\Services;

use App\Helpers\Email;
use App\Models\CMContractScenarioAssign;
use App\Models\ContractMilestone;
use App\Models\ContractUserAssign;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;

class ContractReminderService
{
    public static function reminderBefore()
    {
        $results = CMContractScenarioAssign::getReminderMilestoneDueDate(1);

        $contractIds = $results->pluck('contract_id')->toArray();
        $userDetails = ContractUserAssign::getReminderContractExpiryUsers($contractIds);
        $existingUserIds = $userDetails->pluck('userId')->toArray();

        foreach ($results as $result)
        {
            $contractId = $result->contract_id;
            $settingValue = $result->contractScenarioSettings[0]->value;
            $milestones = ContractMilestone::getReminderMilestoneDueDate(1,$contractId,$settingValue);

            $contractOwnerId = $result->contractMaster->contractOwner;
            $counterPartyNameId = $result->contractMaster->counterPartyName;
            self::addUserIfNotExists($userDetails, $contractOwnerId, $existingUserIds, $result);
            self::addUserIfNotExists($userDetails, $counterPartyNameId, $existingUserIds, $result);
            $milestoneDetails = self::getMilestoneDetails($milestones);
        }

        self::getSupplierData(1, $milestoneDetails, $userDetails);
    }

    public static function reminderAfter()
    {
        $results = CMContractScenarioAssign::getReminderMilestoneDueDate(2);

        $contractIds = $results->pluck('contract_id')->toArray();
        $userDetails = ContractUserAssign::getReminderContractExpiryUsers($contractIds);
        $existingUserIds = $userDetails->pluck('userId')->toArray();

        foreach ($results as $result)
        {
            $contractId = $result->contract_id;
            $settingValue = $result->contractScenarioSettings[0]->value;

            switch ($settingValue)
            {
                case 1:
                    $interval = 1;
                    break;
                case 2:
                    $interval = 3;
                    break;
                case 3:
                    $interval = 7;
                    break;
                case 4:
                    $interval = 14;
                    break;
                case 5:
                    $interval = 30;
                    break;
                default:
                    $interval = 0;
            }

            $milestones = ContractMilestone::getReminderMilestoneDueDate(2,$contractId,$interval);

            $contractOwnerId = $result->contractMaster->contractOwner;
            $counterPartyNameId = $result->contractMaster->counterPartyName;
            self::addUserIfNotExists($userDetails, $contractOwnerId, $existingUserIds, $result);
            self::addUserIfNotExists($userDetails, $counterPartyNameId, $existingUserIds, $result);
            $milestoneDetails = self::getMilestoneDetails($milestones);

        }

        self::getSupplierData( 2, $milestoneDetails, $userDetails);
    }

    private static function addUserIfNotExists($userDetails, $userId, $existingUserIds, $result)
    {
        if ($userId && !in_array($userId, $existingUserIds))
        {
            $userDetails->push([
                'userId' => $userId,
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

    private static function getMilestoneDetails($milestones)
    {
        return $milestones->map(function ($milestone)
        {
            return [
                'milestoneTitle' => $milestone->title,
                'milestoneDescription' => $milestone->description,
                'milestoneDueDate' => $milestone->due_date,
            ];
        })->toArray();
    }

    public static function getSupplierData($type, $milestoneDetails, $userDetails)
    {
        $milestoneDetails = is_array($milestoneDetails) ? $milestoneDetails : $milestoneDetails->toArray();
        $userDetails = is_array($userDetails) ? $userDetails : $userDetails->toArray();

        $emailData = array_map(function ($user) use ($milestoneDetails) {
            return array_map(fn($milestone) => array_merge($user, $milestone), $milestoneDetails);
        }, $userDetails);

        $emailData = array_merge(...$emailData);

        self::sendEmail($emailData, $type);
    }

    public static function sendEmail($userDetails, $type)
    {
        $emails = [];

        $subject = ($type == 1) ? 'Milestone Due Date Expiry Notice' : 'Milestone Due Date Expired';
        $text = ($type == 1) ? 'is approaching' : 'has expired';


        foreach ($userDetails as $dt)
        {
            $contractCode = $dt['contractCode'];
            $title = $dt['title'] ?? 'N/A';
            $milestoneTitle = $dt['milestoneTitle'] ?? 'N/A';
            $milestoneDescription = $dt['milestoneDescription'] ?? 'N/A';
            $endDate = $dt['milestoneDueDate'] ?? 'N/A';
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

            $body = "<p>
            This is a reminder that the project milestone " . $text . " its due date. Please review the deliverables
            and tasks and ensure timely completion.</p>
            <p>Contract Title  : " . $title . "</p>
            <p>Contract ID : " . $contractCode . "</p>
            <p>Milestone Title : " . $milestoneTitle . "</p>
            <p>Milestone Description : " . $milestoneDescription . "</p>
            <p>Milestone Due Date : " . $endDateFormatted . "</p>
            <p>If you need any assistance, feel free to contact us.</p>
            <p>Best regards,</p>
        <p>Thank you</p>";

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
