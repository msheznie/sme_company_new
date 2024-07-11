<?php

namespace App\Repositories;

use App\Models\SystemConfigurationDetail;
use App\Repositories\BaseRepository;

/**
 * Class SystemConfigurationDetailRepository
 * @package App\Repositories
 * @version July 8, 2024, 4:06 pm +04
*/

class SystemConfigurationDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'attributeId',
        'value'
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
        return SystemConfigurationDetail::class;
    }
}
