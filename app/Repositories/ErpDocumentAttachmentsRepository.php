<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Exceptions\HttpError;
use App\Helpers\General;
use App\Models\ContractHistory;
use App\Models\DocumentAttachments;
use App\Models\DocumentMaster;
use App\Models\ErpDocumentAttachments;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Models\ErpDocumentMaster;
use App\Repositories\BaseRepository;
use App\Rules\SafeFile;
use App\Services\AttachmentService;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function downloadFile($attachmentID)
    {
        $documentAttachment = ErpDocumentAttachments::where('attachmentID', $attachmentID)->first();
        if (!$documentAttachment)
        {
            return [
                'status' => false,
                'message' => trans('common.attachment_is_not_attached'),
                'code' => 404
            ];
        }
        $disk = 's3';
        if (Storage::disk($disk)->exists($documentAttachment->path))
        {
            $attachmentResp =  Storage::disk($disk)
                ->download($documentAttachment->path, $documentAttachment->myFileName);
            return [
                'status' => true,
                'message' => trans('common.attachment_downloaded_successfully'),
                'data' => $attachmentResp
            ];
        }
        else
        {
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

    public function getHistoryAttachments($request)
    {
        try
        {
            $formData = $request->all();
            $companySystemID = $formData['selectedCompanyID'] ?? 0;
            if(isset($formData['newContractHistoryId']) && $formData['newContractHistoryId'] != -1)
            {
                $documentSystemCode = $formData['newContractHistoryId'];
            } else
            {
                $contractHistoryDetails = ContractHistory::getContractHistory(
                    $formData['historyUuid'],
                    $companySystemID);

                if (!$contractHistoryDetails)
                {
                    throw new CommonException(trans('common.contract_history_not_found'));
                }

                $documentSystemCode = $contractHistoryDetails->id;
            }

            return $this->model->getAttachmentDocumentWise($formData['documentMaster'],
                $documentSystemCode,
                $companySystemID);
        } catch (\Exception $e)
        {
            return [
                'status' => false,
                'message' => trans('common.attachment_not_found'),
                'code' => 500
            ];
        }
    }

    public function deleteHistoryAttachment($attachmentId): array
    {
        $deleteAttachment = ErpDocumentAttachments::deleteHistoryAttachment($attachmentId);
        if(!$deleteAttachment['status'])
        {
            return [
                'status' => false,
                'message' =>  $deleteAttachment['message'],
                'code' => 404
            ];
        }
        return [
            'status' => true,
            'message' =>  trans('common.attachment_successfully_deleted')
        ];
    }

    public function updateDocumentAttachments(Request $request, $attachmentID)
    {
        $response = [
            'status' => true,
            'message' => trans('common.document_updated_successfully'),
            'code' => 200
        ];

        $input = $request->all();

        $validationResult = $this->validateFile($input);
        if (!$validationResult['status'])
        {
            return $validationResult;
        }

        DB::beginTransaction();
        try
        {
            $documentAttachment = ErpDocumentAttachments::where('attachmentID', $attachmentID)->first();

            if (!$documentAttachment)
            {
                $response = [
                    'status' => false,
                    'message' => trans('common.attachment_not_found'),
                    'code' => 404
                ];
            } else
            {
                $updateData = [
                    'originalFileName' => $input['originalFileName'],
                    'attachmentDescription' => $input['description'],
                    'sizeInKbs' => $input['sizeInKbs'],
                    'timeStamp' => now()
                ];

                if ($request->has('file'))
                {
                    $fileUploadResult = $this->uploadFile($request->input('file'),
                        $input['fileType'], $documentAttachment);
                    if (!$fileUploadResult['status'])
                    {
                        return $fileUploadResult;
                    }
                    $updateData = array_merge($updateData, $fileUploadResult['data']);
                }

                $documentAttachment->update($updateData);

                DB::commit();
            }
        } catch (\Exception $exception)
        {
            DB::rollBack();
            $response = [
                'status' => false,
                'message' => $exception->getMessage(),
                'code' => 500
            ];
        }

        return $response;
    }

    public function saveDocumentAttachments(Request $request, $documentSystemCode,$historyId = 0)
    {
        $input = $request->all();
        $amendment = $input['amendment'] ?? false;
        $response = [
            'status' => true,
            'message' => trans('common.document_uploaded_successfully'),
            'code' => 200
        ];

        $validationResult = $this->validateFile($input);
        if (!$validationResult['status'])
        {
            return $validationResult;
        }

        DB::beginTransaction();
        try
        {
            $companyID = General::getCompanyById($input['selectedCompanyID']);
            $documentID = $this->getDocumentID($input);
            $documentSystemID = $input['documentMasterID'] ?? $input['documentMaster'];

            $postData = [
                'companySystemID' => $input['selectedCompanyID'],
                'companyID' => $companyID,
                'documentSystemID' => $documentSystemID,
                'documentID' => $documentID,
                'documentSystemCode' => $documentSystemCode,
                'attachmentDescription' => $input['attachmentName'] ?? 'Document'
            ];

            $model = $amendment ? ErpDocumentAttachmentsAmd::class : ErpDocumentAttachments::class;

            if($amendment)
            {
                $postData['contract_history_id'] = $historyId;
            }

            $documentAttachments = $model::create($postData);

            $fileUploadResult = $this->uploadFile($request->input('file'), $input['fileType'], $documentAttachments);
            if (!$fileUploadResult['status'])
            {
                $response = $fileUploadResult;
            } else
            {
                $postData = array_merge($postData, $fileUploadResult['data'], [
                    'sizeInKbs' => $input['sizeInKbs'],
                    'originalFileName' => $input['originalFileName']
                ]);

                if($amendment)
                {
                    ErpDocumentAttachmentsAmd::where('id', $documentAttachments->id)->update($postData);
                }else
                {
                    ErpDocumentAttachments::where('attachmentID', $documentAttachments->attachmentID)->update($postData);
                }


                DB::commit();
            }
        } catch (\Exception $exception)
        {
            DB::rollBack();
            $response = [
                'status' => false,
                'message' => $exception->getMessage(),
                'code' => 500
            ];
        }

        return $response;
    }

    private function validateFile($input)
    {
        $extension = $input['fileType'];
        $blockExtensions = ['ace', 'ade', 'adp', 'ani', 'app', 'asp', 'aspx', 'asx', 'bas', 'bat', 'cla', 'cer', 'chm',
            'cmd', 'cnt', 'com', 'cpl', 'crt', 'csh', 'class', 'der', 'docm', 'exe', 'fxp', 'gadget', 'hlp', 'hpj',
            'hta', 'htc', 'inf', 'ins', 'isp', 'its', 'jar', 'js', 'jse', 'ksh', 'lnk', 'mad', 'maf', 'mag', 'mam',
            'maq', 'mar', 'mas', 'mat', 'mau', 'mav', 'maw', 'mda', 'mdb', 'mde', 'mdt', 'mdw', 'mdz', 'mht', 'mhtml',
            'msc', 'msh', 'msh1', 'msh1xml', 'msh2', 'msh2xml', 'mshxml', 'msi', 'msp', 'mst', 'ops', 'osd', 'ocx',
            'pl', 'pcd', 'pif', 'plg', 'prf', 'prg', 'ps1', 'ps1xml', 'ps2', 'ps2xml', 'psc1', 'psc2', 'pst', 'reg',
            'scf', 'scr', 'sct', 'shb', 'shs', 'tmp', 'url', 'vb', 'vbe', 'vbp', 'vbs', 'vsmacros', 'vss', 'vst',
            'vsw', 'ws', 'wsc', 'wsf', 'wsh', 'xml', 'xbap', 'xnk', 'php'];

        if (in_array($extension, $blockExtensions))
        {
            return [
                'status' => false,
                'message' => trans('common.upload_file_type_not_allowed'),
                'code' => 500
            ];
        }

        if (isset($input['size']) && $input['size'] > 31457280)
        {
            return [
                'status' => false,
                'message' => trans('common.maximum_file_size_allowed'),
                'code' => 500
            ];
        }

        return ['status' => true];
    }

    private function uploadFile($file, $extension, $documentAttachment)
    {
        try
        {
            $decodeFile = base64_decode($file);

            $validator = \Validator::make(
                ['file' => $decodeFile],
                ['file' => [new SafeFile]]
            );

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $myFileName = $documentAttachment->companyID . '_' .
                $documentAttachment->documentSystemCode . '_' .
                $documentAttachment->attachmentID . '.' . $extension;
            $disk = 's3';

            $path = $documentAttachment->documentID . '/' . $documentAttachment->documentSystemCode . '/' . $myFileName;
            Storage::disk($disk)->put($path, $decodeFile);

            return [
                'status' => true,
                'data' => [
                    'isUploaded' => 1,
                    'path' => $path,
                    'myFileName' => $myFileName
                ]
            ];
        } catch (\Exception $exception)
        {
            return [
                'status' => false,
                'message' => $exception->getMessage(),
                'code' => 500
            ];
        }
    }

    private function getDocumentID($input)
    {
        if (isset($input['contractHistory']) && $input['contractHistory'])
        {
            return $input['documentMasterCode'];
        } else
        {
            $documentMaster = ErpDocumentMaster::documentMasterData($input['documentMasterID']);
            if ($documentMaster)
            {
                return $documentMaster->documentID;
            }
        }

        return '';
    }
    public function getErpAttachedData($documentCode,$getContractDocument)
    {
        return $this->model->getErpAttachedData($documentCode,$getContractDocument);
    }

    public function downloadFileAmd($attachmentID)
    {
        $documentAttachment = ErpDocumentAttachmentsAmd::where('id', $attachmentID)->first();
        if (!$documentAttachment)
        {
            return [
                'status' => false,
                'message' => trans('common.attachment_is_not_attached'),
                'code' => 404
            ];
        }
        $disk = 's3';
        if (Storage::disk($disk)->exists($documentAttachment->path))
        {
            $attachmentResp =  Storage::disk($disk)
                ->download($documentAttachment->path, $documentAttachment->myFileName);
            return [
                'status' => true,
                'message' => trans('common.attachment_downloaded_successfully'),
                'data' => $attachmentResp
            ];
        }
        else
        {
            return [
                'status' => false,
                'message' => trans('common.attachment_not_found'),
                'code' => 500
            ];
        }

    }

    public function deleteDocumentAttachment($attachmentId, $path)
    {
        $attachment = $this->model->where('attachmentId', $attachmentId)->first();

        if (!$attachment)
        {
            GeneralService::sendException('Attachment not found.');
        }

        if (Storage::disk('s3')->exists($path))
        {
            if (!Storage::disk('s3')->delete($path))
            {
                GeneralService::sendException('Failed to delete file from S3.');
            }
        }
        else
        {
            GeneralService::sendException('File not found on S3.');
        }

        if (!$attachment->delete())
        {
            GeneralService::sendException('Failed to delete the attachment record.');
        }

        return true;
    }

}
