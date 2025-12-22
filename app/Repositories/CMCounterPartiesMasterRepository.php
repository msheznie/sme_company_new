<?php

namespace App\Repositories;

use App\Models\CMCounterPartiesMaster;
use App\Repositories\BaseRepository;

/**
 * Class CMCounterPartiesMasterRepository
 * @package App\Repositories
 * @version February 22, 2024, 2:35 pm +04
*/

class CMCounterPartiesMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cmCounterParty_name',
        'cpt_active',
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
        return CMCounterPartiesMaster::class;
    }
}
