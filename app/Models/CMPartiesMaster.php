<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMPartiesMaster
 * @package App\Models
 * @version February 22, 2024, 2:26 pm +04
 *
 * @property string $cmParty_name
 * @property boolean $cpm_active
 * @property string|\Carbon\Carbon $timestamp
 */
class CMPartiesMaster extends Model
{
    use HasFactory;

    public $table = 'cm_parties_master';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'cmParty_name',
        'cpm_active',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cmParty_id' => 'integer',
        'cmParty_name' => 'string',
        'cpm_active' => 'boolean',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cmParty_name' => 'nullable|string|max:200',
        'cpm_active' => 'required|boolean',
        'timestamp' => 'nullable'
    ];

    
}
