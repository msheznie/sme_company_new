<?php

namespace App\Utilities;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\SystemConfigurationAttributes;
use Illuminate\Support\Facades\Log;

class EmailUtils
{
    public static function getEmailSubject($documentID)
    {
        $emailSubject = '';
        switch ($documentID)
        {
            case 123:
                $emailSubject = 'Contract Approval';
                break;
            default:
                break;
        }
        return $emailSubject;
    }
    public static function getEmailBody($documentID, $masterData, $url=null)
    {
        $emailBody = '';
        $footer = self::getEmailFooter();
        if($documentID === 123)
        {
            $contractType = $masterData['contractTypes']['cm_type_name'] ?? "-";
            $contractUser = $masterData['createdUser']['empName'] ?? "-";
            $partyA = $masterData['contractTypes']['partyA']['cmParty_name'] ?? "-";
            $partyB = $masterData['contractTypes']['partyB']['cmParty_name'] ?? "-";
            $emailBody = '<p>A new contract has been created in the contract management and requires your
                   '. General::ordinalSuffix($masterData->rollLevelOrder) . ' level of approval. Please review the
                    contract details provided below and approve.
                   </p> <p><span style="text-decoration: underline;">Contract Details</span></p>
                   <p>
                    <span style="margin-bottom: 10px;"><b>Contract Title: </b>'. $masterData->title .'</span><br>
                    <span style="margin-bottom: 10px;"><b>Contract ID: </b>'. $masterData->contractCode .' </span><br>
                    <span style="margin-bottom: 10px;"><b>Contract Type: </b>'. $contractType .' </span><br>
                    <span style="margin-bottom: 10px;"><b>Created By: </b>'. $contractUser .' </span><br>
                    <span style="margin-bottom: 10px;"><b>Parties Invlolved: </b></span> <br>
                    <span style="margin-bottom: 10px;"><b>Party A: </b>'. $partyA .' </span><br>
                    <span style="margin-bottom: 10px;"><b>Party B: </b>'. $partyB .' </span>
                   </p>
                   <p> Please click the link below to review the full contract document and submit your approval or
                     comments. <br> <a href="' . self::getRedirectUrl($url) . '">Click here to approve</a> </p>
                     <p>Thank you for your prompt attention to this matter.</p>';
        }

        return $emailBody . $footer;
    }
    public static function emailAddressFormat($email)
    {

        if ($email)
        {
            $email = str_replace(" ", "", $email);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                Log::channel('email_log')->info(' Email not valid : ' . $email);
                $email = '';
            }
        }
        return $email;
    }
    public static function getRedirectUrl($url)
    {
        $redirectUrl =  env("DOMAIN_URL");

        if (env('IS_MULTI_TENANCY'))
        {
            $path = parse_url($url, PHP_URL_PATH);
            $host = explode('/', trim($path, '/'));

            $subDomain = $host[0] ?? null;

            if (!$subDomain)
            {
                throw new CommonException($subDomain . "Not found");
            }
            $redirectUrl = str_replace('*', $subDomain, $redirectUrl);
        }
        return $redirectUrl.'/approval/contracts';
    }
    public static function getEmailFooter()
    {
        return "<font size='1.5'><i><p><br><br><br>SAVE PAPER - THINK BEFORE YOU PRINT!
        <br>This is an auto generated email. Please do not reply to this email because we are not
         monitoring this inbox.</font>";
    }
    public static function getEmailConfiguration($slug='', $defaultValue = 'GEARS')
    {
        $emailConfiguration = SystemConfigurationAttributes::select('id', 'systemConfigurationId', 'name', 'slug')
            ->where('slug', $slug)
            ->whereHas('systemConfigurationDetail')
            ->with([
                'systemConfigurationDetail' => function ($q)
                {
                    $q->select('id', 'attributeId', 'value');
                }
            ])->first();
        if(!$emailConfiguration)
        {
            return $defaultValue;
        }
        return $emailConfiguration['systemConfigurationDetail']['value'];
    }
}
