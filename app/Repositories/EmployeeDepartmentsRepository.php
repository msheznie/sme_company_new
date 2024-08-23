<?php

namespace App\Repositories;

use App\Models\EmployeeDepartments;
use App\Repositories\BaseRepository;

/**
 * Class EmployeeDepartmentsRepository
 * @package App\Repositories
 * @version August 22, 2024, 1:16 pm +04
*/

class EmployeeDepartmentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmpID',
        'DepartmentMasterID',
        'isPrimary',
        'date_from',
        'date_to',
        'Erp_companyID',
        'SchMasterID',
        'BranchID',
        'AcademicYearID',
        'isActive',
        'CreatedUserName',
        'CreatedDate',
        'CreatedPC',
        'ModifiedUserName',
        'Timestamp',
        'ModifiedPC'
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
        return EmployeeDepartments::class;
    }
}
