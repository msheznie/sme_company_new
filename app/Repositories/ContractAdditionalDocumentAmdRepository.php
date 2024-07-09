<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Models\ContractAdditionalDocumentAmd;
use App\Models\ErpDocumentAttachments;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Foundation\Application;
use Exception;
use Illuminate\Support\Facades\Log;
/**
 * Class ContractAdditionalDocumentAmdRepository
 * @package App\Repositories
 * @version July 8, 2024, 3:08 pm +04
*/

class ContractAdditionalDocumentAmdRepository extends BaseRepository
{
    protected $contractAdditionalDocument;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'additional_doc_id',
        'uuid',
        'contractID',
        'documentMasterID',
        'documentType',
        'documentName',
        'documentDescription',
        'expiryDate',
        'companySystemID',
        'created_by',
        'updated_by'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function contractAdditionalDocumentRepo()
    {
        if (!$this->contractAdditionalDocument)
        {
            $this->contractAdditionalDocument = $this->app->make(ContractAdditionalDocumentsRepository::class);
        }
        return $this->contractAdditionalDocument;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContractAdditionalDocumentAmd::class;
    }

    public function save($input, $contractId, $historyId)
    {
        try
        {
            $contractAdditional = $this->contractAdditionalDocumentRepo()->additionalDocumentData($contractId);
            foreach ($contractAdditional as $record)
            {
                $recordData = $record->toArray();
                $recordData['additional_doc_id'] = $record['id'];
                $recordData['contract_history_id'] = $historyId;

                $newRecord = $this->model->create($recordData);
                Log::info($record['id']);

                $this->insertErpDocumentAmd($newRecord->id, $record['id'],$historyId);
            }
        } catch (Exception $e)
        {
            throw new ContractCreationException("Contract additional document failed: " . $e->getMessage());
        }
    }
    private function insertErpDocumentAmd($newContractId, $oldContractId, $historyId)
    {
        $erpDocuments = ErpDocumentAttachments::getErpAttachedData('COAD', [$oldContractId]);

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
