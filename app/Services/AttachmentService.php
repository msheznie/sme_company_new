<?php

namespace App\Services;

use App\Models\ContractMaster;
use App\Models\ContractDocument;
class AttachmentService
{
    public function getDocumentSystemID($uuid, $documentMasterID)
    {
        $ids = [];
        switch($documentMasterID)
        {
            case 121:
                $ids = self::getContractDocumentID($uuid);
                break;
            default:
                break;
        }
        return $ids;
    }

    private function getContractDocumentID($uuid)
    {
        $contractMaster = ContractMaster::select('id')->where('uuid', $uuid)->first();
        $contractID = $contractMaster['id'] ?? 0;
        return  ContractDocument::where('contractID', $contractID)->pluck('id');
    }
}
