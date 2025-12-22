<?php

namespace App\Helpers;

use App\Exceptions\CommonException;
use App\Models\Alert;
use App\Models\AppearanceSettings;
use App\Models\CompanyPolicyMaster;
use App\Models\ContractUsers;
use App\Models\CustomerMaster;
use App\Models\Employees;
use App\Mail\EmailForQueuing;
use App\Models\SupplierMaster;
use App\Utilities\EmailUtils;
use Illuminate\Support\Facades\Log;
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
            $dataMail['attachmentList'] = $dataMail['attachmentList'] ?? [];
            if(!empty($employee))
            {
                $dataMail['empID'] = $employee['empID'];
                $dataMail['empName'] = $employee['empName'];
                $dataMail['empEmail'] = $employee['empEmail'];
            } else
            {
                Log::info('Email verification for the Employee ID ' . $dataMail['empSystemID'] . ' is pending!');
                continue;
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
                        $body, $dataMail['attachmentFileName'], $dataMail['attachmentList'], $color, $text, $fromName));
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
            if($contractUserResult)
            {
                if ($contractUserResult->contractUserType == 1)
                {
                    $supplier = SupplierMaster::getSupplierBySupplierCodeSystem($contractUserResult->contractUserId);
                    if (empty($supplier))
                    {
                        Log::info('Email verification for the Supplier ID ' . $dataMail['empSystemID'] . ' is pending!');
                        continue;
                    }
                    $dataMail = array_merge($dataMail, [
                        'empID' => $supplier['supplierCodeSystem'],
                        'empName' => $supplier['supplierName'],
                        'empEmail' => $supplier['supEmail'],
                    ]);
                }
                elseif ($contractUserResult->contractUserType == 3)
                {
                    $employee = Employees::getEmployee($contractUserResult->contractUserId);
                    if (empty($employee))
                    {
                        Log::info('Email verification for the Employee ID ' . $dataMail['empSystemID'] . ' is pending!');
                        continue;
                    }
                    $dataMail = array_merge($dataMail, [
                        'empID' => $employee['empID'],
                        'empName' => $employee['empName'],
                        'empEmail' => $employee['empEmail'],
                    ]);
                }
                else
                {
                    throw new CommonException('No Record Found');
                }
            }
            else
            {
                Log::info($dataMail['error_tag'] . ' ' . $dataMail['error_msg']);
                continue;
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
