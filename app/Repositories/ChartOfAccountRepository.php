<?php

namespace App\Repositories;

use App\Models\ChartOfAccount;
use App\Repositories\BaseRepository;

/**
 * Class ChartOfAccountRepository
 * @package App\Repositories
 * @version September 9, 2024, 12:47 pm +04
*/

class ChartOfAccountRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'primaryCompanySystemID',
        'primaryCompanyID',
        'documentSystemID',
        'documentID',
        'AccountCode',
        'AccountDescription',
        'masterAccount',
        'catogaryBLorPLID',
        'catogaryBLorPL',
        'controllAccountYN',
        'controlAccountsSystemID',
        'controlAccounts',
        'isApproved',
        'approvedBySystemID',
        'approvedBy',
        'approvedDate',
        'approvedComment',
        'isActive',
        'isBank',
        'AllocationID',
        'relatedPartyYN',
        'interCompanySystemID',
        'interCompanyID',
        'confirmedYN',
        'confirmedEmpSystemID',
        'confirmedEmpID',
        'confirmedEmpName',
        'confirmedEmpDate',
        'isMasterAccount',
        'RollLevForApp_curr',
        'refferedBackYN',
        'timesReferred',
        'createdPcID',
        'createdUserGroup',
        'createdUserID',
        'createdDateTime',
        'modifiedPc',
        'modifiedUser',
        'timestamp',
        'reportTemplateCategory'
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
        return ChartOfAccount::class;
    }
}
