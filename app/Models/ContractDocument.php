<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;
/**
 * Class ContractDocument
 * @package App\Models
 * @version May 8, 2024, 5:23 pm +04
 *
 * @property string $uuid
 * @property integer $contractID
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
    use HasFactory;
    use HasContractIdColumn;
    use HasCompanyIdColumn;
    use Compoships;

    public $table = 'cm_contract_document';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = ['id'];

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
        'updated_by',
        'attach_after_approval',
        'is_editable'
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
        'documentVersionNumber' => 'string',
        'documentResponsiblePerson' => 'string',
        'documentExpiryDate' => 'string',
        'returnedBy' => 'string',
        'returnedDate' => 'string',
        'returnedTo' => 'string',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'attach_after_approval' => 'integer',
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
        return $this->belongsTo(ErpDocumentAttachments::class, ['documentMasterID', 'id'],
            ['documentSystemID', 'documentSystemCode']);
    }
    public function contractDocuments($selectedCompanyID, $contractID)
    {
        return ContractDocument::select('uuid', 'documentType', 'documentName', 'documentDescription',
            'followingRequest', 'attachedDate', 'status','documentExpiryDate', 'attach_after_approval',
            'is_editable')
            ->with(['documentMaster' => function ($query)
            {
                $query->select('id', 'uuid', 'documentType');
            }])
            ->where([
                'contractID' => $contractID,
                'companySystemID' => $selectedCompanyID
            ])
            ->orderBy('id', 'desc');
    }

    public static function getContractDocuments($contractId, $selectedCompanyID)
    {
        return self::where('companySystemID',$selectedCompanyID)
            ->where('contractID',$contractId)
            ->get();
    }
    public function pluckContractDocumentID($contractID)
    {
        return ContractDocument::where('contractID', $contractID)->pluck('id');
    }

    public static function getContractIdColumn()
    {
        return 'contractID';
    }

    public static function getCompanyIdColumn()
    {
        return 'companySystemID';
    }

    public function getcontractDocumentData($contractId)
    {
        return self::where('contractID',$contractId)
            ->get();
    }

    public function fetchByUuid($uuid)
    {
        return self::where('uuid',$uuid)
            ->with([
                'attachment' => function ($q)
                {
                    $q->select('attachmentID', 'myFileName', 'documentSystemID',
                        'documentSystemCode','originalFileName','path');
                },
                'documentMaster' => function ($q1)
                {
                    $q1->select('id', 'uuid');
                }
            ])
            ->first();
    }
}
