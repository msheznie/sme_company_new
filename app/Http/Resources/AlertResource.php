<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'alertID' => $this->alertID,
            'companySystemID' => $this->companySystemID,
            'companyID' => $this->companyID,
            'empSystemID' => $this->empSystemID,
            'empID' => $this->empID,
            'docSystemID' => $this->docSystemID,
            'docID' => $this->docID,
            'docApprovedYN' => $this->docApprovedYN,
            'docSystemCode' => $this->docSystemCode,
            'docCode' => $this->docCode,
            'alertMessage' => $this->alertMessage,
            'alertDateTime' => $this->alertDateTime,
            'alertViewedYN' => $this->alertViewedYN,
            'alertViewedDateTime' => $this->alertViewedDateTime,
            'empName' => $this->empName,
            'empEmail' => $this->empEmail,
            'ccEmailID' => $this->ccEmailID,
            'emailAlertMessage' => $this->emailAlertMessage,
            'isEmailSend' => $this->isEmailSend,
            'attachmentFileName' => $this->attachmentFileName,
            'timeStamp' => $this->timeStamp
        ];
    }
}
