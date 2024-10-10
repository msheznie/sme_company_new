<?php

namespace App\Services;

use App\Helpers\Email;
use App\Helpers\General;
use App\Models\CMContractScenarioAssign;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractDocument;
use App\Models\ContractMilestone;
use App\Models\ContractUserAssign;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;

class ContractReminderService
{
    public static function reminderBefore($scenario)
    {
        $results = CMContractScenarioAssign::getReminderData(1, $scenario);
        $contractIds = $results->pluck('contract_id')->toArray();
        $userDetails = ContractUserAssign::getReminderContractExpiryUsers($contractIds);
        $existingUserIds = $userDetails->pluck('userId')->toArray();

        $details = [];
        $allDetails = [];

        foreach ($results as $result)
        {
            $contractId = $result->contract_id;
            $settingValue = $result->contractScenarioSettings[0]->value;

            if($scenario == 2)
            {
                $milestones = ContractMilestone::getReminderMilestoneDueDate(1,$contractId,$settingValue);
                $details = self::getMilestoneDetails($milestones);
            }
            if($scenario == 4)
            {
                $documents = ContractDocument::getReminderDocumentExpiryData(1,$contractId,$settingValue);
                $additionalDocuments = ContractAdditionalDocuments::getReminderDocumentExpiryData(
                    1,$contractId,$settingValue);

                $details = array_merge(
                    self::getContractDocumentDetails($documents),
                    self::getContractAdditionalDocumentDetails($additionalDocuments)
                );
            }

            $allDetails = array_merge($allDetails, $details);
            $contractOwnerId = $result->contractMaster->contractOwner;
            $counterPartyNameId = $result->contractMaster->counterPartyName;
            self::addUserIfNotExists($userDetails, $contractOwnerId, $existingUserIds, $result);
            self::addUserIfNotExists($userDetails, $counterPartyNameId, $existingUserIds, $result);
        }

        self::getSupplierData(1, $allDetails, $userDetails, $scenario);
    }

    public static function reminderAfter($scenario)
    {
        $results = CMContractScenarioAssign::getReminderData(2, $scenario);
        $contractIds = $results->pluck('contract_id')->toArray();
        $userDetails = ContractUserAssign::getReminderContractExpiryUsers($contractIds);
        $existingUserIds = $userDetails->pluck('userId')->toArray();

        $details = [];
        $allDetails = [];

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

            if($scenario == 2)
            {
                $milestones = ContractMilestone::getReminderMilestoneDueDate(2,$contractId,$interval);
                $details = self::getMilestoneDetails($milestones);
            }
            if($scenario == 4)
            {
                $documents = ContractDocument::getReminderDocumentExpiryData(2,$contractId,$interval);
                $additionalDocuments = ContractAdditionalDocuments::getReminderDocumentExpiryData(
                    2,$contractId,$interval);

                $details = array_merge(
                    self::getContractDocumentDetails($documents),
                    self::getContractAdditionalDocumentDetails($additionalDocuments)
                );
            }

            $allDetails = array_merge($allDetails, $details);
            $contractOwnerId = $result->contractMaster->contractOwner;
            $counterPartyNameId = $result->contractMaster->counterPartyName;
            self::addUserIfNotExists($userDetails, $contractOwnerId, $existingUserIds, $result);
            self::addUserIfNotExists($userDetails, $counterPartyNameId, $existingUserIds, $result);
        }

        self::getSupplierData( 2, $allDetails, $userDetails, $scenario);
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
                'contractID' => $milestone->contractID,
            ];
        })->toArray();
    }

    private static function getContractDocumentDetails($documents)
    {
        return $documents->map(function ($document)
        {
            return [
                'documentType' => $document->documentMaster->documentType,
                'documentName' => $document->documentName,
                'expiryDate' => $document->documentExpiryDate,
                'contractID' => $document->contractID,
            ];
        })->toArray();
    }

    private static function getContractAdditionalDocumentDetails($additionalDocuments)
    {
        return $additionalDocuments->map(function ($document)
        {
            return [
                'documentType' => $document->documentMaster->documentType,
                'documentName' => $document->documentName,
                'expiryDate' => $document->expiryDate,
                'contractID' => $document->contractID,
            ];
        })->toArray();
    }

    public static function getSupplierData($type, $details, $userDetails, $scenario)
    {
        $details = is_array($details) ? $details : $details->toArray();
        $userDetails = is_array($userDetails) ? $userDetails : $userDetails->toArray();

        $emailData = array_reduce($userDetails, function ($carry, $user) use ($details)
        {
            $userContractId = $user['contractId'];

            $matchedDetails = array_filter($details, function ($detail) use ($userContractId)
            {
                return $detail['contractID'] == $userContractId;
            });

            foreach ($matchedDetails as $detail)
            {
                $carry[] = array_merge($user, $detail);
            }

            return $carry;
        }, []);

        self::sendEmail($emailData, $type, $scenario);
    }

    public static function sendEmail($userDetails, $type, $scenario)
    {
        $emails = [];

        $subject = self::getEmailSubject($scenario, $type);

        foreach ($userDetails as $details)
        {
            $userId = $details['userId'] ?? 'N/A';
            $companySystemID = $details['companySystemID'];

            $body = self::getEmailBody($details, $type, $scenario);

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

    public static function getEmailSubject($scenario, $type)
    {
        $emailSubject = '';
        switch ($scenario)
        {
            case 2:
                $emailSubject = ($type == 1) ? 'Milestone Due Date Expiry Notice' : 'Milestone Due Date Expired';
                break;
            case 4:
                $emailSubject = ($type == 1) ? 'Document Expiry Notice' : 'Document Expired';
                break;
            default:
                break;
        }
        return $emailSubject;
    }

    public static function getEmailBody($details, $type, $scenario)
    {
        $emailBody = '';

        $contractCode = $details['contractCode'];
        $title = $details['title'] ?? "-";

        if($scenario === 2)
        {
            $text = ($type == 1) ? 'is approaching' : 'has expired';
            $milestoneTitle = $details['milestoneTitle'] ?? "-";
            $milestoneDescription = $details['milestoneDescription'] ?? "-";
            $endDate = $details['milestoneDueDate'] ?
                (new DateTime($details['milestoneDueDate']))->format('Y-m-d') : "-";

            $emailBody = "<p>
            This is a reminder that the project milestone " . $text . " its due date. Please review the deliverables
            and tasks and ensure timely completion.</p>
            <p>Contract Title  : " . $title . "</p>
            <p>Contract ID : " . $contractCode . "</p>
            <p>Milestone Title : " . $milestoneTitle . "</p>
            <p>Milestone Description : " . $milestoneDescription . "</p>
            <p>Milestone Due Date : " . $endDate . "</p>
            <p>If you need any assistance, feel free to contact us.</p>
            <p>Best regards,</p>
        <p>Thank you</p>";
        }

        if($scenario === 4)
        {
            $text = ($type == 1) ? 'have reached their expiration date' : 'have expired';
            $documentType = $details['documentType'] ?? "-";
            $documentName = $details['documentName'] ?? "-";
            $endDate = $details['expiryDate'] ?
                (new DateTime($details['expiryDate']))->format('Y-m-d') : "-";

            $emailBody = "<p>
            This is to inform you that below attached documents in the system " . $text . " . Please upload the updated
             versions at your earliest convenience to maintain the compliance.</p>
            <p>Contract Title  : " . $title . "</p>
            <p>Contract ID : " . $contractCode . "</p>
            <p>Document Type : " . $documentType . "</p>
            <p>Document Name : " . $documentName . "</p>
            <p>Expiry Date : " . $endDate . "</p>
            <p>If you need any assistance, feel free to contact us.</p>
            <p>Best regards,</p>
        <p>Thank you</p>";
        }

        return $emailBody;
    }
}
