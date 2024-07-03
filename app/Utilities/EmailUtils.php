<?php

namespace App\Utilities;

use App\Helpers\General;
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
    public static function getEmailBody($documentID, $masterData)
    {
        Log::info('gg: '.$masterData);
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
                    <span><b>Contract Title: </b></span>'. $masterData->title .' <br>
                    <span><b>Contract ID: </b></span>'. $masterData->contractCode .' <br>
                    <span><b>Contract Type: </b></span>'. $contractType .' <br>
                    <span><b>Created By: </b></span>'. $contractUser .' <br>
                    <span><b>Parties Invlolved: </b></span> <br>
                    <span><b>Party A: </b></span>'. $partyA .' <br>
                    <span><b>Party B: </b></span>'. $partyB .' <br>
                   </p>
                   <p> Please click the link below to review the full contract document and submit your approval or
                     comments. <br> <a href="' . self::getRedirectUrl() . '">Click here to approve</a> </p>
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
    public static function getRedirectUrl()
    {
        $redirectUrl = env("APP_URL");
        if($_SERVER["REMOTE_ADDR"]=="172.20.0.1")
        {
            $redirectUrl = $redirectUrl.':4200';
        }else
        {
            $redirectUrl = $_SERVER['HTTP_HOST'];
            $urlArray = explode('.', $redirectUrl);
            $subDomain = $urlArray[0];

            $tenantDomain = (isset(explode('-', $subDomain)[0])) ? explode('-', $subDomain)[0] : "";

            $search = '*';
            $redirectUrl = str_replace($search, $tenantDomain, $redirectUrl);
        }

        return $redirectUrl.'/approval/contracts';
    }
    public static function getEmailFooter()
    {
        return "<font size='1.5'><i><p><br><br><br>SAVE PAPER - THINK BEFORE YOU PRINT!
        <br>This is an auto generated email. Please do not reply to this email because we are not
         monitoring this inbox.</font>";
    }
}
