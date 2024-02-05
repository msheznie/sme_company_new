<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Navigation
 * @package App\Models
 * @version October 21, 2021, 8:41 am UTC
 *
 * @property integer $parent_id
 * @property string $name
 * @property string $path
 * @property string $icon
 * @property integer $order_index
 * @property integer $has_children
 * @property integer $status
 * @property integer $created_by
 * @property integer $updated_by
 */
class Navigation extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'navigations';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'parent_id',
        'name',
        'path',
        'icon',
        'order_index',
        'has_children',
        'status',
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
        'parent_id' => 'integer',
        'name' => 'string',
        'path' => 'string',
        'icon' => 'string',
        'order_index' => 'integer',
        'has_children' => 'integer',
        'status' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'parent_id' => 'nullable|integer',
        'name' => 'required|string|max:255',
        'path' => 'nullable|string|max:255',
        'icon' => 'nullable|string|max:255',
        'order_index' => 'required|integer',
        'has_children' => 'required|integer',
        'status' => 'required|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class)->select(['id', 'name', 'icon', 'path', 'order_index', 'has_children', 'status']);
    }

    /*public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->select(['id', 'name', 'icon', 'path', 'order_index', 'has_children', 'status']);
    }*/

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function navigationHasRole()
    {
        return $this->hasOne(NavigationRole::class,  'navigation_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

}
