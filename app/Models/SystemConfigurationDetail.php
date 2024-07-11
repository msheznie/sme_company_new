<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SystemConfigurationDetail
 * @package App\Models
 * @version July 8, 2024, 4:06 pm +04
 *
 * @property integer $attributeId
 * @property string $value
 */
class SystemConfigurationDetail extends Model
{

    use HasFactory;

    public $table = 'system_configuration_detail';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'attributeId',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'attributeId' => 'integer',
        'value' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
