<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class WebEmployeeProfile
 * @package App\Models
 * @version February 14, 2024, 3:25 pm +04
 *
 * @property integer $employeeSystemID
 * @property string $empID
 * @property string $profileImage
 * @property string|\Carbon\Carbon $modifiedDate
 * @property string|\Carbon\Carbon $timestamp
 */
class WebEmployeeProfile extends Model
{

    public $table = 'web_employee_profile';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $primaryKey = 'empPorfileID';


    public $fillable = [
        'employeeSystemID',
        'empID',
        'profileImage',
        'modifiedDate',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empPorfileID' => 'integer',
        'employeeSystemID' => 'integer',
        'empID' => 'string',
        'profileImage' => 'string',
        'modifiedDate' => 'datetime',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'employeeSystemID' => 'nullable|integer',
        'empID' => 'nullable|string|max:20',
        'profileImage' => 'nullable|string|max:300',
        'modifiedDate' => 'nullable',
        'timestamp' => 'nullable'
    ];


}
