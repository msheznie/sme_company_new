<?php

namespace App\Http\Controllers\Tender\API;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class TenderManagementController extends Controller
{
    public $fileService = '';

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * handle download
     * @param Request $request
     * @return StreamedResponse
     * @throws Throwable
     */
    public function downloadAttachment(Request $request)
    {
        $input = $request->all();
        $path = $input['attachment'];
        $storageDriver = 's3ERP';
        try {
            return $this->fileService->downloadFile($path, $storageDriver);
        } catch (\Exception $e) {
            return new StreamedResponse('Attachment Not Found');
        }
    }

    public function removeAttachment(Request $request)
    {
        $input = $request->all();
        $path = $input['attachment'];
        $storageDriver = 's3ERP';
        try {
            return $this->fileService->removeFile($path, $storageDriver);
        } catch (\Exception $e) {
            return new StreamedResponse('Attachment Not Found');
        }
    }
}
