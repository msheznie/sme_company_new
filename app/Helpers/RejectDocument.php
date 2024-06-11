<?php

namespace App\Helpers;

use App\Exceptions\CommonException;
use App\Models\CompanyDocumentAttachment;
use App\Models\ErpApprovalLevel;
use App\Models\ErpDocumentApproved;
use App\Models\ErpDocumentMaster;
use App\Models\ErpEmployeesDepartments;

class RejectDocument
{

    public static function rejectDocument($formData, $masterRecord): bool
    {
        return self::validationAndRejection($formData, $masterRecord);
    }
    private static function validationAndRejection($formData, $masterRecord): bool
    {
        $checkDocumentExist = ErpDocumentMaster::documentMasterData($formData['documentSystemID']);
        if(empty($checkDocumentExist))
        {
            throw new CommonException(trans('common.document_id_not_found'));
        }
        $documentApproved = ErpDocumentApproved::checkApproveDocumentExists($formData['documentApprovedID']);
        if(empty($documentApproved))
        {
            throw new CommonException(trans('common.no_approval_record_found'));
        }
        if($documentApproved->rejectedYN == -1)
        {
            throw new CommonException(trans('common.document_already_rejected'));
        }

        $approvalLevel = ErpApprovalLevel::getApprovalLevel($formData["approvalLevelID"]);
        if (empty($approvalLevel))
        {
            throw new CommonException(trans('common.approval_level_not_found'));
        }

        $companyDocument = CompanyDocumentAttachment::companyDocumentAttachment(
            $formData["selectedCompanyID"],
            $formData["documentSystemID"]
        );
        if (empty($companyDocument))
        {
            throw new CommonException(trans('common.policy_not_found'));
        }

        $employeeSystemID = General::currentEmployeeId();
        $checkEmployeeAccess = ErpEmployeesDepartments::checkUserHasApprovalAccess(
            $documentApproved['approvalGroupID'],
            $documentApproved['companySystemID'],
            $formData["documentSystemID"],
            $employeeSystemID
        );
        if(!$checkEmployeeAccess)
        {
            throw new CommonException(trans('common.you_do_not_have_access_to_approve_this_document'));
        }

        self::updateDocumentRejected($formData, $masterRecord, $employeeSystemID, $documentApproved);
        return true;
    }
    public static function updateDocumentRejected($formData, $masterRecord, $employeeSystemID, $documentApproved): bool
    {
        $approveDocUpdate = $documentApproved->update([
            'rejectedYN' => -1,
            'rejectedDate' => now(),
            'rejectedComments' => $formData["rejectedComments"],
            'employeeID' => General::getEmployeeCode($employeeSystemID),
            'employeeSystemID' => $employeeSystemID
        ]);
        if(!$approveDocUpdate)
        {
            throw new CommonException(trans('common.failed_to_reject_document'));
        }
        $masterRecord->increment('timesReferred');
        $masterRecord->refferedBackYN = 1;
        $masterUpdate = $masterRecord->save();
        if(!$masterUpdate)
        {
            throw new CommonException(trans('common.failed_to_reject_document'));
        }
        return true;
    }
}
