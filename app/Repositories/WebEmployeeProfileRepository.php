<?php

namespace App\Repositories;

use App\Models\WebEmployeeProfile;
use App\Repositories\BaseRepository;

/**
 * Class WebEmployeeProfileRepository
 * @package App\Repositories
 * @version February 14, 2024, 3:25 pm +04
*/

class WebEmployeeProfileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'employeeSystemID',
        'empID',
        'profileImage',
        'modifiedDate',
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
        return WebEmployeeProfile::class;
    }
}
