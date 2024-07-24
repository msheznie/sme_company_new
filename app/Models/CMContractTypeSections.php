<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

/**
 * Class CMContractTypeSections
 * @package App\Models
 * @version February 22, 2024, 2:43 pm +04
 *
 * @property integer $contract_typeId
 * @property integer $cmSection_id
 * @property boolean $is_enabled
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class CMContractTypeSections extends Model
{
    use HasFactory;

    public $table = 'cm_contract_type_sections';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'uuid',
        'contract_typeId',
        'cmSection_id',
        'is_enabled',
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
        'uuid' => 'string',
        'ct_sectionId' => 'integer',
        'contract_typeId' => 'integer',
        'cmSection_id' => 'integer',
        'is_enabled' => 'boolean',
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
        'uuid' => 'required|unique',
        'contract_typeId' => 'nullable|integer',
        'cmSection_id' => 'nullable|integer',
        'is_enabled' => 'required|boolean',
        'companySystemID' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_by' => 'nullable|integer',
        'updated_at' => 'nullable'
    ];

    public function contractSectionWithTypes()
    {
        return $this->belongsTo(CMContractSectionsMaster::class, 'cmSection_id', 'cmSection_id');
    }

    public function sectionDetail()
    {
        return $this->belongsTo(ContractSectionDetail::class, 'cmSection_id', 'sectionMasterId');
    }

    public static function getContractTypeSections($contractTypeId, $companySystemID)
    {
        return CMContractTypeSections::where('contract_typeId', $contractTypeId)
            ->where('companySystemID', $companySystemID)
            ->where('is_enabled', 1)
            ->get();
    }
}
