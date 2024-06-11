<?php

namespace App\Models;

use App\Exceptions\CommonException;
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

    public function approvalRole()
    {
        return $this->hasMany(ErpApprovalRole::class,'approvalLevelID','approvalLevelID');
    }
    public static function getApprovalLevel($approvalLevelID)
    {
        return ErpApprovalLevel::select('approvalLevelID', 'companySystemID', 'departmentSystemID', 'departmentID',
            'serviceLineWise', 'serviceLineSystemID', 'serviceLineCode', 'documentSystemID', 'documentID',
            'levelDescription', 'noOfLevels', 'valueWise', 'valueFrom', 'valueTo', 'isCategoryWiseApproval',
            'categoryID', 'isActive'
        )
            ->where('approvalLevelID', $approvalLevelID)
            ->first();
    }
    public static function approvalLevelValidation($params, $document, $policy)
    {
        $approvalLevel = ErpApprovalLevel::select('approvalLevelID')
            ->with([
                'approvalRole' => function ($q)
                {
                    $q->select('rollDescription', 'documentSystemID', 'documentID', 'companySystemID', 'companyID',
                        'departmentSystemID', 'departmentID', 'serviceLineSystemID', 'serviceLineID', 'rollLevel',
                        'approvalLevelID', 'approvalGroupID');
                }
            ])
            ->where('companySystemID', $params['company'])
            ->where('documentSystemID', $params['document'])
            ->where('departmentSystemID', $document['departmentSystemID'])
            ->where('isActive', -1);

        if($policy->isAmountApproval)
        {
            if(array_key_exists('amount', $params))
            {
                if ($params["amount"] >= 0)
                {
                    $amount = $params["amount"];
                    $approvalLevel->where(function ($query) use ($amount)
                    {
                        $query->where('valueFrom', '<=', $amount);
                        $query->where('valueTo', '>=', $amount);
                    });
                    $approvalLevel->where('valueWise', 1);
                } else
                {
                    throw new CommonException(trans('common.no_approval_level_for_this_document'));
                }
            } else
            {
                throw new CommonException(trans('common.amount_parameter_are_missing'));
            }
        }
        return $approvalLevel->first();
    }
}
