<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DocumentMaster
 * @package App\Models
 * @version May 6, 2024, 4:50 pm +04
 *
 * @property string $uuid
 * @property string $documentType
 * @property string $description
 * @property boolean $status
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class DocumentMaster extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_document_master';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'documentType',
        'description',
        'status',
        'companySystemID',
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
        'uuid' => 'string',
        'documentType' => 'string',
        'description' => 'string',
        'status' => 'boolean',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uuid' => 'required|string|max:255',
        'documentType' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'status' => 'nullable|boolean',
        'companySystemID' => 'required|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
