<?php

namespace App\Http\Controllers\DeliveryAppointment\API;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Exception;
use Throwable;

class AppointmentController extends Controller
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
        if (Storage::disk('s3ERP')->exists($path)) {
            try {
                return Storage::disk('s3ERP')->download($path, 'Attachment');
            } catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
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
