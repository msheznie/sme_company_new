<?php

namespace App\Repositories;

use App\Models\CMPartiesMaster;
use App\Repositories\BaseRepository;

/**
 * Class CMPartiesMasterRepository
 * @package App\Repositories
 * @version February 22, 2024, 2:26 pm +04
*/

class CMPartiesMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cmParty_name',
        'cpm_active',
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
        return CMPartiesMaster::class;
    }
}
