<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractSettingMaster
 * @package App\Models
 * @version March 29, 2024, 3:57 pm +04
 *
 * @property integer $contractId
 * @property integer $contractTypeDetailId
 * @property boolean $isActive
 */
class ContractSettingMaster extends Model
{

    use HasFactory;

    public $table = 'cm_contract_setting_master';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'uuid',
        'contractId',
        'contractTypeSectionId',
        'isActive'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'contractId' => 'integer',
        'contractTypeSectionId' => 'integer',
        'isActive' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'contractId' => 'required|integer',
        'contractTypeSectionId' => 'required|integer',
        'isActive' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function contractTypeSection()
    {
        return $this->belongsTo(CMContractTypeSections::class, 'contractTypeSectionId', 'ct_sectionId');
    }

    public function contractSettingDetails()
    {
        return $this->hasMany(ContractSettingDetail::class, 'settingMasterId', 'id');
    }

    public static function getSettingMaster($cloningContractId)
    {
        return self::where('contractId', $cloningContractId)
            ->get();
    }

    public static function getContractSettings($cloningContractId)
    {
        return self::select('id','contractId', 'contractTypeSectionId', 'isActive')
            ->where('contractId', $cloningContractId)
            ->with(['contractTypeSection' => function ($q)
            {
                $q->select('ct_sectionId', 'cmSection_id', 'is_enabled')
                    ->where('is_enabled', 1);
            }])
            ->with(['contractSettingDetails' => function ($q)
            {
                $q->select('id', 'contractId', 'settingMasterId','sectionDetailId','isActive')
                    ->where('isActive', 1);
            }])
            ->with(['contractSettingDetails' => function ($q)
            {
                $q->select('id', 'contractId', 'settingMasterId','sectionDetailId','isActive')
                    ->where('isActive', 1);
            }])
            ->where('isActive', true)
            ->get();
    }


}
