<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Awobaz\Compoships\Compoships;

/**
 * Class ErpDocumentAttachments
 * @package App\Models
 * @version May 8, 2024, 5:21 pm +04
 *
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $documentSystemID
 * @property string $documentID
 * @property integer $documentSystemCode
 * @property integer $approvalLevelOrder
 * @property string $attachmentDescription
 * @property string $location
 * @property string $path
 * @property string $originalFileName
 * @property string $myFileName
 * @property string|\Carbon\Carbon $docExpirtyDate
 * @property integer $attachmentType
 * @property number $sizeInKbs
 * @property integer $isUploaded
 * @property integer $pullFromAnotherDocument
 * @property integer $parent_id
 * @property string|\Carbon\Carbon $timeStamp
 * @property integer $envelopType
 * @property integer $order_number
 */
class ErpDocumentAttachments extends Model
{
    use Compoships;
    use HasFactory;

    public $table = 'erp_documentattachments';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';

    protected $hidden = ['documentSystemCode'];

    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'attachmentID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'documentSystemCode' => 'integer',
        'approvalLevelOrder' => 'integer',
        'attachmentDescription' => 'string',
        'location' => 'string',
        'path' => 'string',
        'originalFileName' => 'string',
        'myFileName' => 'string',
        'docExpirtyDate' => 'datetime',
        'attachmentType' => 'integer',
        'sizeInKbs' => 'float',
        'isUploaded' => 'integer',
        'pullFromAnotherDocument' => 'integer',
        'parent_id' => 'integer',
        'timeStamp' => 'datetime',
        'envelopType' => 'integer',
        'order_number' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static function deleteAttachment($documentSystemID, $documentSystemCode): array
    {
        $deleteFile = ErpDocumentAttachments::select('attachmentID')
            ->where('documentSystemID', $documentSystemID)
            ->where('documentSystemCode', $documentSystemCode)
            ->first();
        if(!empty($deleteFile))
        {
            $delete = ErpDocumentAttachments::where('attachmentID', $deleteFile['attachmentID'])->delete();
            if(!$delete)
            {
                return [
                    'status' => false,
                    'message' => trans('common.could_not_delete_attachment')
                ];
            }
        }
        return [
            'status' => true,
            'message' => trans('common.attachment_document_deleted_successfully')
        ];
    }

    public function getAttachmentDocumentWise($documentSystemID,$documentSystemCode,$companySystemID)
    {
        return self::where('companySystemID', $companySystemID)
            ->where('documentSystemID',$documentSystemID)
            ->where('documentSystemCode',$documentSystemCode)
            ->get();

    }
    public function getDocumentAttachments($search, $documentSystemID, $selectedCompanyID, $ids)
    {
        $query = ErpDocumentAttachments::select('attachmentID', 'attachmentDescription', 'myFileName',
                'documentSystemID'
            )
            ->with([
                'documentMaster' => function ($q)
                {
                    $q->select('documentSystemID', 'documentDescription');
                }
            ])
            ->where('documentSystemID', $documentSystemID)
            ->whereIn('documentSystemCode', $ids)
            ->orderBy('attachmentID', 'desc');
        if($search)
        {
            $search = str_replace("\\", "\\\\", $search);
            $query = $query->where(function ($query) use ($search)
            {
                $query->orWhere('attachmentDescription', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }
    public function documentMaster()
    {
        return $this->belongsTo(ErpDocumentMaster::class, 'documentSystemID', 'documentSystemID');
    }
}
