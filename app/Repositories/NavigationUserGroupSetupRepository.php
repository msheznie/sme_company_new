<?php

namespace App\Repositories;

use App\Models\ErpEmployeeNavigation;
use App\Models\NavigationUserGroupSetup;
use App\Repositories\BaseRepository;

/**
 * Class NavigationUserGroupSetupRepository
 * @package App\Repositories
 * @version February 19, 2024, 2:14 pm +04
*/

class NavigationUserGroupSetupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'userGroupID',
        'companyID',
        'navigationMenuID',
        'description',
        'masterID',
        'url',
        'pageID',
        'pageTitle',
        'pageIcon',
        'levelNo',
        'sortOrder',
        'isSubExist',
        'readonly',
        'create',
        'update',
        'delete',
        'print',
        'export',
        'timestamp',
        'isPortalYN',
        'externalLink'
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
        return NavigationUserGroupSetup::class;
    }

    public function userMenusByCompany($companySystemID, $userId) {
        $userGroupID = ErpEmployeeNavigation::where('employeeSystemID', $userId)
            ->where('companyID', $companySystemID)
            ->value('userGroupID');
        return $this->model->userMenusByCompany($companySystemID, $userGroupID);
    }
}
