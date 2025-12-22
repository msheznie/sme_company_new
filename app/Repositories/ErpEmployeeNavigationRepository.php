<?php

namespace App\Repositories;

use App\Models\ErpEmployeeNavigation;
use App\Repositories\BaseRepository;

/**
 * Class ErpEmployeeNavigationRepository
 * @package App\Repositories
 * @version February 16, 2024, 5:55 pm +04
*/

class ErpEmployeeNavigationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'employeeSystemID',
        'userGroupID',
        'companyID',
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
        return ErpEmployeeNavigation::class;
    }

    public function  getCurrentUserCompanies($user_id)
    {
        return $this->model->getCurrentUserCompanies($user_id);
    }
}
