<?php

namespace App\Repositories;

use App\Models\CMContractStatusHistoryAmd;
use App\Repositories\BaseRepository;

/**
 * Class CMContractStatusHistoryAmdRepository
 * @package App\Repositories
 * @version July 3, 2024, 9:43 am +04
*/

class CMContractStatusHistoryAmdRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'contract_history_id',
        'uuid',
        'contractID',
        'milestoneID',
        'changedFrom',
        'changedTo',
        'companySystemID',
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
        return CMContractStatusHistoryAmd::class;
    }
}
