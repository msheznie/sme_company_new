<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractSectionDetail
 * @package App\Models
 * @version March 29, 2024, 3:44 pm +04
 *
 * @property integer $sectionMasterId
 * @property string $description
 * @property string $inputType
 */
class ContractSectionDetail extends Model
{
    use HasFactory;

    public $table = 'cm_contract_section_detail';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'sectionMasterId',
        'description',
        'inputType'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'sectionMasterId' => 'integer',
        'description' => 'string',
        'inputType' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sectionMasterId' => 'required|integer',
        'description' => 'nullable|string|max:255',
        'inputType' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
