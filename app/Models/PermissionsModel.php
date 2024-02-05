<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PermissionsModel
 * @package App\Models
 * @version April 21, 2022, 11:26 am +04
 *
 * @property \App\Models\ModelHasPermission $modelHasPermission
 * @property \Illuminate\Database\Eloquent\Collection $roles
 * @property string $name
 * @property string $description
 * @property integer $navigation_id
 * @property string $guard_name
 */
class PermissionsModel extends Model
{
     
    use HasFactory;

    public $table = 'permissions';


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description',
        'navigation_id',
        'guard_name',
        'provider'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'navigation_id' => 'integer',
        'guard_name' => 'string',
        'provider' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'navigation_id' => 'required|integer',
        'guard_name' => 'required|string|max:255',
        'provider' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/

}
