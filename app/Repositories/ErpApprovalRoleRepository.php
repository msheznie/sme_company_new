<?php

namespace App\Repositories;

use App\Models\ErpApprovalRole;
use App\Repositories\BaseRepository;

/**
 * Class ErpApprovalRoleRepository
 * @package App\Repositories
 * @version May 22, 2024, 1:04 pm +04
*/

class ErpApprovalRoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rollDescription',
        'documentSystemID',
        'documentID',
        'companySystemID',
        'companyID',
        'departmentSystemID',
        'departmentID',
        'serviceLineSystemID',
        'serviceLineID',
        'rollLevel',
        'approvalLevelID',
        'approvalGroupID',
        'timeStamp'
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
        return ErpApprovalRole::class;
    }
}
