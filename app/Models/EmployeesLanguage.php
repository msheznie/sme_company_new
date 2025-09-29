<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmployeesLanguage
 * @package App\Models
 * @version September 1, 2025, 12:04 pm +04
 *
 * @property integer $employeeID
 * @property integer $languageID
 */
class EmployeesLanguage extends Model
{
    use HasFactory;

    public $table = 'employees_languages';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'employeeID',
        'languageID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'employeeID' => 'integer',
        'languageID' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'employeeID' => 'nullable|integer',
        'languageID' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function getUserLanguage($empId)
    {
        return self::select('languageID')
            ->with(['languages'])
            ->where('employeeID',$empId)
            ->first();
    }
    public function languages()
    {
        return $this->hasOne(ERPLanguageMaster::class,  'languageID', 'languageID');
    }
}
