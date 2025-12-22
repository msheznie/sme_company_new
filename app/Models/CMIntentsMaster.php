<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMIntentsMaster
 * @package App\Models
 * @version February 22, 2024, 2:33 pm +04
 *
 * @property string $cmIntent_detail
 * @property boolean $cim_active
 * @property string|\Carbon\Carbon $timestamp
 */
class CMIntentsMaster extends Model
{
    use HasFactory;

    public $table = 'cm_intents_master';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'cmIntent_detail',
        'cim_active',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cmIntent_id' => 'integer',
        'cmIntent_detail' => 'string',
        'cim_active' => 'boolean',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cmIntent_detail' => 'nullable|string|max:200',
        'cim_active' => 'required|boolean',
        'timestamp' => 'nullable'
    ];

    
}
