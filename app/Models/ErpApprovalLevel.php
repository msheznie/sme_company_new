<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpApprovalLevel
 * @package App\Models
 * @version May 22, 2024, 1:01 pm +04
 *
 * @property integer $companySystemID
 * @property string $companyID
 * @property integer $departmentSystemID
 * @property string $departmentID
 * @property integer $serviceLineWise
 * @property integer $serviceLineSystemID
 * @property string $serviceLineCode
 * @property integer $documentSystemID
 * @property string $documentID
 * @property string $levelDescription
 * @property integer $noOfLevels
 * @property integer $valueWise
 * @property number $valueFrom
 * @property number $valueTo
 * @property integer $isCategoryWiseApproval
 * @property integer $categoryID
 * @property integer $isActive
 * @property boolean $is_deleted
 * @property string|\Carbon\Carbon $timeStamp
 */
class ErpApprovalLevel extends Model
{

    use HasFactory;

    public $table = 'erp_approvallevel';

    const CREATED_AT = 'timeStamp';
    const UPDATED_AT = 'timeStamp';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'companySystemID',
        'companyID',
        'departmentSystemID',
        'departmentID',
        'serviceLineWise',
        'serviceLineSystemID',
        'serviceLineCode',
        'documentSystemID',
        'documentID',
        'levelDescription',
        'noOfLevels',
        'valueWise',
        'valueFrom',
        'valueTo',
        'isCategoryWiseApproval',
        'categoryID',
        'isActive',
        'is_deleted',
        'timeStamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function approvalRole(){
        return $this->hasMany(ErpApprovalRole::class,'approvalLevelID','approvalLevelID');
    }

}
