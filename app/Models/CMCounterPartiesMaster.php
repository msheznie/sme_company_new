<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMCounterPartiesMaster
 * @package App\Models
 * @version February 22, 2024, 2:35 pm +04
 *
 * @property string $cmCounterParty_name
 * @property boolean $cpt_active
 * @property string|\Carbon\Carbon $timestamp
 */
class CMCounterPartiesMaster extends Model
{
    use HasFactory;

    public $table = 'cm_counter_parties_master';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'cmCounterParty_name',
        'cpt_active',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cmCounterParty_id' => 'integer',
        'cmCounterParty_name' => 'string',
        'cpt_active' => 'boolean',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cmCounterParty_name' => 'nullable|string|max:200',
        'cpt_active' => 'required|boolean',
        'timestamp' => 'nullable'
    ];

    
}
