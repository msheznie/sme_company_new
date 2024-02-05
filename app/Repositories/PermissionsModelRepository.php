<?php

namespace App\Repositories;

use App\Models\PermissionsModel;
use App\Repositories\BaseRepository;

/**
 * Class PermissionsModelRepository
 * @package App\Repositories
 * @version April 21, 2022, 11:26 am +04
*/

class PermissionsModelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'navigation_id',
        'guard_name'
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
        return PermissionsModel::class;
    }
}
