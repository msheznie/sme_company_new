<?php

namespace App\Repositories;

use App\Models\SupplierDetailHistory;
use App\Repositories\BaseRepository;

/**
 * Class SupplierDetailHistoryRepository
 * @package App\Repositories
 * @version November 13, 2023, 6:50 am +04
*/

class SupplierDetailHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'tenant_id',
        'form_section_id',
        'form_group_id',
        'form_field_id',
        'form_data_id',
        'sort',
        'value',
        'status',
        'master_id'
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
        return SupplierDetailHistory::class;
    }
}
