<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SystemConfigurationAttributes
 * @package App\Models
 * @version July 8, 2024, 4:05 pm +04
 *
 * @property integer $systemConfigurationId
 * @property string $name
 * @property string $slug
 */
class SystemConfigurationAttributes extends Model
{
    use HasFactory;

    public $table = 'system_configuration_attributes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'systemConfigurationId',
        'name',
        'slug'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'systemConfigurationId' => 'integer',
        'name' => 'string',
        'slug' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function systemConfigurationDetail()
    {
        return $this->hasOne('App\Models\SystemConfigurationDetail', 'attributeId', 'id');
    }
}
