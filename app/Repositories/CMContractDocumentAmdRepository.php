<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Models\CMContractDocumentAmd;
use App\Models\ErpDocumentAttachments;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Foundation\Application;
use Exception;
use Illuminate\Support\Facades\Log;
/**
 * Class CMContractDocumentAmdRepository
 * @package App\Repositories
 * @version July 4, 2024, 10:08 am +04
*/

class CMContractDocumentAmdRepository extends BaseRepository
{
    /**
     * @var array
     */

    protected $contractDocument;
    protected $erpAttachmentAmdRepo;
    protected $erpDocument;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function contractDocumentRepo()
    {
        if (!$this->contractDocument)
        {
            $this->contractDocument = $this->app->make(ContractDocumentRepository::class);
        }
        return $this->contractDocument;
    }

    public function erpDocumentAmd()
    {
        if (!$this->erpAttachmentAmdRepo)
        {
            $this->erpAttachmentAmdRepo = $this->app->make(ErpDocumentAttachmentsAmdRepository::class);
        }
        return $this->erpAttachmentAmdRepo;
    }
    public function erpDocument()
    {
        if (!$this->erpDocument)
        {
            $this->erpDocument = $this->app->make(ErpDocumentAttachmentsRepository::class);
        }
        return $this->erpDocument;
    }




    protected $fieldSearchable = [
        'contract_doc_id',
        'contract_history_id',
        'uuid',
        'contractID',
        'documentMasterID',
        'documentType',
        'documentName',
        'documentDescription',
        'attachedDate',
        'followingRequest',
        'status',
        'receivedBy',
        'receivedDate',
        'receivedFormat',
        'documentVersionNumber',
        'documentResponsiblePerson',
        'documentExpiryDate',
        'returnedBy',
        'returnedDate',
        'returnedTo',
        'companySystemID',
        'created_by',
        'updated_by'
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
        return CMContractDocumentAmd::class;
    }

    public function save($historyId, $contractId, $input)
    {
        try
        {
            $getContractDocument = $this->contractDocumentRepo()->getcontractDocumentData($contractId);

            foreach ($getContractDocument as $record)
            {
                $recordData = $record->toArray();
                $recordData['contract_doc_id'] = $record['id'];
                $recordData['contract_history_id'] = $historyId;
                $newRecord = $this->model->create($recordData);
                $this->insertErpDocumentAmd($newRecord->id, $record['id'],$historyId);
            }
        } catch (Exception $e)
        {
            throw new ContractCreationException("Contract Retention failed: " . $e->getMessage());
        }
    }

    private function insertErpDocumentAmd($newContractId, $oldContractId, $historyId)
    {
        // Retrieve the corresponding records in erp_document
        $erpDocuments = ErpDocumentAttachments::getErpAttachedData('COD', [$oldContractId]);

        foreach ($erpDocuments as $erpDocument)
        {
            $newErpDocument = $erpDocument->replicate();
            $newErpDocument->documentSystemCode = $newContractId;
            $newErpDocument->contract_history_id = $historyId;
            $newErpDocument->attachmentID = $erpDocument->attachmentID;
            ErpDocumentAttachmentsAmd::create($newErpDocument->toArray());
        }
    }
}
