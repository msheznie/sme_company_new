<?php

namespace App\Helpers;

use App\Helpers\General;
use App\Models\ErpDocumentApproved;
use App\Models\ErpApprovalLevel;
use App\Models\ErpDocumentMaster;
use Illuminate\Support\Facades\DB;

class ConfirmDocument
{
    public static function confirmDocument($params): array {
        $validation = self::checkValidation($params);
        if(!$validation['success']){
            return $validation;
        }
        $docInfoArr = self::setDocumentInfoArray($params);
        if (empty($docInfoArr)) {
            return [
                'success' => false,
                'message' => trans('common.document_id_not_found')
            ];
        }
        $namespacedModel = 'App\Models\\' . $docInfoArr["modelName"];
        $masterRec = $namespacedModel::find($params["autoID"]);

        if (!$masterRec) {
            return ['success' => false, 'message' => 'No records found'];
        }
        $otherValidation = self::checkOtherValidation($params, $namespacedModel, $docInfoArr);
        if(!$otherValidation['success']) {
            return $otherValidation;
        }

        $employeeSystemID = General::currentEmployeeId();
        $document = ErpDocumentMaster::select('documentSystemID', 'documentID', 'departmentSystemID')
            ->where('documentSystemID', $params["document"])->first();

        DB::beginTransaction();
        try {
            $masterRec->update([
                $docInfoArr['confirmColumnName'] => 1,
                $docInfoArr['confirmedBySystemID'] => $employeeSystemID,
                $docInfoArr['confirmedDate'] => now(),
                $docInfoArr['RollLevForApp_curr'] => 1
            ]);

            $approvalLevel = ErpApprovalLevel::select('approvalLevelID')
                ->with([
                    'approvalRole' => function ($q) {
                        $q->select('rollDescription', 'documentSystemID', 'documentID', 'companySystemID', 'companyID',
                            'departmentSystemID', 'departmentID', 'serviceLineSystemID', 'serviceLineID', 'rollLevel',
                            'approvalLevelID', 'approvalGroupID');
                    }
                ])
                ->where('companySystemID', $params['company'])
                ->where('documentSystemID', $params['document'])
                ->where('departmentSystemID', $document['departmentSystemID'])
                ->where('isActive', -1)
                ->first();

            if (empty($approvalLevel)) {
                DB::rollback();
                return [
                    'success' => false,
                    'message' => trans('common.no_approval_level_for_this_document')
                ];
            }

            $sourceDocument = $namespacedModel::find($params['autoID']);
            $documentApproved = self::prepareDocumentApprovalData(
                $approvalLevel,
                $sourceDocument,
                $docInfoArr,
                $params,
                $employeeSystemID
            );

            if (!$documentApproved) {
                return [
                    'success' => false,
                    'message' => trans('common.please_set_the_approval_group')
                ];
            }

            $docApproved = ErpDocumentApproved::saveDocumentApproved($documentApproved);
            if (!$docApproved) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => trans('common.failed_to_confirm_document')
                ];
            }

            DB::commit();
            return [
                'success' => true,
                'message' => trans('common.document_confirmed_successfully')
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => $e->getMessage() . trans('common.error_occurred')
            ];
        }
    }

    private static function prepareDocumentApprovalData(
        $approvalLevel,
        $sourceDocument,
        $docInfoArr,
        $params,
        $employeeSystemID
    ): array{
        $documentApproved = [];
        foreach ($approvalLevel->approvalRole as $val) {
            if (!$val->approvalGroupID) {
                return [];
            }

            $documentCode = $sourceDocument[$docInfoArr['documentCodeColumnName']] ?? $params['documentCode'];

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
    public static function setDocumentInfoArray($params): array {
        $docInfoArray = array(
            'documentCodeColumnName' => '',
            'confirmColumnName' => '',
            'confirmedBy' => '',
            'confirmedBySystemID' => '',
            'confirmedDate' => '',
            'tableName' => '',
            'modelName' => '',
            'primarykey' => ''
        );
        switch ($params["document"]) {
            case 123:
                $docInfoArray["documentCodeColumnName"] = 'title';
                $docInfoArray["confirmColumnName"] = 'confirmed_yn';
                $docInfoArray["confirmedBySystemID"] = 'confirm_by';
                $docInfoArray["confirmedDate"] = 'confirmed_date';
                $docInfoArray["tableName"] = 'cm_contract_master';
                $docInfoArray["modelName"] = 'ContractMaster';
                $docInfoArray["primarykey"] = 'id';
                $docInfoArray["RollLevForApp_curr"] = 'rollLevelOrder';
                break;
            default:
                return [];
        }
        return $docInfoArray;
    }
    private function checkValidation($params): array {
        if (!array_key_exists('autoID', $params)) {
            return [
                'success' => false,
                'message' => trans('common.document_system_id_not_found')
            ];
        }

        if (!array_key_exists('company', $params)) {
            return [
                'success' => false,
                'message' => trans('common.company_not_found')
            ];
        }

        if (!array_key_exists('document', $params)) {
            return [
                'success' => false,
                'message' => trans('common.document_not_found')
            ];
        }
        return [
            'success' => true,
            'message' => trans('common.validation_checked_successfully')
        ];
    }
    private function checkOtherValidation($params, $namespacedModel, $docInfoArr): array {
        $docExist = ErpDocumentApproved::select('documentApprovedID')
            ->where('documentSystemID', $params["document"])
            ->where('documentSystemCode', $params["autoID"])
            ->first();
        if ($docExist) {
            return [
                'success' => false,
                'message' => trans('common.document_approval_data_already_generated')
            ];
        }

        $document = ErpDocumentMaster::select('documentSystemID')
            ->where('documentSystemID', $params["document"])
            ->first();
        if (!$document) {
            return [
                'success' => false,
                'message' => trans('common.document_not_found')
            ];
        }

        $isConfirm = $namespacedModel::where($docInfoArr["primarykey"], $params["autoID"])
            ->where($docInfoArr["confirmColumnName"], 1)
            ->first();
        if ($isConfirm) {
            return [
                'success' => false,
                'message' => trans('common.document_is_already_confirmed')
            ];
        }
        return [
            'success' => true,
            'message' => trans('common.validation_checked_successfully')
        ];
    }
}
