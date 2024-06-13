<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\DocumentAttachments;
use App\Models\DocumentMaster;
use App\Models\ErpDocumentAttachments;
use App\Models\ErpDocumentMaster;
use App\Repositories\BaseRepository;
use App\Services\AttachmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

/**
 * Class ErpDocumentAttachmentsRepository
 * @package App\Repositories
 * @version May 8, 2024, 5:21 pm +04
*/

class ErpDocumentAttachmentsRepository extends BaseRepository
{
    /**
     * @var array
     */

    protected $fieldSearchable = [
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
        return ErpDocumentAttachments::class;
    }

    public function saveDocumentAttachments(Request $request, $documentSystemCode){
        $input = $request->all();
        $extension = $input['fileType'];
        $blockExtensions = ['ace', 'ade', 'adp', 'ani', 'app', 'asp', 'aspx', 'asx', 'bas', 'bat', 'cla', 'cer', 'chm',
            'cmd', 'cnt', 'com', 'cpl', 'crt', 'csh', 'class', 'der', 'docm', 'exe', 'fxp', 'gadget', 'hlp', 'hpj',
            'hta', 'htc', 'inf', 'ins', 'isp', 'its', 'jar', 'js', 'jse', 'ksh', 'lnk', 'mad', 'maf', 'mag', 'mam',
            'maq', 'mar', 'mas', 'mat', 'mau', 'mav', 'maw', 'mda', 'mdb', 'mde', 'mdt', 'mdw', 'mdz', 'mht', 'mhtml',
            'msc', 'msh', 'msh1', 'msh1xml', 'msh2', 'msh2xml', 'mshxml', 'msi', 'msp', 'mst', 'ops', 'osd', 'ocx',
            'pl', 'pcd', 'pif', 'plg', 'prf', 'prg', 'ps1', 'ps1xml', 'ps2', 'ps2xml', 'psc1', 'psc2', 'pst', 'reg',
            'scf', 'scr', 'sct', 'shb', 'shs', 'tmp', 'url', 'vb', 'vbe', 'vbp', 'vbs', 'vsmacros', 'vss', 'vst',
            'vsw', 'ws', 'wsc', 'wsf', 'wsh', 'xml', 'xbap', 'xnk','php'];

        if (in_array($extension, $blockExtensions))
        {
            return [
                'status' => false,
                'message' => trans('common.upload_file_type_not_allowed'),
                'code' => 500
            ];
        }

        if(isset($input['size']) && $input['size'] > 31457280){
            return [
                'status' => false,
                'message' => trans('common.maximum_file_size_allowed'),
                'code' => 500
            ];
        }
        DB::beginTransaction();
        try {
            $companyID = General::getCompanyById($input['selectedCompanyID']);
            $documentID = '';
            $documentMaster = ErpDocumentMaster::documentMasterData($input['documentMasterID']);
            if ($documentMaster) {
                $documentID = $documentMaster->documentID;
            }

            $postData = [
                'companySystemID' => $input['selectedCompanyID'],
                'companyID' => $companyID,
                'documentSystemID' => $input['documentMasterID'],
                'documentID' => $documentID,
                'documentSystemCode' => $documentSystemCode,
                'attachmentDescription' => $input['attachmentName'] ?? 'Document'
            ];
            if (isset($input['documentCode'])) {
                $documentCode = $input['documentCode'];
            }
            else{
                $documentCode = $documentMaster->documentID . '_' . $documentSystemCode;
            }
            $documentCode = str_replace("\\", "_", $documentCode);
            $documentAttachments = $this->model->create($postData);

            $file = $request->request->get('file');
            $decodeFile = base64_decode($file);
            $postData['myFileName'] = $documentAttachments->companyID . '_' . $documentCode . '_' .
                $documentAttachments->attachmentID . '.' . $extension;
            $disk = 's3';

            $path = $documentAttachments->documentID . '/' . $documentAttachments->documentSystemCode . '/' .
                $postData['myFileName'];
            Storage::disk($disk)->put($path, $decodeFile);

            $postData['isUploaded'] = 1;
            $postData['path'] = $path;
            $postData['sizeInKbs'] = $input['sizeInKbs'];
            $postData['originalFileName'] = $input['originalFileName'];

            ErpDocumentAttachments::where('attachmentID', $documentAttachments->id)
                ->update($postData);

            DB::commit();
            return [
                'status' => true,
                'message' => trans('common.document_uploaded_successfully')
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function downloadFile($attachmentID) {
        $documentAttachment = ErpDocumentAttachments::where('attachmentID', $attachmentID)->first();
        if (!$documentAttachment) {
            return [
                'status' => false,
                'message' => trans('common.attachment_is_not_attached'),
                'code' => 404
            ];
        }
        $disk = 's3';
        if (Storage::disk($disk)->exists($documentAttachment->path)) {
            $attachmentResp =  Storage::disk($disk)
                ->download($documentAttachment->path, $documentAttachment->myFileName);
            return [
                'status' => true,
                'message' => trans('common.attachment_downloaded_successfully'),
                'data' => $attachmentResp
            ];
        }
        else {
            return [
                'status' => false,
                'message' => trans('common.attachment_not_found'),
                'code' => 500
            ];
        }

    }
    public function getDocumentAttachments($documentSystemID, $search, $selectedCompanyID, $ids)
    {
        $languages =  $this->model->getDocumentAttachments(
            $search,
            $documentSystemID,
            $selectedCompanyID,
            $ids
        );
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }
}
