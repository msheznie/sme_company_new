<?php

namespace App\Repositories;

use App\Models\PriceList;
use App\Repositories\BaseRepository;

/**
 * Class PriceListRepository
 * @package App\Repositories
 * @version April 22, 2022, 10:14 am +04
*/

class PriceListRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'item_code',
        'item_description',
        'part_number',
        'uom',
        'delivery_lead_time',
        'currency_id',
        'from_date',
        'to_date',
        'is_active',
        'created_by',
        'updated_by'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PriceList::class;
    }
}
