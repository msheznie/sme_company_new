<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractReminderScenario
 * @package App\Models
 * @version July 1, 2024, 8:01 pm +04
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 */
class CMContractDropValue extends Model
{
    //use SoftDeletes;

    use HasFactory;

    public $table = 'cm_contract_drop_value';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'scenario_type_id',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'scenario_type_id' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [ ];

    public static function showRemindersAfterTheScenarioEvery()
    {
      return self::select('id', 'description')
          ->where('scenario_type_id', 2)
          ->get();

    }

}
