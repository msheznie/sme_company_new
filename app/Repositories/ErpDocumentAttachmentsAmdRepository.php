<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;
use Exception;
/**
 * Class ErpDocumentAttachmentsAmdRepository
 * @package App\Repositories
 * @version July 4, 2024, 12:18 pm +04
*/

class ErpDocumentAttachmentsAmdRepository extends BaseRepository
{
    protected $erpAttachmentRepo;
    public function erpDocumentRepo()
    {
        if (!$this->erpAttachmentRepo)
        {
            $this->erpAttachmentRepo = $this->app->make(ErpDocumentAttachmentsRepository::class);
        }
        return $this->erpAttachmentRepo;
    }
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'attachmentID',
        'contract_history_id',
        'companySystemID',
        'companyID',
        'documentSystemID',
        'documentID',
        'documentSystemCode',
        'approvalLevelOrder',
        'attachmentDescription',
        'location',
        'path',
        'originalFileName',
        'myFileName',
        'docExpirtyDate',
        'attachmentType',
        'sizeInKbs',
        'isUploaded',
        'pullFromAnotherDocument',
        'parent_id',
        'timeStamp',
        'envelopType',
        'order_number'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ErpDocumentAttachmentsAmd::class;
    }

    public function save($historyId,$contractId,$documentCode,$getContractDocument)
    {
        try
        {
            $getContractDocument = $this->erpDocumentRepo()->getErpAttachedData(
                $documentCode,$getContractDocument
            );

            foreach ($getContractDocument as $record)
            {
                $recordData = $record->toArray();
                $recordData['attachmentID'] = $record['attachmentID'];
                $recordData['contract_history_id'] = $historyId;
                $recordData['documentSystemCode'] = $record['documentSystemCode'];
                $this->model->create($recordData);
            }


        } catch
        (Exception $e)
        {
            throw new ContractCreationException("Test :" . $e->getMessage());
        }
    }
}
