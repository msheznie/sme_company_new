<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Tenant
 * @package App\Models
 * @version February 13, 2024, 5:57 pm +04
 *
 * @property string $name
 * @property string $sub_domain
 * @property string $erp_domain
 * @property integer $azureLogin
 * @property string $database
 * @property string $api_key
 * @property boolean $is_active
 */
class Tenant extends Model
{

    public $table = 'tenant';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name',
        'sub_domain',
        'erp_domain',
        'azureLogin',
        'database',
        'api_key',
        'is_active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'sub_domain' => 'string',
        'erp_domain' => 'string',
        'azureLogin' => 'integer',
        'database' => 'string',
        'api_key' => 'string',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'sub_domain' => 'nullable|string|max:255',
        'erp_domain' => 'nullable|string|max:255',
        'azureLogin' => 'nullable|integer',
        'database' => 'nullable|string|max:255',
        'api_key' => 'nullable|string|max:255',
        'is_active' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
