<?php

namespace App\Services;

use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Utilities\ContractManagementUtils;

class AttachmentService
{
    public function getDocumentSystemID($uuid, $documentMasterID, $companySystemID)
    {
        $ids = [];
        switch($documentMasterID)
        {
            case 121:
                $ids = self::getContractDocumentID($uuid, $companySystemID);
                break;
            case 123:
                $contract = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
                $ids[] = $contract['id'] ?? [];
                break;
            case 124:
            case 125:
                $ids = ContractHistory::getContractHistory($uuid, $companySystemID);
                break;
            default:
                break;
        }
        return $ids;
    }

    private function getContractDocumentID($uuid, $companySystemID)
    {
        $contractMaster = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
        $contractID = $contractMaster['id'] ?? 0;
        return  ContractDocument::pluckContractDocumentID($contractID);
    }
}
