<?php

namespace App\Repositories;

use App\Models\ErpEmployeesDepartments;
use App\Repositories\BaseRepository;

/**
 * Class ErpEmployeesDepartmentsRepository
 * @package App\Repositories
 * @version June 3, 2024, 3:24 pm +04
*/

class ErpEmployeesDepartmentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'employeeSystemID',
        'employeeID',
        'employeeGroupID',
        'companySystemID',
        'companyId',
        'documentSystemID',
        'documentID',
        'departmentSystemID',
        'departmentID',
        'ServiceLineSystemID',
        'ServiceLineID',
        'warehouseSystemCode',
        'reportingManagerID',
        'isDefault',
        'dischargedYN',
        'approvalDeligated',
        'approvalDeligatedFromEmpID',
        'approvalDeligatedFrom',
        'approvalDeligatedTo',
        'dmsIsUploadEnable',
        'isActive',
        'activatedDate',
        'activatedByEmpID',
        'activatedByEmpSystemID',
        'removedYN',
        'removedByEmpID',
        'removedByEmpSystemID',
        'removedDate',
        'createdDate',
        'createdByEmpSystemID',
        'timeStamp',
        'deligateDetaileID'
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
        return ErpEmployeesDepartments::class;
    }
}
