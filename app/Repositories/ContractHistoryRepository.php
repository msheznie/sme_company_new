<?php

namespace App\Repositories;

use App\Models\ContractHistory;
use App\Repositories\BaseRepository;

/**
 * Class ContractHistoryRepository
 * @package App\Repositories
 * @version May 29, 2024, 2:49 pm +04
*/

class ContractHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category',
        'date',
        'end_date',
        'contract_id',
        'company_id',
        'contract_title',
        'created_date',
        'created_by'
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
        return ContractHistory::class;
    }
}
