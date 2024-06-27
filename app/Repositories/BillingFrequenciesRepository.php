<?php

namespace App\Repositories;

use App\Models\BillingFrequencies;
use App\Repositories\BaseRepository;

/**
 * Class BillingFrequenciesRepository
 * @package App\Repositories
 * @version June 26, 2024, 8:55 am +04
*/

class BillingFrequenciesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
        'company_id',
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
        return BillingFrequencies::class;
    }
}
