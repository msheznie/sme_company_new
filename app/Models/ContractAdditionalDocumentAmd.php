<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractAdditionalDocumentAmd
 * @package App\Models
 * @version July 8, 2024, 3:08 pm +04
 *
 * @property integer $additional_doc_id
 * @property string $uuid
 * @property integer $contractID
 * @property integer $documentMasterID
 * @property integer $documentType
 * @property string $documentName
 * @property string $documentDescription
 * @property string $expiryDate
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractAdditionalDocumentAmd extends Model
{

    use HasFactory;
    use Compoships;
    public $table = 'cm_contract_additional_document_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
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
        'updated_by',
        'contract_history_id',
        'is_editable'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'additional_doc_id' => 'integer',
        'contract_history_id' => 'integer',
        'uuid' => 'string',
        'contractID' => 'integer',
        'documentMasterID' => 'integer',
        'documentType' => 'integer',
        'documentName' => 'string',
        'documentDescription' => 'string',
        'expiryDate' => 'string',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'is_editable' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public function documentMaster()
    {
        return $this->belongsTo(DocumentMaster::class, 'documentType', 'id');
    }
    public function attachment()
    {
        return $this->belongsTo(ErpDocumentAttachmentsAmd::class, ['documentMasterID', 'id'],
            ['documentSystemID', 'documentSystemCode']);
    }

    public static function getAdditionalDocument($uuid, $historyId)
    {
        return self::where('uuid', $uuid)
            ->where('contract_history_id', $historyId)
            ->first();
    }

    public static function getAdditionalDocumentDataAmd($historyId)
    {
        return self::where('contract_history_id',$historyId)
            ->whereNull('additional_doc_id')
            ->get();
    }

}
