<?php

namespace App\Helpers;

use App\Models\Company;
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
    public static function getArrayIds($data_array): array{
        return array_filter(array_map(function($data) {
            return $data['id'] ?? null;
        }, $data_array));
    }

    public static function getCompanyById($companySystemID)
    {
        $company = Company::select('CompanyID')->where("companySystemID", $companySystemID)->first();

        if (!empty($company)) {
            return $company->CompanyID;
        } else {
            return "";
        }
    }

    public static function getEmployeeCode($empId)
    {
        $employee = Employees::find($empId);
        if (!empty($employee)) {
            return $employee->empID;
        }
        return 0;
    }

    public static function convertDateTime($date)
    {
        return $date;
    }

    public static function checkEmployeeDischargedYN(): bool
    {
        $user = Users::find(Auth::id());
        if(!empty($user))
        {
            $employee = Employees::find($user->employee_id);
            if ($employee->discharegedYN == -1)
            {
                return true;
            } else
            {
                return false;
            }
        }
        return false;
    }
    public static function ordinalSuffix($number)
    {
        $suffix = 'th';
        if (!in_array(($number % 100), [11, 12, 13]))
        {
            switch ($number % 10)
            {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
                default:
                    $suffix = '';
                    break;
            }
        }
        return $number . $suffix;
    }
}
