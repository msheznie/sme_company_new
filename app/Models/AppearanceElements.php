<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AppearanceElements
 * @package App\Models
 * @version July 8, 2024, 3:33 pm +04
 *
 * @property string $elementName
 */
class AppearanceElements extends Model
{
    use HasFactory;

    public $table = 'appearance_elements';

    public $fillable = [
        'elementName'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'elementName' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
