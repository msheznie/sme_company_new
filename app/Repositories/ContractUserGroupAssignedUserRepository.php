<?php

namespace App\Repositories;

use App\Models\ContractUserGroupAssignedUser;
use App\Repositories\BaseRepository;

/**
 * Class ContractUserGroupRepository
 * @package App\Repositories
 * @version May 7, 2024, 10:59 am +04
*/

class ContractUserGroupAssignedUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'userGroupId',
        'companySystemID',
        'contractUserId',
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
        return ContractUserGroupAssignedUser::class;
    }
}
