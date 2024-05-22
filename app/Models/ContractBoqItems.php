<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
   // use SoftDeletes;

    use HasFactory;

    public $table = 'cm_contract_boq_items';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'itemId'];




    public $fillable = [
        'uuid',
        'contractId',
        'itemId',
        'description',
        'minQty',
        'maxQty',
        'qty',
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

    public function itemMaster(){
        return $this->belongsTo(ItemMaster::class, 'itemId', 'itemCodeSystem');
    }
}
