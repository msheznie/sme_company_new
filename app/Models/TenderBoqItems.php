<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Log;

/**
 * @SWG\Definition(
 *      definition="TenderBoqItems",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="main_work_id",
 *          description="main_work_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="item_id",
 *          description="item_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="uom",
 *          description="uom",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="qty",
 *          description="qty",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="created_by",
 *          description="created_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_by",
 *          description="updated_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="company_id",
 *          description="company_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class TenderBoqItems extends Model
{

    public $table = 'srm_tender_boq_items';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'main_work_id',
        'item_name',
        'description',
        'uom',
        'qty',
        'created_by',
        'updated_by',
        'company_id',
        'tender_ranking_line_item',
        'tender_id',
        'item_primary_code',
        'origin',
        'purchase_request_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'main_work_id' => 'integer',
        'item_name' => 'string',
        'description' => 'string',
        'uom' => 'integer',
        'qty' => 'float',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'company_id' => 'integer',
        'item_primary_code' => 'string',
        'origin' => 'integer',
        'purchase_request_id' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function bid_boq()
    {
        return $this->hasOne('App\Models\BidBoq', 'boq_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','uom','UnitID');
    }

    public static function getBoqItemList($bidMasterId)
    {
        return self::select([
            'srm_tender_boq_items.id as itemCodeSystem',
            'srm_tender_boq_items.item_name as itemPrimaryCode',
            'srm_tender_boq_items.description as itemDescription',
            'srm_tender_boq_items.item_name as barcode',
            'srm_tender_boq_items.main_work_id',
            'srm_tender_boq_items.uom',
            'srm_tender_boq_items.qty',
            'srm_bid_boq.unit_amount as price',
            'srm_bid_boq.total_amount'
        ])
            ->join('srm_bid_boq', 'srm_tender_boq_items.id', '=', 'srm_bid_boq.boq_id')
            ->where('srm_bid_boq.bid_master_id', $bidMasterId)
            ->with(['unit' => function ($q)
            {
                $q->select('UnitID', 'UnitShortCode');
            }]);
    }

}
