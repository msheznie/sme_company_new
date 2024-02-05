<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Casts\DateSettingsFormat;
use App\Services\GeneralService;
use Carbon\Carbon;

/**
 * Class PriceList
 * @package App\Models
 * @version April 22, 2022, 10:14 am +04
 *
 * @property string $item_code
 * @property string $item_description
 * @property string $part_number
 * @property string $uom
 * @property integer $delivery_lead_time
 * @property integer $currency_id
 * @property integer $from_date
 * @property integer $to_date
 * @property integer $is_active
 * @property integer $created_by
 * @property integer $updated_by
 */
class PriceList extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'price_list';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'item_code',
        'item_description',
        'part_number',
        'uom',
        'delivery_lead_time',
        'unit_price',
        'currency_id',
        'from_date',
        'to_date',
        'is_active',
        'created_by',
        'updated_by',
        'tenant_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'item_code' => 'string',
        'item_description' => 'string',
        'part_number' => 'string',
        'from_date' => 'date',
        'to_date' => 'date',
        'uom' => 'string',
        'delivery_lead_time' => 'float',
        'unit_price' => 'float',
        'currency_id' => 'integer', 
        'is_active' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'tenant_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'item_code' => 'nullable|string|max:255',
        'item_description' => 'nullable|string|max:255',
        'part_number' => 'nullable|string|max:255',
        'uom' => 'nullable|string|max:255',
        'delivery_lead_time' => 'nullable|float',
        'unit_price' => 'nullable|float',
        'currency_id' => 'nullable|integer', 
        'is_active' => 'required|integer',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'tenant_id' => 'nullable|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(CurrencyMaster::class, 'currency_id', 'id');
    }
    public function setFromDateAttribute($value)
    {
        $this->attributes['from_date'] = GeneralService::dateAddTime($value);
    }
    public function setToDateAttribute($value)
    {
        $this->attributes['to_date'] = GeneralService::dateAddTime($value);
    }

     public function getFromDateAttribute(): string
    {
        if (isset($this->attributes['from_date'])) {
            if ($this->attributes['from_date']) {
                return Carbon::parse($this->attributes['from_date'])->format('Y-m-d');
            } else {
                return 'N/A';
            }
        }

        return '';
    }
    public function getToDateAttribute(): string
    {
        if (isset($this->attributes['to_date'])) {
            if ($this->attributes['to_date']) {
                return Carbon::parse($this->attributes['to_date'])->format('Y-m-d');
            } else {
                return 'N/A';
            }
        }

        return '';
    } 

    
}
