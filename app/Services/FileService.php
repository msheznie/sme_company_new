<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class FileService
{
    public function __construct(){}

    /**
     * upload files
     * @param $data
     * @return string
     */
    public function uploadFile($data): string {
        $storageType = $data['storageDriver'] ?? 's3';

        Storage::disk($storageType)->put($data['url'], $data['attachment']);

        return Storage::disk($storageType)->url($data['url']);
    }

    /**
     * remove file
     * @param $path
     * @param string $storageDriver
     * @return string
     * @throws Exception
     */
    public function removeFile($path, string $storageDriver = 'resource'): string {
        try{
            return Storage::disk($storageDriver)->delete($path);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * get file
     * @param $path
     * @param string $storageDriver
     * @return string
     * @throws Exception
     */
    public function getFile($path, string $storageDriver = 's3'): string {
        try{
            if(!Storage::disk($storageDriver)->exists($path)){
                throw new Exception("File doesn't exists");
            }

            return Storage::disk($storageDriver)->get($path);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * download attachment from storage
     * @param $path
     * @param string $storageDriver
     * @return StreamedResponse
     * @throws Throwable
     */
    public function downloadFile($path, string $storageDriver = 's3'): StreamedResponse {
        throw_unless(Storage::disk($storageDriver)->exists($path), 'Attachment not found in storage');
        return Storage::disk($storageDriver)->download($path);
    }
}
