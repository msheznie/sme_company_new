<?php

namespace App\Repositories;

use App\Models\AppearanceElements;
use App\Repositories\BaseRepository;

/**
 * Class AppearanceElementsRepository
 * @package App\Repositories
 * @version July 8, 2024, 3:33 pm +04
*/

class AppearanceElementsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'elementName'
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
        return AppearanceElements::class;
    }
}
