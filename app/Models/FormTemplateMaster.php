<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FormTemplateMaster
 * @package App\Models
 * @version March 14, 2022, 1:56 pm +04
 *
 * @property integer $tenant_id
 * @property string $name
 * @property string $description
 * @property integer $status
 */
class FormTemplateMaster extends Model
{ 
    use HasFactory;

    public $table = 'form_template_master';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at'; 

    public $fillable = [
        'tenant_id',
        'name',
        'description',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tenant_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tenant_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'status' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
