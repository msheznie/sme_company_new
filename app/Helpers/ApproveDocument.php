<?php

namespace App\Helpers;

use App\Helpers\General;
use App\Models\CompanyDocumentAttachment;
use App\Models\ErpApprovalLevel;
use App\Models\ErpDocumentApproved;
use App\Models\ErpEmployeesDepartments;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CommonException;

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
            $employeeSystemID
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
            $update = $masterRecord->save();
            if(!$update)
            {
                throw new CommonException(trans('common.failed_to_approve_document'));
            }
        } else
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
        return true;
    }
}
