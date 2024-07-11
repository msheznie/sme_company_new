<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpDocumentAttachmentsAmd
 * @package App\Models
 * @version July 4, 2024, 12:18 pm +04
 *
 * @property integer $attachmentID
 * @property integer $contract_history_id
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
class ErpDocumentAttachmentsAmd extends Model
{


    use HasFactory;
    use Compoships;
    public $table = 'erp_documentattachments_amd';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';

    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'attachmentID' => 'integer',
        'contract_history_id' => 'integer',
        'id' => 'integer',
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
        'attachmentID' => 'nullable|integer',
        'contract_history_id' => 'nullable|integer',
        'companySystemID' => 'nullable|integer',
        'companyID' => 'nullable|string|max:45',
        'documentSystemID' => 'nullable|integer',
        'documentID' => 'nullable|string|max:45',
        'documentSystemCode' => 'nullable|integer',
        'approvalLevelOrder' => 'nullable|integer',
        'attachmentDescription' => 'nullable|string',
        'location' => 'nullable|string|max:500',
        'path' => 'nullable|string|max:500',
        'originalFileName' => 'nullable|string|max:500',
        'myFileName' => 'nullable|string|max:500',
        'docExpirtyDate' => 'nullable',
        'attachmentType' => 'nullable|integer',
        'sizeInKbs' => 'nullable|numeric',
        'isUploaded' => 'nullable|integer',
        'pullFromAnotherDocument' => 'nullable|integer',
        'parent_id' => 'nullable|integer',
        'timeStamp' => 'nullable',
        'envelopType' => 'nullable|integer',
        'order_number' => 'nullable|integer'
    ];

    public static function deleteAttachment($documentSystemID, $documentSystemCode, $historyId): array
    {
        $deleteFile = self::select('id')
            ->where('documentSystemID', $documentSystemID)
            ->where('documentSystemCode', $documentSystemCode)
            ->where('contract_history_id', $historyId )
            ->first();
        if(!empty($deleteFile))
        {
            $delete = self::where('id', $deleteFile['id'])->delete();
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
    public static function getErpAttachedData($documentCode,$id)
    {
        return self::where('documentID',$documentCode)
            ->where('documentSystemCode',$id)
            ->whereNull('attachmentID')
            ->get();
    }


}
