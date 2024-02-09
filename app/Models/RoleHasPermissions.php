<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class RoleHasPermissions
 * @package App\Models
 * @version October 22, 2021, 11:20 am UTC
 *
 * @property \App\Models\Permission $permission
 * @property \App\Models\Role $role
 * @property integer $role_id
 */
class RoleHasPermissions extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $table = 'role_has_permissions';

    const CREATED_AT = '';
    const UPDATED_AT = '';

    public $fillable = [
        'role_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'permission_id' => 'integer',
        'role_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'role_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function permission()
    {
        return $this->belongsTo(\App\Models\Permission::class, 'permission_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
