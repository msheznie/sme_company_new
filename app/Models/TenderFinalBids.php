<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *      schema="TenderFinalBids",
 *      required={""},
 *      @OA\Property(
 *          property="award",
 *          description="award",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="boolean"
 *      ),
 *      @OA\Property(
 *          property="bid_id",
 *          description="bid_id",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="com_weightage",
 *          description="com_weightage",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="number",
 *          format="number"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          description="created_at",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="string",
 *          format="date-time"
 *      ),
 *      @OA\Property(
 *          property="id",
 *          description="id",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="status",
 *          description="status",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="boolean"
 *      ),
 *      @OA\Property(
 *          property="supplier_id",
 *          description="supplier_id",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="tech_weightage",
 *          description="tech_weightage",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="number",
 *          format="number"
 *      ),
 *      @OA\Property(
 *          property="tender_id",
 *          description="tender_id",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="total_weightage",
 *          description="total_weightage",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="number",
 *          format="number"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          readOnly=$FIELD_READ_ONLY$,
 *          nullable=$FIELD_NULLABLE$,
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class TenderFinalBids extends Model
{

    public $table = 'srm_tender_final_bids';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'award',
        'bid_id',
        'com_weightage',
        'status',
        'supplier_id',
        'tech_weightage',
        'tender_id',
        'total_weightage',
        'combined_ranking',
        'commercial_ranking'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'award' => 'boolean',
        'bid_id' => 'integer',
        'com_weightage' => 'float',
        'id' => 'integer',
        'status' => 'boolean',
        'supplier_id' => 'integer',
        'tech_weightage' => 'float',
        'tender_id' => 'integer',
        'total_weightage' => 'float',
        'commercial_ranking' => 'integer',
        'combined_ranking' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'award' => 'required',
        'bid_id' => 'required',
        'status' => 'required',
        'supplier_id' => 'required',
        'tender_id' => 'required'
    ];

    public function tender_master()
    {
        return $this->belongsTo('App\Models\TenderMaster', 'tender_id', 'id');
    }

    public static function getTenderListBySupplier($supplierId, $companyId)
    {
        $supplierRegistrationId = SupplierRegistrationLink::getSupplierId($supplierId);

        if (!$supplierRegistrationId)
        {
            return collect();
        }

        $tenderList = self::with([
            'tender_master' => function ($q) use ($companyId)
            {
                $q->select('id', 'title', 'document_type')
                    ->where('document_type', 0)
                    ->where('company_id', $companyId);
            }
        ])->whereHas('tender_master', function ($q) use ($companyId)
        {
            $q->where('company_id', $companyId)
                ->where('document_type', 0);
        })->where('supplier_id', $supplierRegistrationId->id)
            ->where('award', 1)
            ->get(['award', 'tender_id']);

        $tenderList->each(function ($item)
        {
            if($item->tender_master)
            {
                $item->tender_master_title = $item->tender_master->title;
                unset($item->tender_master);
            } else
            {
                $item->tender_master_title = null;
            }
        });

        return $tenderList;
    }
}
