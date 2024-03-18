<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Employees
 * @package App\Models
 * @version February 13, 2024, 8:05 pm +04
 *
 * @property string $empID
 * @property integer $hrmsEmpID
 * @property integer $serial
 * @property string $empLeadingText
 * @property string $empUserName
 * @property string $empTitle
 * @property string $empInitial
 * @property string $empName
 * @property string $empName_O
 * @property string $empFullName
 * @property string $empSurname
 * @property string $empSurname_O
 * @property string $empFirstName
 * @property string $empFirstName_O
 * @property string $empFamilyName
 * @property string $empFamilyName_O
 * @property string $empFatherName
 * @property string $empFatherName_O
 * @property string $empManagerAttached
 * @property string $empDateRegistered
 * @property string $empTelOffice
 * @property string $empTelMobile
 * @property string $empLandLineNo
 * @property integer $extNo
 * @property string $empFax
 * @property string $empEmail
 * @property integer $empLocation
 * @property string|\Carbon\Carbon $empDateTerminated
 * @property integer $empLoginActive
 * @property integer $empActive
 * @property integer $userGroupID
 * @property integer $empCompanySystemID
 * @property string $empCompanyID
 * @property integer $religion
 * @property integer $isLoggedIn
 * @property integer $isLoggedOutFailYN
 * @property integer $logingFlag
 * @property integer $isSuperAdmin
 * @property integer $discharegedYN
 * @property integer $isFinalSettlementDone
 * @property string $hrusergroupID
 * @property integer $employmentType
 * @property integer $isConsultant
 * @property integer $isTrainee
 * @property integer $is3rdParty
 * @property string $3rdPartyCompanyName
 * @property integer $gender
 * @property integer $designation
 * @property string $nationality
 * @property integer $isManager
 * @property integer $isApproval
 * @property integer $isDashBoard
 * @property integer $isAdmin
 * @property integer $isBasicUser
 * @property string $ActivationCode
 * @property integer $ActivationFlag
 * @property integer $isHR_admin
 * @property integer $isLock
 * @property boolean $basicDataIngCount
 * @property integer $opRptManagerAccess
 * @property integer $isSupportAdmin
 * @property integer $isHSEadmin
 * @property integer $excludeObjectivesYN
 * @property integer $machineID
 * @property string|\Carbon\Carbon $timestamp
 * @property integer $createdFrom
 * @property integer $isNewPortal
 * @property string $uuid
 */
class Employees extends Model
{

    public $table = 'employees';
    protected $primaryKey = 'employeeSystemID';
    const CREATED_AT = 'timestamp';
    const UPDATED_AT = 'timestamp';



    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'employeeSystemID' => 'integer',
        'empID' => 'string',
        'hrmsEmpID' => 'integer',
        'serial' => 'integer',
        'empLeadingText' => 'string',
        'empUserName' => 'string',
        'empTitle' => 'string',
        'empInitial' => 'string',
        'empName' => 'string',
        'empName_O' => 'string',
        'empFullName' => 'string',
        'empSurname' => 'string',
        'empSurname_O' => 'string',
        'empFirstName' => 'string',
        'empFirstName_O' => 'string',
        'empFamilyName' => 'string',
        'empFamilyName_O' => 'string',
        'empFatherName' => 'string',
        'empFatherName_O' => 'string',
        'empManagerAttached' => 'string',
        'empDateRegistered' => 'date',
        'empTelOffice' => 'string',
        'empTelMobile' => 'string',
        'empLandLineNo' => 'string',
        'extNo' => 'integer',
        'empFax' => 'string',
        'empEmail' => 'string',
        'empLocation' => 'integer',
        'empDateTerminated' => 'datetime',
        'empLoginActive' => 'integer',
        'empActive' => 'integer',
        'userGroupID' => 'integer',
        'empCompanySystemID' => 'integer',
        'empCompanyID' => 'string',
        'religion' => 'integer',
        'isLoggedIn' => 'integer',
        'isLoggedOutFailYN' => 'integer',
        'logingFlag' => 'integer',
        'isSuperAdmin' => 'integer',
        'discharegedYN' => 'integer',
        'isFinalSettlementDone' => 'integer',
        'hrusergroupID' => 'string',
        'employmentType' => 'integer',
        'isConsultant' => 'integer',
        'isTrainee' => 'integer',
        'is3rdParty' => 'integer',
        '3rdPartyCompanyName' => 'string',
        'gender' => 'integer',
        'designation' => 'integer',
        'nationality' => 'string',
        'isManager' => 'integer',
        'isApproval' => 'integer',
        'isDashBoard' => 'integer',
        'isAdmin' => 'integer',
        'isBasicUser' => 'integer',
        'ActivationCode' => 'string',
        'ActivationFlag' => 'integer',
        'isHR_admin' => 'integer',
        'isLock' => 'integer',
        'basicDataIngCount' => 'boolean',
        'opRptManagerAccess' => 'integer',
        'isSupportAdmin' => 'integer',
        'isHSEadmin' => 'integer',
        'excludeObjectivesYN' => 'integer',
        'machineID' => 'integer',
        'timestamp' => 'datetime',
        'createdFrom' => 'integer',
        'isNewPortal' => 'integer',
        'uuid' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'empID' => 'required|string|max:20',
        'hrmsEmpID' => 'nullable|integer',
        'serial' => 'nullable|integer',
        'empLeadingText' => 'nullable|string|max:3',
        'empUserName' => 'nullable|string|max:255',
        'empTitle' => 'nullable|string|max:100',
        'empInitial' => 'nullable|string|max:100',
        'empName' => 'nullable|string|max:500',
        'empName_O' => 'nullable|string|max:500',
        'empFullName' => 'nullable|string|max:500',
        'empSurname' => 'nullable|string|max:500',
        'empSurname_O' => 'nullable|string|max:500',
        'empFirstName' => 'nullable|string|max:500',
        'empFirstName_O' => 'nullable|string|max:500',
        'empFamilyName' => 'nullable|string|max:500',
        'empFamilyName_O' => 'nullable|string|max:500',
        'empFatherName' => 'nullable|string|max:500',
        'empFatherName_O' => 'nullable|string|max:500',
        'empManagerAttached' => 'nullable|string|max:100',
        'empDateRegistered' => 'nullable',
        'empTelOffice' => 'nullable|string|max:100',
        'empTelMobile' => 'nullable|string|max:100',
        'empLandLineNo' => 'nullable|string|max:100',
        'extNo' => 'nullable|integer',
        'empFax' => 'nullable|string|max:100',
        'empEmail' => 'nullable|string|max:255',
        'empLocation' => 'nullable|integer',
        'empDateTerminated' => 'nullable',
        'empLoginActive' => 'nullable|integer',
        'empActive' => 'nullable|integer',
        'userGroupID' => 'nullable|integer',
        'empCompanySystemID' => 'nullable|integer',
        'empCompanyID' => 'nullable|string|max:45',
        'religion' => 'nullable|integer',
        'isLoggedIn' => 'nullable|integer',
        'isLoggedOutFailYN' => 'nullable|integer',
        'logingFlag' => 'nullable|integer',
        'isSuperAdmin' => 'nullable|integer',
        'discharegedYN' => 'nullable|integer',
        'isFinalSettlementDone' => 'nullable|integer',
        'hrusergroupID' => 'nullable|string|max:45',
        'employmentType' => 'nullable|integer',
        'isConsultant' => 'nullable|integer',
        'isTrainee' => 'nullable|integer',
        'is3rdParty' => 'nullable|integer',
        '3rdPartyCompanyName' => 'nullable|string|max:500',
        'gender' => 'nullable|integer',
        'designation' => 'nullable|integer',
        'nationality' => 'nullable|string|max:20',
        'isManager' => 'nullable|integer',
        'isApproval' => 'nullable|integer',
        'isDashBoard' => 'nullable|integer',
        'isAdmin' => 'nullable|integer',
        'isBasicUser' => 'nullable|integer',
        'ActivationCode' => 'nullable|string|max:100',
        'ActivationFlag' => 'nullable|integer',
        'isHR_admin' => 'nullable|integer',
        'isLock' => 'nullable|integer',
        'basicDataIngCount' => 'nullable|boolean',
        'opRptManagerAccess' => 'nullable|integer',
        'isSupportAdmin' => 'nullable|integer',
        'isHSEadmin' => 'required|integer',
        'excludeObjectivesYN' => 'nullable|integer',
        'machineID' => 'nullable|integer',
        'timestamp' => 'nullable',
        'createdFrom' => 'nullable|integer',
        'isNewPortal' => 'nullable|integer',
        'uuid' => 'nullable|string|max:255'
    ];

    public function pulledContractUser(){
        return $this->belongsTo(ContractUsers::class, 'employeeSystemID', 'contractUserId');
    }


}
