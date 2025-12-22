<?php

namespace App\Repositories;

use App\Models\Employees;
use App\Repositories\BaseRepository;

/**
 * Class EmployeesRepository
 * @package App\Repositories
 * @version February 13, 2024, 8:05 pm +04
*/

class EmployeesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empID',
        'hrmsEmpID',
        'serial',
        'empLeadingText',
        'empUserName',
        'empTitle',
        'empInitial',
        'empName',
        'empName_O',
        'empFullName',
        'empSurname',
        'empSurname_O',
        'empFirstName',
        'empFirstName_O',
        'empFamilyName',
        'empFamilyName_O',
        'empFatherName',
        'empFatherName_O',
        'empManagerAttached',
        'empDateRegistered',
        'empTelOffice',
        'empTelMobile',
        'empLandLineNo',
        'extNo',
        'empFax',
        'empEmail',
        'empLocation',
        'empDateTerminated',
        'empLoginActive',
        'empActive',
        'userGroupID',
        'empCompanySystemID',
        'empCompanyID',
        'religion',
        'isLoggedIn',
        'isLoggedOutFailYN',
        'logingFlag',
        'isSuperAdmin',
        'discharegedYN',
        'isFinalSettlementDone',
        'hrusergroupID',
        'employmentType',
        'isConsultant',
        'isTrainee',
        'is3rdParty',
        '3rdPartyCompanyName',
        'gender',
        'designation',
        'nationality',
        'isManager',
        'isApproval',
        'isDashBoard',
        'isAdmin',
        'isBasicUser',
        'ActivationCode',
        'ActivationFlag',
        'isHR_admin',
        'isLock',
        'basicDataIngCount',
        'opRptManagerAccess',
        'isSupportAdmin',
        'isHSEadmin',
        'excludeObjectivesYN',
        'machineID',
        'timestamp',
        'createdFrom',
        'isNewPortal',
        'uuid'
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
        return Employees::class;
    }
}
