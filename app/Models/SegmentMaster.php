<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SegmentMaster
 * @package App\Models
 * @version August 14, 2024, 10:28 am +04
 *
 * @property string $ServiceLineCode
 * @property string $serviceLineMasterCode
 * @property integer $companySystemID
 * @property string $companyID
 * @property string $ServiceLineDes
 * @property integer $locationID
 * @property integer $isActive
 * @property integer $isPublic
 * @property integer $isServiceLine
 * @property integer $isDepartment
 * @property integer $isMaster
 * @property string $consoleCode
 * @property string $consoleDescription
 * @property string $createdUserGroup
 * @property string $createdPcID
 * @property string $createdUserID
 * @property string $modifiedPc
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string|\Carbon\Carbon $timeStamp
 * @property boolean $isFinalLevel
 * @property integer $masterID
 * @property boolean $isDeleted
 * @property integer $createdFrom
 */
class SegmentMaster extends Model
{

    public $table = 'serviceline';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timeStamp';
    protected $primaryKey  = 'serviceLineSystemID';


    public $fillable = [
        'ServiceLineCode',
        'serviceLineMasterCode',
        'companySystemID',
        'companyID',
        'ServiceLineDes',
        'locationID',
        'isActive',
        'isPublic',
        'isServiceLine',
        'isDepartment',
        'isMaster',
        'consoleCode',
        'consoleDescription',
        'createdUserGroup',
        'createdPcID',
        'createdUserID',
        'modifiedPc',
        'modifiedUser',
        'createdDateTime',
        'timeStamp',
        'isFinalLevel',
        'masterID',
        'isDeleted',
        'createdFrom'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'serviceLineSystemID' => 'integer',
        'ServiceLineCode' => 'string',
        'serviceLineMasterCode' => 'string',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'ServiceLineDes' => 'string',
        'locationID' => 'integer',
        'isActive' => 'integer',
        'isPublic' => 'integer',
        'isServiceLine' => 'integer',
        'isDepartment' => 'integer',
        'isMaster' => 'integer',
        'consoleCode' => 'string',
        'consoleDescription' => 'string',
        'createdUserGroup' => 'string',
        'createdPcID' => 'string',
        'createdUserID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUser' => 'string',
        'createdDateTime' => 'datetime',
        'timeStamp' => 'datetime',
        'isFinalLevel' => 'boolean',
        'masterID' => 'integer',
        'isDeleted' => 'boolean',
        'createdFrom' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
