<?php

namespace App\Helpers;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Helpers\Email;
use App\Models\CompanyDocumentAttachment;
use App\Models\ErpDocumentApproved;
use App\Models\ErpApprovalLevel;
use App\Models\ErpDocumentMaster;
use App\Models\ErpEmployeesDepartments;
use App\Utilities\EmailUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfirmDocument
{
    public static function confirmDocument($params, $masterRecord): bool
    {
        self::checkValidation($params);
        self::checkOtherValidation($params, $masterRecord);

        $employeeSystemID = General::currentEmployeeId();
        $document = ErpDocumentMaster::documentMasterData($params["document"]);

        $policy = CompanyDocumentAttachment::companyDocumentAttachment($params['company'], $params['document']);
        if(empty($policy))
        {
            throw new CommonException(trans('common.policy_not_available_for_this_document'));
        }

        $masterRecord->confirmed_yn = 1;
        $masterRecord->confirm_by = $employeeSystemID;
        $masterRecord->confirmed_date = now();
        $masterRecord->rollLevelOrder = 1;
        $saveMasterRecord = $masterRecord->save();
        if(!$saveMasterRecord)
        {
            throw new CommonException(trans('common.failed_to_confirm_document'));
        }
        $output = ErpApprovalLevel::approvalLevelValidation($params, $document, $policy);

        if (empty($output))
        {
            if($policy->isAmountApproval)
            {
                throw new CommonException('Contract amount is not within the specified approval setup value
                 range');
            } else
            {
                throw new CommonException(trans('common.no_approval_level_for_this_document'));
            }
        }

        $documentApproved = self::prepareDocumentApprovalData(
            $output,
            $params,
            $employeeSystemID
        );

        if (!$documentApproved)
        {
            throw new CommonException(trans('common.please_set_the_approval_group'));
        }

        $docApproved = ErpDocumentApproved::saveDocumentApproved($documentApproved);
        if (!$docApproved)
        {
            throw new CommonException(trans('common.failed_to_confirm_document'));
        }
        if ($params["document"] === 123)
        {
            self::sendEmail($params, $masterRecord);
        }
        return true;
    }

    private static function prepareDocumentApprovalData(
        $approvalLevel,
        $params,
        $employeeSystemID
    ): array
    {
        $documentApproved = [];
        foreach ($approvalLevel->approvalRole as $val)
        {
            if (!$val->approvalGroupID)
            {
                return [];
            }

            $documentCode = $params['documentCode'] ?? null;
            $documentApproved[] = [
                'companySystemID' => $val->companySystemID,
                'companyID' => $val->companyID,
                'departmentSystemID' => $val->departmentSystemID,
                'departmentID' => $val->departmentID,
                'serviceLineSystemID' => $val->serviceLineSystemID,
                'serviceLineCode' => $val->serviceLineCode,
                'documentSystemID' => $val->documentSystemID,
                'documentID' => $val->documentID,
                'documentSystemCode' => $params['autoID'],
                'documentCode' => $documentCode,
                'approvalLevelID' => $val->approvalLevelID,
                'rollID' => $val->rollMasterID,
                'approvalGroupID' => $val->approvalGroupID,
                'rollLevelOrder' => $val->rollLevel,
                'docConfirmedDate' => now(),
                'docConfirmedByEmpSystemID' => $employeeSystemID,
                'docConfirmedByEmpID' => General::getEmployeeCode($employeeSystemID),
                'timeStamp' => now()
            ];
        }
        return $documentApproved;
    }
    private function checkValidation($params): bool
    {
        if (!array_key_exists('autoID', $params))
        {
            throw new CommonException(trans('common.document_is_already_confirmed'));
        }

        if (!array_key_exists('company', $params))
        {
            throw new CommonException(trans('common.company_not_found'));
        }

        if (!array_key_exists('document', $params))
        {
            throw new CommonException(trans('common.document_not_found'));
        }
        return true;
    }
    private function checkOtherValidation($params, $masterRecord): bool
    {
        $docExist = ErpDocumentApproved::checkApproveDocumentExists(0, 1,
            $params["document"], $params["autoID"]
        );
        if ($docExist)
        {
            throw new CommonException(trans('common.document_approval_data_already_generated'));
        }

        $document = ErpDocumentMaster::documentMasterData($params["document"]);
        if (!$document)
        {
            throw new CommonException(trans('common.document_not_found'));
        }

        if ($masterRecord['confirmed_yn'] == 1)
        {
            throw new CommonException(trans('common.document_is_already_confirmed'));
        }
        return true;

    }
    public static function sendEmail($params, $masterRecords)
    {
        $documentApproved = ErpDocumentApproved::levelWiseDocumentApproveUsers(
            $params["document"],
            $masterRecords->id,
            1
        );
        $emails = [];
        if($documentApproved && $documentApproved['approvedYN'] == 0)
        {
            $companyDocument = CompanyDocumentAttachment::companyDocumentAttachment(
                $documentApproved->companySystemID,
                $documentApproved->documentSystemID
            );
            if (empty($companyDocument))
            {
                throw new CommonException('Policy not found for this document');
            }
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
}
