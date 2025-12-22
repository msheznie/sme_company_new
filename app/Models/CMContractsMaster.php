<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractsMaster
 * @package App\Models
 * @version February 22, 2024, 2:38 pm +04
 *
 * @property string $cmMaster_description
 * @property boolean $ctm_active
 * @property string|\Carbon\Carbon $timestamp
 */
class CMContractsMaster extends Model
{
    use HasFactory;

    public $table = 'cm_contracts_master';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'cmMaster_description',
        'ctm_active',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cmMaster_id' => 'integer',
        'cmMaster_description' => 'string',
        'ctm_active' => 'boolean',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cmMaster_description' => 'nullable|string|max:200',
        'ctm_active' => 'required|boolean',
        'timestamp' => 'nullable'
    ];

    
}
