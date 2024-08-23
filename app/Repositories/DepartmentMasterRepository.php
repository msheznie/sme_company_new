<?php

namespace App\Repositories;

use App\Models\DepartmentMaster;
use App\Repositories\BaseRepository;

/**
 * Class DepartmentMasterRepository
 * @package App\Repositories
 * @version August 22, 2024, 1:21 pm +04
*/

class DepartmentMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'DepartmentDes',
        'parent_department_id',
        'is_root_department',
        'Erp_companyID',
        'SchMasterID',
        'BranchID',
        'SortOrder',
        'hod_id',
        'isActive',
        'created_by',
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
        return DepartmentMaster::class;
    }
}
