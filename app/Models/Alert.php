<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Alert
 * @package App\Models
 * @version July 2, 2024, 11:17 am +04
 *
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $empSystemID
 * @property string $empID
 * @property integer $docSystemID
 * @property string $docID
 * @property integer $docApprovedYN
 * @property integer $docSystemCode
 * @property string $docCode
 * @property string $alertMessage
 * @property string|\Carbon\Carbon $alertDateTime
 * @property integer $alertViewedYN
 * @property string|\Carbon\Carbon $alertViewedDateTime
 * @property string $empName
 * @property string $empEmail
 * @property string $ccEmailID
 * @property string $emailAlertMessage
 * @property integer $isEmailSend
 * @property string $attachmentFileName
 * @property string|\Carbon\Carbon $timeStamp
 */
class Alert extends Model
{
    use HasFactory;

    public $table = 'erp_alert';

    const CREATED_AT = 'alertDateTime';
    const UPDATED_AT = 'timeStamp';

    protected $primaryKey = 'alertID';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'companySystemID',
        'companyID',
        'empSystemID',
        'empID',
        'docSystemID',
        'docID',
        'docApprovedYN',
        'docSystemCode',
        'docCode',
        'alertMessage',
        'alertDateTime',
        'alertViewedYN',
        'alertViewedDateTime',
        'empName',
        'empEmail',
        'ccEmailID',
        'emailAlertMessage',
        'isEmailSend',
        'attachmentFileName',
        'timeStamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'alertID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'empSystemID' => 'integer',
        'empID' => 'string',
        'docSystemID' => 'integer',
        'docID' => 'string',
        'docApprovedYN' => 'integer',
        'docSystemCode' => 'integer',
        'docCode' => 'string',
        'alertMessage' => 'string',
        'alertDateTime' => 'datetime',
        'alertViewedYN' => 'integer',
        'alertViewedDateTime' => 'datetime',
        'empName' => 'string',
        'empEmail' => 'string',
        'ccEmailID' => 'string',
        'emailAlertMessage' => 'string',
        'isEmailSend' => 'integer',
        'attachmentFileName' => 'string',
        'timeStamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
