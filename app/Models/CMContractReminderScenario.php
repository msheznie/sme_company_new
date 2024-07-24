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
class CMContractReminderScenario extends Model
{
    //use SoftDeletes;

    use HasFactory;

    public $table = 'cm_contract_reminder_scenario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'uuid',
        'title',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'title' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uuid' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function showReminders()
    {
      return self::select('id', 'title')
          ->get();
    }

}
