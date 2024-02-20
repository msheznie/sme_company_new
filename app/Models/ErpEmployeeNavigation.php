<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpEmployeeNavigation
 * @package App\Models
 * @version February 16, 2024, 5:55 pm +04
 *
 * @property integer $employeeSystemID
 * @property integer $userGroupID
 * @property integer $companyID
 * @property string|\Carbon\Carbon $timestamp
 */
class ErpEmployeeNavigation extends Model
{

    public $table = 'srp_erp_employeenavigation';

    const CREATED_AT = 'timestamp';
    const UPDATED_AT = 'timestamp';

    protected $primaryKey= 'id';


    public $fillable = [
        'employeeSystemID',
        'userGroupID',
        'companyID',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'employeeSystemID' => 'integer',
        'userGroupID' => 'integer',
        'companyID' => 'integer',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'employeeSystemID' => 'nullable|integer',
        'userGroupID' => 'nullable|integer',
        'companyID' => 'nullable|integer',
        'timestamp' => 'nullable'
    ];

    public function company(){
        return $this->belongsTo(Company::class,'companyID','companySystemID');
    }

    public function getCurrentUserCompanies($id){

        return $this->where('employeeSystemID',$id)
            ->with(['company'])
            ->get()
            ->map(function ($company){
               return [
                   'id' => $company->company->companySystemID,
                   'name' => $company->company->CompanyName,
                   'kyc_status' => 0,
                   'is_bid_tender' => 0,
               ];
            });
    }
}
