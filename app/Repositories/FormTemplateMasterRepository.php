<?php

namespace App\Repositories;

use App\Models\FormTemplateMaster;
use App\Repositories\BaseRepository;

/**
 * Class FormTemplateMasterRepository
 * @package App\Repositories
 * @version March 14, 2022, 1:56 pm +04
*/

class FormTemplateMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tenant_id',
        'name',
        'description',
        'status'
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
        return FormTemplateMaster::class;
    }
}
