<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TimeMaterialConsumption
 * @package App\Models
 * @version June 29, 2024, 10:04 pm +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property string $item
 * @property string $description
 * @property integer $min_quantity
 * @property integer $max_quantity
 * @property number $price
 * @property number $amount
 * @property integer $boq_id
 * @property integer $currency_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class TimeMaterialConsumption extends Model
{
    use HasFactory;

    public $table = 'cm_time_material_consumption';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = ['id', 'boq_id'];

    public $fillable = [
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
        'updated_by' => 'integer'
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

    public function getAllTimeMaterialConsumption($contractID)
    {
        return TimeMaterialConsumption::select('uuid', 'item', 'description', 'min_quantity', 'max_quantity', 'price',
                'amount', 'quantity', 'uom_id')
            ->with([
                "units" => function ($q)
                {
                    $q->select('UnitID', 'UnitDes');
                },
            ])
            ->where('contract_id', $contractID)
            ->get();
    }
    public static function checkExistRecordEmpty($contractID)
    {
        return TimeMaterialConsumption::where('contract_id', $contractID)
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

    public static function existTimeMaterialConsumption($contractId, $companySystemID)
    {
        return TimeMaterialConsumption::where('contract_id', $contractId)
            ->where('company_id', $companySystemID)
            ->first();
    }
}
