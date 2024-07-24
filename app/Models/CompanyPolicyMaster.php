<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CompanyPolicyMaster
 * @package App\Models
 * @version July 2, 2024, 10:35 am +04
 *
 * @property integer $companyPolicyCategoryID
 * @property integer $companySystemID
 * @property string $companyID
 * @property string $documentID
 * @property integer $isYesNO
 * @property integer $policyValue
 * @property string $createdByUserID
 * @property string $createdByUserName
 * @property string $createdByPCID
 * @property string $modifiedByUserID
 * @property string|\Carbon\Carbon $createdDateTime
 * @property string|\Carbon\Carbon $timestamp
 */
class CompanyPolicyMaster extends Model
{

    use HasFactory;

    public $table = 'erp_companypolicymaster';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timestamp';
    protected $primaryKey = 'companyPolicyMasterAutoID';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'companyPolicyCategoryID',
        'companySystemID',
        'companyID',
        'documentID',
        'isYesNO',
        'policyValue',
        'createdByUserID',
        'createdByUserName',
        'createdByPCID',
        'modifiedByUserID',
        'createdDateTime',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'companyPolicyMasterAutoID' => 'integer',
        'companyPolicyCategoryID' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'documentID' => 'string',
        'isYesNO' => 'integer',
        'policyValue' => 'integer',
        'createdByUserID' => 'string',
        'createdByUserName' => 'string',
        'createdByPCID' => 'string',
        'modifiedByUserID' => 'string',
        'createdDateTime' => 'datetime',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static function checkActiveCompanyPolicy($companySystemID, $policyCatID)
    {
        return CompanyPolicyMaster::where('companySystemID', $companySystemID)
            ->where('companyPolicyCategoryID', $policyCatID)
            ->where('isYesNO', 1)
            ->exists();
    }

}
