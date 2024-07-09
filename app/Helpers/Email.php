<?php

namespace App\Helpers;

use App\Exceptions\CommonException;
use App\Models\Alert;
use App\Models\AppearanceSettings;
use App\Models\CompanyPolicyMaster;
use App\Models\ContractUsers;
use App\Models\Employees;
use App\Mail\EmailForQueuing;
use App\Models\SupplierMaster;
use App\Utilities\EmailUtils;
use Illuminate\Support\Facades\Mail;

class Email
{
    public static function sendBulkEmail($data)
    {
        $color = AppearanceSettings::getAppearanceSettings(1);
        $text = AppearanceSettings::getAppearanceSettings(7);
        $fromName = EmailUtils::getEmailConfiguration('mail_name','GEARS');
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
                        $body, $dataMail['attachmentFileName']), $dataMail['attachmentList'], $color, $text, $fromName);
                }
            } else
            {
                Alert::create($dataMail);
            }
        }
        return true;
    }

    public static function sendBulkEmailSupplier($data)
    {
        foreach ($data as $dataMail)
        {
            $contractUserResult = ContractUsers::getContractUserIdById($dataMail['empSystemID']);
            $supplier = SupplierMaster::getSupplierBySupplierCodeSystem($contractUserResult->contractUserId);
            if(!empty($supplier))
            {
                $dataMail['empID'] = $supplier['supplierCodeSystem'];
                $dataMail['empName'] = $supplier['supplierName'];
                $dataMail['empEmail'] = $supplier['supEmail'];
            } else
            {
                throw new CommonException('Supplier Not Found');
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
                }
            } else
            {
               Alert::create($dataMail);
            }
        }
        return true;
    }
}
