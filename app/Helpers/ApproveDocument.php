<?php

namespace App\Helpers;

use App\Helpers\General;
use App\Models\CompanyDocumentAttachment;
use App\Models\ContractMaster;
use App\Models\ErpApprovalLevel;
use App\Models\ErpDocumentApproved;
use App\Models\ErpEmployeesDepartments;
use App\Services\ContractHistoryService;
use App\Utilities\ContractManagementUtils;
use App\Utilities\EmailUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CommonException;
use Illuminate\Support\Facades\Log;

class ApproveDocument
{
    public static function approveDocument($formData, $masterRecord)
    {
        return self::approvalValidationUpdation(
            $formData,
            $masterRecord
        );
    }
    private static function approvalValidationUpdation($formData, $masterRecord)
    {
        $employeeSystemID = General::currentEmployeeId();

        $docApproved = ErpDocumentApproved::checkApproveDocumentExists($formData["documentApprovedID"]);
        if (empty($docApproved))
        {
            throw new CommonException(trans('common.no_approval_record_found'));
        }

        if (!$masterRecord->confirmed_yn)
        {
            throw new CommonException(trans('common.document_is_not_confirmed'));
        }

        $companyDocument = CompanyDocumentAttachment::companyDocumentAttachment(
            $docApproved->companySystemID,
            $formData["documentSystemID"]
        );
        if (empty($companyDocument))
        {
            throw new CommonException(trans('common.policy_not_found'));
        }

        $checkEmployeeAccess = ErpEmployeesDepartments::checkUserHasApprovalAccess(
            $docApproved['approvalGroupID'],
            $docApproved['companySystemID'],
            $formData["documentSystemID"],
            $employeeSystemID,
            'get'
        );
        if(!$checkEmployeeAccess)
        {
            throw new CommonException(trans('common.you_do_not_have_access_to_approve_this_document'));
        }

        $isApproved = ErpDocumentApproved::checkApprovalEligible($formData["documentApprovedID"]);
        if ($isApproved)
        {
            throw new CommonException(trans('common.approval_level_not_found'));
        }

        $approvalLevel = ErpApprovalLevel::getApprovalLevel($formData["approvalLevelID"]);
        if (empty($approvalLevel))
        {
            throw new CommonException(trans('common.approval_level_not_found'));
        }
        Log::info($checkEmployeeAccess);

        self::updateApproveDocument(
            $formData,
            $approvalLevel,
            $masterRecord,
            $employeeSystemID
        );

        return true;
    }
    private static function updateApproveDocument(
        $formData,
        $approvalLevel,
        $masterRecord,
        $employeeSystemID
    )
    {
        if ($approvalLevel->noOfLevels == $formData["rollLevelOrder"])
        {
            $masterRecord->approved_yn = 1;
            $masterRecord->approved_by = $employeeSystemID;
            $masterRecord->approved_date = now();

            if($formData['documentSystemID'] == 123)
            {
                if($masterRecord['parent_id'] == 0)
                {
                    self::updateContractStatus($masterRecord);

                } else
                {
                    $result = ContractHistoryService::getContractStatusData($masterRecord);

                    $input = [
                        'contractId' => $result->parent->uuid ?? null,
                        'cloneContractId' => $masterRecord['uuid'],
                        'category' => $result->history->category ?? null,
                        'contractHistoryId' => $result->history->uuid ?? null,
                        'selectedCompanyID' => $masterRecord['companySystemID'],
                    ];

                    ContractHistoryService::updateContractStatus($input);
                }
            }
            $update = $masterRecord->save();

            if(!$update)
            {
                throw new CommonException(trans('common.failed_to_approve_document'));
            }

        }
        else
        {
            $masterRecord->rollLevelOrder = $formData['rollLevelOrder'] + 1;
            $update = $masterRecord->save();
            if(!$update)
            {
                throw new CommonException(trans('common.failed_to_approve_document'));
            }
        }
        $employeeCode = General::getEmployeeCode($employeeSystemID) ?? null;
        $approveDoc = ErpDocumentApproved::updateDocumentApproved(
            $formData["documentApprovedID"],
            $formData["approvalComment"],
            $employeeSystemID,
            $employeeCode
        );
        if(!$approveDoc)
        {
            throw new CommonException(trans('common.failed_to_approve_document'));
        }
        if ($formData["documentSystemID"] === 123)
        {
            self::sendEmail($formData, $masterRecord);
        }

        return true;
    }
    public static function sendEmail($params, $masterRecords)
    {
        $documentApproved = ErpDocumentApproved::levelWiseDocumentApproveUsers(
            $params["documentSystemID"],
            $masterRecords->id,
            $masterRecords->rollLevelOrder
        );
        $emails = [];
        if($documentApproved && $documentApproved['approvedYN'] == 0)
        {
            $approvalList = ErpEmployeesDepartments::getApprovalListToEmail(
                $documentApproved['approvalGroupID'],
                $documentApproved['companySystemID'],
                $documentApproved['documentSystemID'],
            );

            $subject = EmailUtils::getEmailSubject($documentApproved->documentSystemID);
            $body = EmailUtils::getEmailBody($documentApproved->documentSystemID, $masterRecords);
            foreach($approvalList as $dt)
            {
                if ($dt['employee'])
                {
                    $emails[] = [
                        'empSystemID' => $dt['employee']['employeeSystemID'],
                        'companySystemID' => $documentApproved['companySystemID'],
                        'docSystemID' => $documentApproved['documentSystemID'],
                        'alertMessage' => $subject,
                        'emailAlertMessage' => $body,
                        'docSystemCode' => $documentApproved['documentSystemCode'],
                        'error_tag' => $subject,
                        'error_msg' => '<b>The Employee:'. $dt['employee']['empName'] .
                            '</b>- Mail ID is invalid!',
                        'db' => $params['db'] ?? ""
                    ];
                }
            }
        }
        Email::sendBulkEmail($emails);
        return true;
    }

    public static function updateContractStatus($masterRecord)
    {
        $startDate = Carbon::parse($masterRecord['startDate'])->format('Y-m-d');
        $endDate = Carbon::parse($masterRecord['endDate'])
            ->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        $status = ContractHistoryService::checkContractDateBetween(
            $startDate,
            $endDate
        );

        $masterRecord->status = $status;

        return ContractHistoryService::updateOrInsertStatus
        (
            $masterRecord->id, $status, $masterRecord->companySystemID
        );
    }
}
