<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FinanceDocuments
 * @package App\Models
 * @version August 8, 2024, 2:17 pm +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property boolean $document_type
 * @property integer $document_id
 * @property integer $document_system_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class FinanceDocuments extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_finance_documents';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'contract_id',
        'document_type',
        'document_id',
        'document_system_id',
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
        'document_type' => 'boolean',
        'document_id' => 'integer',
        'document_system_id' => 'integer',
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


}
