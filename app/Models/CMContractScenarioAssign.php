<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractScenarioAssign
 * @package App\Models
 * @version July 1, 2024, 8:01 pm +04
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 */
class CMContractScenarioAssign extends Model
{
    use HasFactory;

    public $table = 'cm_contract_scenario_assign';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'id',
        'uuid',
        'scenario_id',
        'is_active',
        'contract_id',
        'company_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'scenario_id' => 'integer',
        'contract_id' => 'integer',
        'is_active' => 'integer',
        'company_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [ ];

    public function contractMaster()
    {
        return $this->belongsTo(ContractMaster::class, 'contract_id', 'id');
    }

    public function contractScenarioSettings()
    {
        return $this->hasMany(CMContractScenarioSetting::class, 'scenario_assign_id', 'id');
    }

    public static function getContractScenarioIsActive($contractId, $companyId)
    {
        $record = self::select('is_active')
            ->where('contract_id', $contractId)
            ->where('company_id', $companyId)
            ->first();

        if ($record)
        {
            return $record->is_active;
        }
        return false;
    }
    public static function getContractScenarioId($contractId, $companyId)
    {
        $record = self::select('id')
            ->where('contract_id', $contractId)
            ->where('company_id', $companyId)
            ->first();

        if ($record)
        {
            return $record->id;
        }
        return false;
    }

    public static function  getReminderContractExpiryBefore()
    {
        return self::where('is_active', 1)
            ->whereHas('contractScenarioSettings', function ($query)
            {
                $query->where('scenario_type', 1);
            })
            ->whereHas('contractMaster', function ($query)
            {
                $query->whereRaw('DATEDIFF(endDate, CURDATE()) > 0')
                ->whereRaw('DATEDIFF(endDate, CURDATE()) < (
            SELECT value FROM cm_contract_scenario_setting
            WHERE scenario_assign_id = cm_contract_scenario_assign.id
            AND scenario_type = 1 LIMIT 1
        )');
            })
            ->with([
                'contractScenarioSettings' => function ($query)
                {
                    $query->where('scenario_type', 1);
                },
                'contractMaster' => function ($query)
                {
                    $query->select
                    (
                        'id', 'title', 'endDate', 'contractOwner', 'counterPartyName', 'companySystemID',
                        'contractCode'
                    );
                }
            ])
            ->get();
    }

    public static function getReminderContractExpiryAfter()
    {
        return self::where('is_active', 1)
            ->whereHas('contractScenarioSettings', function ($query)
            {
                $query->where('scenario_type', 2);
            })
            ->whereHas('contractMaster', function ($query)
            {
                $query->whereRaw('
            DATEDIFF(CURDATE(), endDate) % (
                SELECT CASE
                    WHEN value = 1 THEN 1
                    WHEN value = 2 THEN 3
                    WHEN value = 3 THEN 7
                    WHEN value = 4 THEN 14
                    WHEN value = 5 THEN 30
                    ELSE 0
                END
                FROM cm_contract_scenario_setting
                WHERE scenario_assign_id = cm_contract_scenario_assign.id
                AND scenario_type = 2
                LIMIT 1
            ) = 0
            AND DATEDIFF(CURDATE(), endDate) >= (
                SELECT CASE
                    WHEN value = 1 THEN 1
                    WHEN value = 2 THEN 3
                    WHEN value = 3 THEN 7
                    WHEN value = 4 THEN 14
                    WHEN value = 5 THEN 30
                    ELSE 0
                END
                FROM cm_contract_scenario_setting
                WHERE scenario_assign_id = cm_contract_scenario_assign.id
                AND scenario_type = 2
                LIMIT 1
            )'
                );
            })
            ->with([
                'contractScenarioSettings' => function ($query)
                {
                    $query->where('scenario_type', 2);
                }, 'contractMaster' => function ($query)
                {
                    $query->select('id', 'title', 'endDate', 'contractCode',
                        'contractOwner', 'counterPartyName', 'companySystemID');
                }
            ])
            ->get();
    }

}
