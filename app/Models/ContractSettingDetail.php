<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;
/**
 * Class ContractSettingDetail
 * @package App\Models
 * @version March 30, 2024, 12:58 am +04
 *
 * @property integer $settingMasterId
 * @property integer $sectionDetailId
 * @property boolean $isActive
 */
class ContractSettingDetail extends Model
{

    use HasFactory;
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_contract_setting_detail';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'uuid',
        'contractId',
        'settingMasterId',
        'sectionDetailId',
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
        'settingMasterId' => 'integer',
        'sectionDetailId' => 'integer',
        'isActive' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'settingMasterId' => 'required|integer',
        'sectionDetailId' => 'nullable|integer',
        'isActive' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


    public function contractSectionDetails()
    {
        return $this->belongsTo(ContractSectionDetail::class, 'sectionDetailId', 'id');
    }

    public static function getSettingDetails($contractId, $id)
    {
        return self::where('contractId', $contractId)
            ->where('settingMasterId', $id)
            ->get();
    }

    public static function getContractIdColumn()
    {
        return 'contractId';
    }

    public static function getCompanyIdColumn()
    {
        return null;
    }

    public static function getActiveContractPaymentSchedule($contractID)
    {
        return ContractSettingDetail::select('sectionDetailId')
            ->where('contractId', $contractID)
            ->whereIn('sectionDetailId', [1,2,3])
            ->where('isActive', 1)
            ->first();
    }

    public function getActiveContractPenalty($contractID)
    {
        return ContractSettingDetail::select('sectionDetailId')
            ->where('contractId', $contractID)
            ->whereIn('sectionDetailId', [6,7])
            ->where('isActive', 1)
            ->first();
    }

    public static function getActiveSections($contractId)
    {
        return ContractSettingDetail::select('sectionDetailId')
            ->where('contractId', $contractId)
            ->where('isActive', 1)
            ->get();
    }

    public function getActiveContractRetention($contractID)
    {
        return ContractSettingDetail::select('sectionDetailId')
            ->where('contractId', $contractID)
            ->whereIn('sectionDetailId', [4,5])
            ->where('isActive', 1)
            ->first();
    }
}
