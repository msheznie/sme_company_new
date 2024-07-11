<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

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

    ];

    public static function getTenantList()
    {
        return Tenant::select('database', DB::raw('MAX(id) as id'), DB::raw('MAX(name) as name'))
            ->where('is_active', 1)
            ->groupBy('database')
            ->get();
    }

}
