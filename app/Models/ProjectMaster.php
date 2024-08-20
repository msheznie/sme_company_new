<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProjectMaster
 * @package App\Models
 * @version August 14, 2024, 10:25 am +04
 *
 * @property string $projectCode
 * @property string $description
 * @property string $companyID
 * @property integer $companySystemID
 * @property integer $serviceLineSystemID
 * @property string $serviceLineCode
 * @property integer $projectCurrencyID
 * @property number $estimatedAmount
 * @property string $start_date
 * @property string $end_date
 * @property integer $createdUserGroup
 * @property integer $createdPCID
 * @property integer $createdUserID
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string $createdUserName
 * @property integer $modifiedPCID
 * @property integer $modifiedUserID
 * @property string|\Carbon\Carbon $modifiedDateTime
 * @property string $modifiedUserName
 * @property string|\Carbon\Carbon $timestamp
 */
class ProjectMaster extends Model
{
    public $table = 'erp_projectmaster';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timestamp';

    public $fillable = [
        'projectCode',
        'description',
        'companyID',
        'companySystemID',
        'serviceLineSystemID',
        'serviceLineCode',
        'projectCurrencyID',
        'estimatedAmount',
        'start_date',
        'end_date',
        'createdUserGroup',
        'createdPCID',
        'createdUserID',
        'createdDateTime',
        'createdUserName',
        'modifiedPCID',
        'modifiedUserID',
        'modifiedDateTime',
        'modifiedUserName',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'projectCode' => 'string',
        'description' => 'string',
        'companyID' => 'string',
        'companySystemID' => 'integer',
        'serviceLineSystemID' => 'integer',
        'serviceLineCode' => 'string',
        'projectCurrencyID' => 'integer',
        'estimatedAmount' => 'float',
        'start_date' => 'date',
        'end_date' => 'date',
        'createdUserGroup' => 'integer',
        'createdPCID' => 'integer',
        'createdUserID' => 'integer',
        'createdDateTime' => 'datetime',
        'createdUserName' => 'string',
        'modifiedPCID' => 'integer',
        'modifiedUserID' => 'integer',
        'modifiedDateTime' => 'datetime',
        'modifiedUserName' => 'string',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
