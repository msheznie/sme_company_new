<?php

namespace App\Repositories;

use App\Models\NavigationRole;
use App\Repositories\BaseRepository;

/**
 * Class NavigationRoleRepository
 * @package App\Repositories
 * @version February 10, 2022, 9:49 am +04
*/

class NavigationRoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'role_id'
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
        return NavigationRole::class;
    }
}
