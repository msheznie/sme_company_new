<?php

namespace App\Helpers;

use App\Models\Employees;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class General
{
    public static function currentUser()
    {
        return Auth::user();
    }

    public static function currentEmployee()
    {
        return Auth::user()->employee;
    }

    public static function currentUserId()
    {
        return Auth::id();
    }

    public static function currentUserInfo()
    {
        return array();
    }

    public static function currentEmployeeId()
    {
        return Auth::user()->employee_id;
    }

    public static function getRequestPc()
    {
        return gethostname();
    }

    public static function getFileUrlFromS3($key)
    {
        if ($key) {
            $s3 = Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();
            $bucket = Config::get('filesystems.disks.s3.bucket');
            $command = $client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => $key
            ]);
            $request = $client->createPresignedRequest($command, '+60 minutes');
            return (string)$request->getUri();
        }
        return '';
    }
}
