<?php

namespace App\Repositories;

use App\Models\CMContractsMaster;
use App\Repositories\BaseRepository;

/**
 * Class CMContractsMasterRepository
 * @package App\Repositories
 * @version February 22, 2024, 2:38 pm +04
*/

class CMContractsMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cmMaster_description',
        'ctm_active',
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
        return CMContractsMaster::class;
    }
}
