<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractBoqItemsAmd
 * @package App\Models
 * @version July 2, 2024, 12:20 pm +04
 *
 * @property integer $id
 * @property integer $contract_history_id
 * @property string $uuid
 * @property integer $contractId
 * @property integer $companyId
 * @property integer $itemId
 * @property string $description
 * @property integer $minQty
 * @property integer $maxQty
 * @property integer $qty
 * @property string $created_by
 * @property string $updated_by
 */
class CMContractBoqItemsAmd extends Model
{

    use HasFactory;

    public $table = 'cm_contract_boq_items_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'id',
        'contract_history_id',
        'uuid',
        'price',
        'origin',
        'contractId',
        'companyId',
        'itemId',
        'description',
        'minQty',
        'maxQty',
        'qty',
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
        'amd_id' => 'integer',
        'contract_history_id' => 'integer',
        'uuid' => 'string',
        'contractId' => 'integer',
        'companyId' => 'integer',
        'itemId' => 'integer',
        'description' => 'string',
        'price' => 'float',
        'origin' => 'integer',
        'minQty' => 'integer',
        'maxQty' => 'integer',
        'qty' => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public function itemMaster()
    {
        return $this->belongsTo(ItemMaster::class, 'itemId', 'itemCodeSystem');
    }

    public static function getBoqItemData($historyId,$uuid)
    {
            return self::where('contract_history_id',$historyId)
                ->where('uuid',$uuid)
                ->first();
    }

    public static function copyIdsRange($row)
    {
        return self::select('amd_id')
            ->where('contract_history_id',$row->contract_history_id)
            ->where('amd_id', '<', $row->amd_id)
            ->where('companyId', $row->companyId)
            ->where('contractId', $row->contractId)
            ->get()
            ->pluck('amd_id')
            ->toArray();
    }


}
