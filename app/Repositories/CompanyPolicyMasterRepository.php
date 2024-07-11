<?php

namespace App\Repositories;

use App\Models\CompanyPolicyMaster;
use App\Repositories\BaseRepository;

/**
 * Class CompanyPolicyMasterRepository
 * @package App\Repositories
 * @version July 2, 2024, 10:35 am +04
*/

class CompanyPolicyMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companyPolicyCategoryID',
        'companySystemID',
        'companyID',
        'documentID',
        'isYesNO',
        'policyValue',
        'createdByUserID',
        'createdByUserName',
        'createdByPCID',
        'modifiedByUserID',
        'createdDateTime',
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
        return CompanyPolicyMaster::class;
    }
}
