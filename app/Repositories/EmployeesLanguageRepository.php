<?php

namespace App\Repositories;

use App\Models\EmployeesLanguage;
use App\Repositories\BaseRepository;

/**
 * Class EmployeesLanguageRepository
 * @package App\Repositories
 * @version September 1, 2025, 12:04 pm +04
*/

class EmployeesLanguageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'employeeID',
        'languageID'
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
        return EmployeesLanguage::class;
    }
}
