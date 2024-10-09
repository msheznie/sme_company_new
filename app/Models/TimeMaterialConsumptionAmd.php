<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TimeMaterialConsumptionAmd
 * @package App\Models
 * @version October 7, 2024, 1:36 pm +04
 *
 * @property integer $id
 * @property integer $contract_history_id
 * @property integer $level_no
 * @property string $uuid
 * @property integer $contract_id
 * @property string $item
 * @property string $description
 * @property integer $min_quantity
 * @property integer $max_quantity
 * @property number $price
 * @property integer $quantity
 * @property integer $uom_id
 * @property number $amount
 * @property integer $boq_id
 * @property integer $currency_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class TimeMaterialConsumptionAmd extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_time_material_consumption_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'amd_id';
    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'contract_history_id',
        'level_no',
        'uuid',
        'contract_id',
        'item',
        'description',
        'min_quantity',
        'max_quantity',
        'price',
        'quantity',
        'uom_id',
        'amount',
        'boq_id',
        'currency_id',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'amd_id' => 'integer',
        'contract_history_id' => 'integer',
        'level_no' => 'integer',
        'uuid' => 'string',
        'contract_id' => 'integer',
        'item' => 'string',
        'description' => 'string',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'price' => 'float',
        'quantity' => 'integer',
        'uom_id' => 'integer',
        'amount' => 'float',
        'boq_id' => 'integer',
        'currency_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function units()
    {
        return $this->belongsTo(Unit::class, 'uom_id', 'UnitID');
    }

    public function getLevelNo($consumptionId, $contractId)
    {
        $levelNo = self::where('uuid',$consumptionId)
                ->where('contract_id', $contractId)
                ->max('level_no') + 1;

        return  max(1, $levelNo);
    }
    public static function getAllTimeMaterialConsumptionAmd($contractHistoryID, $contractID)
    {
        return self::select('uuid', 'item', 'description', 'min_quantity', 'max_quantity', 'price',
            'amount', 'quantity', 'uom_id')
            ->with([
                "units" => function ($q)
                {
                    $q->select('UnitID', 'UnitDes');
                },
            ])
            ->where('contract_id', $contractID)
            ->where('contract_history_id', $contractHistoryID)
            ->get();
    }
    public static function checkUuidExists($uuid)
    {
        return self::where('uuid', $uuid)->exists();
    }
    public static function checkExistRecordEmpty($contractID, $historyID)
    {
        return self::where('contract_id', $contractID)
            ->where('contract_history_id', $historyID)
            ->where( function ($q)
            {
                $q->whereNull('item')
                    ->orWhereNull('description')
                    ->orWhereNull('uom_id')
                    ->orWhere('quantity', '=', 0)
                    ->orWhere('price', '=', 0);
            })
            ->exists();
    }
    public static function findTimeMaterialConsumption($uuid, $historyID)
    {
        return self::select('amd_id', 'deleted_by')
            ->where('uuid', $uuid)
            ->where('contract_history_id', $historyID)
            ->first();
    }
    public function getAllAmdRecords($contractHistoryID, $withNullRecord = false)
    {
       return self::where('contract_history_id', $contractHistoryID)
           ->where(function ($q) use ($withNullRecord)
           {
               $q->when(!$withNullRecord, function ($q)
               {
                   $q->whereNotNull('id');
               })->when($withNullRecord, function ($q)
               {
                   $q->whereNull('id');
               });
           })
           ->get();
    }
}
