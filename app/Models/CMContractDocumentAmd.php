<?php

namespace App\Models;

use App\Utilities\ContractManagementUtils;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
/**
 * Class CMContractDocumentAmd
 * @package App\Models
 * @version July 4, 2024, 10:08 am +04
 *
 * @property integer $contract_doc_id
 * @property integer $contract_history_id
 * @property string $uuid
 * @property integer $contractID
 * @property integer $documentMasterID
 * @property integer $documentType
 * @property string $documentName
 * @property string $documentDescription
 * @property string $attachedDate
 * @property boolean $followingRequest
 * @property boolean $status
 * @property string $receivedBy
 * @property string|\Carbon\Carbon $receivedDate
 * @property integer $receivedFormat
 * @property string $documentVersionNumber
 * @property string $documentResponsiblePerson
 * @property string $documentExpiryDate
 * @property string $returnedBy
 * @property string|\Carbon\Carbon $returnedDate
 * @property string $returnedTo
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class CMContractDocumentAmd extends Model
{
    use HasFactory;

    public $table = 'cm_contract_document_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contract_doc_id' => 'integer',
        'contract_history_id' => 'integer',
        'uuid' => 'string',
        'contractID' => 'integer',
        'documentMasterID' => 'integer',
        'documentType' => 'integer',
        'documentName' => 'string',
        'documentDescription' => 'string',
        'attachedDate' => 'string',
        'followingRequest' => 'integer',
        'status' => 'boolean',
        'receivedBy' => 'string',
        'receivedDate' => 'string',
        'receivedFormat' => 'integer',
        'documentVersionNumber' => 'string',
        'documentResponsiblePerson' => 'string',
        'documentExpiryDate' => 'string',
        'returnedBy' => 'string',
        'returnedDate' => 'string',
        'returnedTo' => 'string',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function contractDocuments($selectedCompanyID, $contractID,$contractHistoryUuid)
    {
        $getHistoryData = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
        return self::select('uuid', 'documentType', 'documentName', 'documentDescription',
            'followingRequest', 'attachedDate', 'status','contract_doc_id')
            ->with(['documentMaster' => function ($query)
            {
                $query->select('id', 'uuid', 'documentType');
            }])
            ->where([
                'contractID' => $contractID,
                'contract_history_id'=>$getHistoryData->id,
                'companySystemID' => $selectedCompanyID
            ])
            ->orderBy('id', 'desc');
    }

    public function documentMaster()
    {
        return $this->belongsTo(DocumentMaster::class, 'documentType', 'id');
    }

    public function getContractDocumentData($contractDocumentUuid,$historyId)
    {
        $contractDocumentData = self::getContractDocumentUuid($contractDocumentUuid,$historyId);
        $id = $contractDocumentData->id;

        return CMContractDocumentAmd::select('uuid', 'documentMasterID','contract_doc_id',
            'receivedBy', 'receivedDate', 'receivedFormat', 'documentVersionNumber', 'documentResponsiblePerson',
            'documentExpiryDate', 'documentName', 'documentDescription', 'returnedBy', 'returnedDate', 'returnedTo')
            ->with([
                'attachment' => function ($q) use ($id)
                {
                    $q->select(DB::raw('id AS attachmentID'), 'myFileName', 'documentSystemID', 'documentSystemCode')
                        ->where('documentSystemCode', $id);
                }
            ])
            ->where('id', $id)
            ->first();


    }

    public function getContractDocumentUuid($contractDocumentUuid,$historyId)
    {
        return self::where('uuid', $contractDocumentUuid)
            ->where('contract_history_id',$historyId)
            ->first();
    }

    public function attachment()
    {
        return $this->belongsTo(ErpDocumentAttachmentsAmd::class, 'documentMasterID', 'documentSystemID');
    }


    public function getcontractDocumentDataAmd($contractId)
    {
        return self::where('contract_history_id',$contractId)
            ->whereNull('contract_doc_id')
            ->get();
    }
}
