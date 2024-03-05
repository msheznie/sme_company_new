<?php

namespace App\Repositories;

use App\Models\CMContractSectionsMaster;
use App\Repositories\BaseRepository;

/**
 * Class CMContractSectionsMasterRepository
 * @package App\Repositories
 * @version February 26, 2024, 6:56 pm +04
*/

class CMContractSectionsMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cmSection_detail',
        'csm_active',
        'timestamp'
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
        return CMContractSectionsMaster::class;
    }
}
