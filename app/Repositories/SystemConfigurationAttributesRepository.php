<?php

namespace App\Repositories;

use App\Models\SystemConfigurationAttributes;
use App\Repositories\BaseRepository;

/**
 * Class SystemConfigurationAttributesRepository
 * @package App\Repositories
 * @version July 8, 2024, 4:05 pm +04
*/

class SystemConfigurationAttributesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'systemConfigurationId',
        'name',
        'slug'
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
        return SystemConfigurationAttributes::class;
    }
}
