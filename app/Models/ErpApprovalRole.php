<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpApprovalRole
 * @package App\Models
 * @version May 22, 2024, 1:04 pm +04
 *
 * @property string $rollDescription
 * @property integer $documentSystemID
 * @property string $documentID
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $departmentSystemID
 * @property string $departmentID
 * @property integer $serviceLineSystemID
 * @property string $serviceLineID
 * @property integer $rollLevel
 * @property integer $approvalLevelID
 * @property integer $approvalGroupID
 * @property string|\Carbon\Carbon $timeStamp
 */
class ErpApprovalRole extends Model
{
    use HasFactory;

    public $table = 'erp_approvalrollmaster';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';


    public $fillable = [
        'rollDescription',
        'documentSystemID',
        'documentID',
        'companySystemID',
        'companyID',
        'departmentSystemID',
        'departmentID',
        'serviceLineSystemID',
        'serviceLineID',
        'rollLevel',
        'approvalLevelID',
        'approvalGroupID',
        'timeStamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'rollMasterID' => 'integer',
        'rollDescription' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'departmentSystemID' => 'integer',
        'departmentID' => 'string',
        'serviceLineSystemID' => 'integer',
        'serviceLineID' => 'string',
        'rollLevel' => 'integer',
        'approvalLevelID' => 'integer',
        'approvalGroupID' => 'integer',
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
