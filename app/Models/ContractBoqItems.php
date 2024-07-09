<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;
/**
 * Class ContractBoqItems
 * @package App\Models
 * @version April 24, 2024, 8:31 am +04
 *
 * @property string $uuid
 * @property integer $contractId
 * @property integer $itemId
 * @property string $description
 * @property integer $minQty
 * @property integer $maxQty
 * @property integer $qty
 * @property string $created_by
 * @property string $updated_by
 */
class ContractBoqItems extends Model
{
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    use HasFactory;

    public $table = 'cm_contract_boq_items';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id'];




    public $fillable = [
        'uuid',
        'contractId',
        'itemId',
        'description',
        'minQty',
        'maxQty',
        'qty',
        'price',
        'companyId',
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
        'contractId' => 'integer',
        'itemId' => 'integer',
        'description' => 'string',
        'minQty' => 'integer',
        'maxQty' => 'integer',
        'qty' => 'integer',
        'price' => 'float',
        'companyId' => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function itemMaster()
    {
        return $this->belongsTo(ItemMaster::class, 'itemId', 'itemCodeSystem');
    }
    public static function getContractIdColumn()
    {
        return 'contractId';
    }

    public static function getCompanyIdColumn()
    {
        return 'companyId';
    }

    public function getBoqItemDetails($uuid)
    {
        return ContractBoqItems::select('id', 'itemId', 'description', 'minQty', 'maxQty', 'qty', 'price')
            ->with([
                'itemMaster' => function ($q)
                {
                    $q->select('itemCodeSystem', 'primaryCode', 'unit');
                    $q->with([
                        'itemAssigned' => function ($q)
                        {
                            $q->select('idItemAssigned', 'itemCodeSystem', 'wacValueLocal');
                        }
                    ]);
                }
            ])
            ->where('uuid', $uuid)
            ->first();
    }

    public function getBoqData($id)
    {
        return self::where('contractId',$id)
            ->get();
    }

    public static function checkValues($contractId, $companySystemID, $checkType)
    {
        return ContractBoqItems::select('qty', 'price')
            ->where('contractId', $contractId)
            ->where('companyId', $companySystemID)
            ->where(function($query) use ($checkType)
            {
                if ($checkType == 'zero')
                {
                    $query->where('qty', 0)
                        ->orWhere('price', 0);
                }
                if ($checkType == 'empty')
                {
                    $query->whereNull('qty')
                        ->orWhereNull('price')
                        ->orWhere('qty', '')
                        ->orWhere('price', '');
                }
            })
            ->get();
    }
}
