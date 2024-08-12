<?php

namespace App\Repositories;

use App\Models\ContractOverallPenaltyAmd;
use App\Repositories\BaseRepository;

/**
 * Class ContractOverallPenaltyAmdRepository
 * @package App\Repositories
 * @version July 23, 2024, 2:01 pm +04
*/

class ContractOverallPenaltyAmdRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'overall_penalty_id',
        'contract_history_id',
        'uuid',
        'contract_id',
        'minimum_penalty_percentage',
        'minimum_penalty_amount',
        'maximum_penalty_percentage',
        'maximum_penalty_amount',
        'actual_percentage',
        'actual_penalty_amount',
        'penalty_circulation_start_date',
        'actual_penalty_start_date',
        'penalty_circulation_frequency',
        'due_in',
        'due_penalty_amount',
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
        return ContractOverallPenaltyAmd::class;
    }
}
