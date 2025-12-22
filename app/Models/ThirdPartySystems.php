<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ThirdPartySystems
 * @package App\Models
 * @version July 24, 2024, 12:07 pm +04
 *
 * @property string $description
 * @property boolean $status
 * @property integer $moduleID
 * @property integer $isDefault
 * @property string $comment
 */
class ThirdPartySystems extends Model
{
    use HasFactory;

    public $table = 'third_party_systems';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'description',
        'status',
        'moduleID',
        'isDefault',
        'comment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'description' => 'string',
        'status' => 'boolean',
        'moduleID' => 'integer',
        'isDefault' => 'integer',
        'comment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'description' => 'required|string|max:255',
        'status' => 'required|boolean',
        'moduleID' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'isDefault' => 'nullable|integer',
        'comment' => 'nullable|string|max:255'
    ];

    public static function getData($key)
    {
      return  self::where('description', $key)->first();
    }
}
