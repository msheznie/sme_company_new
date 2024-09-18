<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AppearanceSettings
 * @package App\Models
 * @version July 8, 2024, 3:29 pm +04
 *
 * @property integer $appearance_system_id
 * @property integer $appearance_element_id
 * @property string $value
 */
class AppearanceSettings extends Model
{

    use HasFactory;

    public $table = 'appearance_settings';

    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'appearance_system_id',
        'appearance_element_id',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'appearance_system_id' => 'integer',
        'appearance_element_id' => 'integer',
        'value' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function elements()
    {
        return $this->belongsTo('App\Models\AppearanceElements', 'appearance_element_id');
    }

    public static function getAppearanceSettings($appearanceElementId)
    {
        $settings = AppearanceSettings::where([
            'appearance_system_id' => 1,
            'appearance_element_id' => $appearanceElementId,
        ])->value('value');

        if ($settings !== null) {
            return $settings;
        }

        return $appearanceElementId == 1 ? '#C23C32' : 'GEARS';
    }

    public static function getBrandingData($appearanceSystemID)
    {
        return AppearanceSettings::select('appearance_system_id', 'appearance_element_id', 'value')
        ->with(['elements'])
            ->where('appearance_system_id', $appearanceSystemID)
            ->get();
    }
}
