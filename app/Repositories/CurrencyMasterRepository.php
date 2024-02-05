<?php

namespace App\Repositories;

use App\Models\CurrencyMaster;
use App\Repositories\BaseRepository;

/**
 * Class CurrencyMasterRepository
 * @package App\Repositories
 * @version April 26, 2022, 12:11 pm +04
*/

class CurrencyMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'currency_name',
        'currency_code',
        'decimal_places',
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
        return CurrencyMaster::class;
    }
}
