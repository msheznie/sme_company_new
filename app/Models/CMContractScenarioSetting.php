<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractScenarioSetting
 * @package App\Models
 * @version July 1, 2024, 8:01 pm +04
 *
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 */
class CMContractScenarioSetting extends Model
{
    use HasFactory;

    public $table = 'cm_contract_scenario_setting';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'scenario_assign_id',
        'value',
        'scenario_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'scenario_assign_id' => 'integer',
        'value' => 'integer',
        'scenario_type' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [ ];

    public function scenarioAssign()
    {
        return $this->belongsTo(CMContractScenarioAssign::class, 'scenario_assign_id', 'id');
    }

    public static function getValue($id, $scenarioType)
    {
        $record = self::where('scenario_assign_id', $id)
            ->where('scenario_type', $scenarioType)
            ->first();

        if ($record)
        {
            return $record->value;
        }
        return 0;
    }

}
