<?php

namespace App\Repositories;

use App\Models\Tenant;
use App\Repositories\BaseRepository;

/**
 * Class TenantRepository
 * @package App\Repositories
 * @version February 13, 2024, 5:57 pm +04
*/

class TenantRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'sub_domain',
        'erp_domain',
        'azureLogin',
        'database',
        'api_key',
        'is_active'
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
        return Tenant::class;
    }
}
