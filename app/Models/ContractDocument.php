<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractDocument
 * @package App\Models
 * @version May 8, 2024, 5:23 pm +04
 *
 * @property string $uuid
 * @property integer $documentType
 * @property string $documentName
 * @property string $documentDescription
 * @property string $attachedDate
 * @property boolean $followingRequest
 * @property boolean $status
 * @property string $receivedBy
 * @property string|\Carbon\Carbon $receivedDate
 * @property integer $receivedFormat
 * @property number $documentVersionNumber
 * @property string $documentResponsiblePerson
 * @property string $documentExpiryDate
 * @property string $returnedBy
 * @property string|\Carbon\Carbon $returnedDate
 * @property string $returnedTo
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractDocument extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'cm_contract_document';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'documentType'];

    public $fillable = [
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
        'uuid' => 'string',
        'contractID' => 'integer',
        'documentMasterID' => 'integer',
        'documentType' => 'integer',
        'documentName' => 'string',
        'documentDescription' => 'string',
        'attachedDate' => 'string',
        'followingRequest' => 'integer',
        'status' => 'integer',
        'receivedBy' => 'string',
        'receivedDate' => 'string',
        'receivedFormat' => 'integer',
        'documentVersionNumber' => 'float',
        'documentResponsiblePerson' => 'string',
        'documentExpiryDate' => 'date',
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
    public static $rules = [

    ];
    public function documentMaster() {
        return $this->belongsTo(DocumentMaster::class, 'documentType', 'id');
    }
    public function attachment(){
        return $this->belongsTo(ErpDocumentAttachments::class, 'documentMasterID', 'documentSystemID');
    }
    public function contractDocuments($selectedCompanyID, $contractID) {
        return ContractDocument::select('uuid', 'documentType', 'documentName', 'documentDescription',
            'followingRequest', 'attachedDate', 'status')
            ->with(['documentMaster' => function ($query) {
                $query->select('id', 'uuid', 'documentType');
            }])
            ->where([
                'contractID' => $contractID,
                'companySystemID' => $selectedCompanyID
            ]);
    }
}
