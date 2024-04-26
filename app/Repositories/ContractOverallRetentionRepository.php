<?php

namespace App\Repositories;

use App\Models\ContractOverallRetention;
use App\Repositories\BaseRepository;

/**
 * Class ContractOverallRetentionRepository
 * @package App\Repositories
 * @version April 22, 2024, 4:09 pm +04
*/

class ContractOverallRetentionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contractId',
        'contractAmount',
        'retentionPercentage',
        'retentionAmount',
        'startDate',
        'dueDate',
        'retentionWithholdPeriod',
        'companySystemId',
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
        return ContractOverallRetention::class;
    }
}
