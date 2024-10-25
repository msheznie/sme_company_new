<?php

namespace App\Services;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\CompanyDocumentAttachment;
use App\Models\DocumentApproved;
use App\Models\DocumentMaster;
use App\Models\DocumentReferedHistory;
use App\Models\EmployeesDepartment;
use App\Models\ErpDocumentApproved;
use App\Models\ErpDocumentReferedHistory;
use App\Traits\AuditTrial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReferbackContractService
{
    public static function referbackContract($formData, $masterRecord): bool
    {
        return self::validateReferback($formData, $masterRecord);
    }

    public static function validateReferback($formData, $masterRecord)
    {
        if ($masterRecord->approved_yn == 1)
        {
            GeneralService::sendException('You cannot reopen this contract it is already fully approved');
        }

        if ($masterRecord->confirmed_yn == 0)
        {
            GeneralService::sendException('You cannot reopen this contract, it is not confirmed');
        }

        if(isset($formData["categoryId"]) && in_array($formData["categoryId"], [4, 6]))
        {
            self::updateReferedHistory($formData, $masterRecord);
        }
        else
        {
            self::updateMasterRecord($formData, $masterRecord);
        }
        return true;
    }

    public static function updateMasterRecord($formData, $masterRecord)
    {
        $masterRecord->increment('timesReferred');
        $masterRecord->confirmed_yn = 0;
        $masterRecord->confirm_by = null;
        $masterRecord->confirmed_date = null;
        $masterRecord->rollLevelOrder = 1;
        $masterUpdate = $masterRecord->save();

        if(!$masterUpdate)
        {
            GeneralService::sendException('Unable to referback contract');
        }

        self::updateReferedHistory($formData, $masterRecord);
        return true;
    }

    public static function updateReferedHistory($formData, $masterRecord)
    {
        $fetchDocumentApproved = ErpDocumentApproved::getDocumentApprovedData(
            $formData["documentSystemID"], $masterRecord['id'], $formData["selectedCompanyID"]
        );

        if (!empty($fetchDocumentApproved))
        {
            foreach ($fetchDocumentApproved as $documentApproved)
            {
                $documentApproved['refTimes'] = $masterRecord->timesReferred;
            }
        }

        $documentApprovedArray = $fetchDocumentApproved->toArray();
        $referedHistory = ErpDocumentReferedHistory::saveReferedHistory($documentApprovedArray);

        if(!$referedHistory)
        {
            GeneralService::sendException('Unable to create referback history');
        }

        $employeeSystemID = General::currentEmployeeId();
        $referedHistoryArray = [
            'rejectedYN' => -1,
            'rejectedDate' => now(),
            'rejectedComments' => $formData["referedbackComments"],
            'employeeSystemID' => $employeeSystemID,
            'employeeID' => General::getEmployeeCode($employeeSystemID)
        ];

        $updateHistory = ErpDocumentReferedHistory::updateReferedHistory(
            $masterRecord['id'], $formData["selectedCompanyID"], $formData["documentSystemID"],
            $formData["documentApprovedID"], $formData["rollLevelOrder"], $referedHistoryArray
        );

        if(!$updateHistory)
        {
            GeneralService::sendException('Unable to update referback history');
        }

        $deleteApproved =  ErpDocumentApproved::deleteDocumentApprovedData(
            $formData["documentSystemID"], $masterRecord['id'], $formData["selectedCompanyID"]
        );
        if(!$deleteApproved)
        {
            GeneralService::sendException('Unable to delete approval document');
        }

        if(isset($formData["categoryId"]) && in_array($formData["categoryId"], [4, 6]))
        {
            ContractHistoryService::contractHistoryDelete($formData);
        }

        return true;
    }



}
