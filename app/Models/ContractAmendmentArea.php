<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractAmendmentArea
 * @package App\Models
 * @version July 5, 2024, 9:58 am +04
 *
 * @property integer $contract_history_id
 * @property integer $section_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractAmendmentArea extends Model
{
    use HasFactory;

    public $table = 'cm_contract_amendment_areas';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'contract_history_id',
        'contract_id',
        'section_id',
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
        'contract_history_id' => 'integer',
        'contract_id' => 'integer',
        'section_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public static function getContractAmendAreas($contractId,$historyId)
    {
        return self::select('section_id')
        ->where('contract_id', $contractId)
        ->where('contract_history_id', $historyId)
        ->pluck('section_id')
        ->toArray();
    }


}
