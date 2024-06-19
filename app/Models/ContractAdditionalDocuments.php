<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Awobaz\Compoships\Compoships;
use App\Traits\HasContractIdColumn;

/**
 * Class ContractAdditionalDocuments
 * @package App\Models
 * @version May 15, 2024, 5:23 am +04
 *
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
class ContractAdditionalDocuments extends Model
{
    use Compoships;
    use HasFactory;
    use HasContractIdColumn;

    public $table = 'cm_contract_additional_document';

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
        'expiryDate',
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
        'expiryDate' => 'string',
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
        return $this->belongsTo(ErpDocumentAttachments::class, ['documentMasterID', 'id'],
            ['documentSystemID', 'documentSystemCode']);
    }
    public function additionalDocumentList($selectedCompanyID, $contractID){
        return ContractAdditionalDocuments::select('id', 'documentMasterID', 'uuid', 'documentType', 'documentName',
            'documentDescription', 'expiryDate')
            ->with([
                'documentMaster' => function ($query) {
                    $query->select('id', 'uuid', 'documentType');
                },
                'attachment' => function ($query) {
                    $query->select('attachmentID', 'documentSystemID', 'documentSystemCode', 'myFileName');
                }
            ])
            ->where([
                'contractID' => $contractID,
                'companySystemID' => $selectedCompanyID
            ])
            ->orderBy('id', 'desc');
    }

    public static function getContractAdditionalDocuments($contractId, $selectedCompanyID)
    {
        return self::where('companySystemID',$selectedCompanyID)
            ->where('contractID',$contractId)
            ->get();
    }

    public static function getContractIdColumn()
    {
        return 'contractID';
    }
}
