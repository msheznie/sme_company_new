<?php

namespace App\Helpers;

use App\Exceptions\CommonException;
use App\Models\Alert;
use App\Models\CompanyPolicyMaster;
use App\Models\Employees;
use App\Mail\EmailForQueuing;
use App\Utilities\EmailUtils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Email
{
    public static function sendBulkEmail($data)
    {
        foreach ($data as $dataMail)
        {
            $employee = Employees::getEmployee($dataMail['empSystemID']);
            if(!empty($employee))
            {
                $dataMail['empID'] = $employee['empID'];
                $dataMail['empName'] = $employee['empName'];
                $dataMail['empEmail'] = $employee['empEmail'];
            } else
            {
                throw new CommonException('Employee Not Found');
            }
            $body = '<p>Dear ' . $dataMail['empName'] . ', </p>' . $dataMail['emailAlertMessage'];
            $hasPolicy = CompanyPolicyMaster::checkActiveCompanyPolicy($dataMail['companySystemID'], 37);
            if($hasPolicy)
            {
                $dataMail['empEmail'] = EmailUtils::emailAddressFormat($dataMail['empEmail']);
                if ($dataMail['empEmail'])
                {
                    $dataMail['attachmentFileName'] = $dataMail['attachmentFileName'] ?? '';
                    Mail::to($dataMail['empEmail'])->send(new EmailForQueuing($dataMail['alertMessage'],
                        $body, $dataMail['attachmentFileName']));
                    Log::channel('email_log')->info('Email sent success fully to :' .
                        $dataMail['empEmail']);
                    Log::channel('email_log')->info('QUEUE_DRIVER : ' . env('QUEUE_DRIVER'));
                }
            } else
            {
                Alert::create($dataMail);
            }
        }
        return true;
    }
}
