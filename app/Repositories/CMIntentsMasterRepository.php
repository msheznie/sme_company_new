<?php

namespace App\Repositories;

use App\Models\CMIntentsMaster;
use App\Repositories\BaseRepository;

/**
 * Class CMIntentsMasterRepository
 * @package App\Repositories
 * @version February 22, 2024, 2:33 pm +04
*/

class CMIntentsMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cmIntent_detail',
        'cim_active',
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
        return CMIntentsMaster::class;
    }
}
