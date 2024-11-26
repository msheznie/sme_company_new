<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CodeConfigurations
 * @package App\Models
 * @version November 11, 2024, 4:47 pm +04
 *
 * @property string $uuid
 * @property boolean $serialization_based_on
 * @property string $code_pattern
 * @property string $company_id
 * @property integer $company_system_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class CodeConfigurations extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'cm_code_configuration';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'created_by', 'updated_by'];


    public $fillable = [
        'uuid',
        'serialization_based_on',
        'code_pattern',
        'company_id',
        'company_system_id',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'serialization_based_on' => 'integer',
        'code_pattern' => 'string',
        'company_id' => 'string',
        'company_system_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'created_by', 'employeeSystemID');
    }
    public static function getAllCodeConfiguration($companySystemID)
    {
        return CodeConfigurations::select('uuid', 'serialization_based_on', 'code_pattern', 'created_by',
            'created_at')
            ->with([
                'employee' => function ($q)
                {
                    $q->select('employeeSystemID', 'empName');
                }
            ])
        ->where('company_system_id', $companySystemID);
    }
    public static function checkUniqueUuid($uuid)
    {
        return self::where('uuid', $uuid)->exists();
    }
}
