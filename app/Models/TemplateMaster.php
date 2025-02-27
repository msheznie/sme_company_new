<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TemplateMaster
 * @package App\Models
 * @version February 21, 2025, 2:27 pm +04
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $contract_id
 * @property string $content
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class TemplateMaster extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_template_master';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $hidden = ['id', 'contract_id'];

    public $fillable = [
        'id',
        'uuid',
        'contract_id',
        'content',
        'company_id',
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
        'contract_id' => 'integer',
        'content' => 'string',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


    public static function checkUuidExists($uuid)
    {
        return self::where('uuid', $uuid)->exists();
    }

    public static function checkTemplateExists($contractID, $companySystemID)
    {
        return self::where('contract_id', $contractID)->where('company_id', $companySystemID)->exists();
    }

}
