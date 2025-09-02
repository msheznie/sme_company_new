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
            GeneralService::sendException(trans('common.you_cannot_reopen_this_contract_it_is_already_fully_approved'));
        }

        if ($masterRecord->confirmed_yn == 0)
        {
            GeneralService::sendException(trans('common.you_cannot_reopen_this_contract_it_is_not_confirmed'));
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
            GeneralService::sendException(trans('common.unable_to_referback_contract'));
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
            GeneralService::sendException(trans('common.unable_to_create_referback_history'));
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
            GeneralService::sendException(trans('common.unable_to_update_referback_history'));
        }

        $deleteApproved =  ErpDocumentApproved::deleteDocumentApprovedData(
            $formData["documentSystemID"], $masterRecord['id'], $formData["selectedCompanyID"]
        );
        if(!$deleteApproved)
        {
            GeneralService::sendException(trans('common.unable_to_delete_approval_document'));
        }

        if(isset($formData["categoryId"]) && in_array($formData["categoryId"], [4, 6]))
        {
            ContractHistoryService::contractHistoryDelete($formData);
        }

        return true;
    }



}
