<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractHistory
 * @package App\Models
 * @version May 29, 2024, 2:49 pm +04
 *
 * @property integer $category
 * @property string $date
 * @property string $end_date
 * @property integer $contract_id
 * @property integer $company_id
 * @property string $contract_title
 * @property string $created_date
 * @property integer $created_by
 */
class ContractHistory extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_contract_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'category',
        'date',
        'end_date',
        'uuid',
        'contract_id',
        'company_id',
        'created_date',
        'created_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category' => 'integer',
        'uuid' => 'string',
        'date' => 'date',
        'end_date' => 'date',
        'contract_id' => 'integer',
        'company_id' => 'integer',
        'created_date' => 'date',
        'created_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category' => 'required|integer',
        'date' => 'required',
        'end_date' => 'required',
        'contract_id' => 'required|integer',
        'company_id' => 'required|integer',
        'contract_title' => 'required|string|max:255',
        'created_date' => 'required',
        'created_by' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
