<?php

namespace App\Repositories;

use App\Models\ErpApprovalLevel;
use App\Repositories\BaseRepository;

/**
 * Class ErpApprovalLevelRepository
 * @package App\Repositories
 * @version May 22, 2024, 1:01 pm +04
*/

class ErpApprovalLevelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companySystemID',
        'companyID',
        'departmentSystemID',
        'departmentID',
        'serviceLineWise',
        'serviceLineSystemID',
        'serviceLineCode',
        'documentSystemID',
        'documentID',
        'levelDescription',
        'noOfLevels',
        'valueWise',
        'valueFrom',
        'valueTo',
        'isCategoryWiseApproval',
        'categoryID',
        'isActive',
        'is_deleted',
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
        return ErpApprovalLevel::class;
    }
}
