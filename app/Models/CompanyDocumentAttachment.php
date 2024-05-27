<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CompanyDocumentAttachment
 * @package App\Models
 * @version May 22, 2024, 10:40 am +04
 *
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $documentSystemID
 * @property string $documentID
 * @property string $docRefNumber
 * @property integer $isAttachmentYN
 * @property integer $sendEmailYN
 * @property string $codeGeneratorFormat
 * @property integer $isAmountApproval
 * @property integer $isServiceLineAccess
 * @property integer $isServiceLineApproval
 * @property integer $isCategoryApproval
 * @property integer $blockYN
 * @property integer $enableAttachmentAfterApproval
 * @property string|\Carbon\Carbon $timeStamp
 */
class CompanyDocumentAttachment extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'companydocumentattachment';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';



    public $fillable = [
        'companySystemID',
        'companyID',
        'documentSystemID',
        'documentID',
        'docRefNumber',
        'isAttachmentYN',
        'sendEmailYN',
        'codeGeneratorFormat',
        'isAmountApproval',
        'isServiceLineAccess',
        'isServiceLineApproval',
        'isCategoryApproval',
        'blockYN',
        'enableAttachmentAfterApproval',
        'timeStamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'companyDocumentAttachmentID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'docRefNumber' => 'string',
        'isAttachmentYN' => 'integer',
        'sendEmailYN' => 'integer',
        'codeGeneratorFormat' => 'string',
        'isAmountApproval' => 'integer',
        'isServiceLineAccess' => 'integer',
        'isServiceLineApproval' => 'integer',
        'isCategoryApproval' => 'integer',
        'blockYN' => 'integer',
        'enableAttachmentAfterApproval' => 'integer',
        'timeStamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'companySystemID' => 'nullable|integer',
        'companyID' => 'nullable|string|max:45',
        'documentSystemID' => 'nullable|integer',
        'documentID' => 'nullable|string|max:45',
        'docRefNumber' => 'nullable|string|max:300',
        'isAttachmentYN' => 'nullable|integer',
        'sendEmailYN' => 'nullable|integer',
        'codeGeneratorFormat' => 'nullable|string|max:300',
        'isAmountApproval' => 'nullable|integer',
        'isServiceLineAccess' => 'nullable|integer',
        'isServiceLineApproval' => 'nullable|integer',
        'isCategoryApproval' => 'nullable|integer',
        'blockYN' => 'nullable|integer',
        'enableAttachmentAfterApproval' => 'nullable|integer',
        'timeStamp' => 'nullable'
    ];

    public static function companyDocumentAttachment($companySystemID, $documentSystemID)
    {
        return CompanyDocumentAttachment::select('companyDocumentAttachmentID', 'companySystemID', 'documentSystemID',
        'documentID', 'isAttachmentYN', 'sendEmailYN', 'codeGeneratorFormat', 'isAmountApproval')
            ->where('companySystemID', $companySystemID)
            ->where('documentSystemID', $documentSystemID)
            ->first();
    }
}
