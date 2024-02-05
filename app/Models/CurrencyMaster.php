<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CurrencyMaster
 * @package App\Models
 * @version April 26, 2022, 12:11 pm +04
 *
 * @property string $currency_name
 * @property string $currency_code
 * @property string $decimal_places
 * @property integer $created_by
 * @property integer $updated_by
 */
class CurrencyMaster extends Model
{
 

    use HasFactory;

    public $table = 'currency_master';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
 
    public $fillable = [
        'currency_name',
        'currency_code',
        'decimal_places',
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
        'currency_name' => 'string',
        'currency_code' => 'string',
        'decimal_places' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'currency_name' => 'required|string|max:255',
        'currency_code' => 'required|string|max:255',
        'decimal_places' => 'nullable|string|max:255',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
