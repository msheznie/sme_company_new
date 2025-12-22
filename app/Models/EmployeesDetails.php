<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmployeesDetails
 * @package App\Models
 * @version March 6, 2024, 9:54 am +04
 *
 * @property integer $serialNo
 * @property string $ECode
 * @property string $EmpSecondaryCode
 * @property integer $EmpTitleId
 * @property string $manPowerNo
 * @property string $ssoNo
 * @property string $EmpDesignationId
 * @property boolean $is_top_of_company
 * @property string $Ename1
 * @property string $Ename2
 * @property integer $AirportDestinationID
 * @property string $Ename3
 * @property string $Ename4
 * @property string $empSecondName
 * @property string $EFamilyName
 * @property string $initial
 * @property string $EmpShortCode
 * @property string $Enameother1
 * @property string $Enameother2
 * @property string $Enameother3
 * @property string $Enameother4
 * @property string $empSecondNameOther
 * @property string $EFamilyNameOther
 * @property string $empSignature
 * @property string $EmpImage
 * @property string $EthumbnailImage
 * @property string $Gender
 * @property integer $payee_emp_type
 * @property string $EpAddress1
 * @property string $EpAddress2
 * @property string $EpAddress3
 * @property string $EpAddress4
 * @property string $ZipCode
 * @property string $EpTelephone
 * @property string $EpFax
 * @property string $EpMobile
 * @property string $EcAddress1
 * @property string $EcAddress2
 * @property string $EcAddress3
 * @property string $EcAddress4
 * @property string $EcPOBox
 * @property string $EcPC
 * @property string $EcArea
 * @property string $EcTel
 * @property string $EcExtension
 * @property string $EcFax
 * @property string $EcMobile
 * @property string $EEmail
 * @property string $personalEmail
 * @property string $EDOB
 * @property string $EDOJ
 * @property string $NIC
 * @property string $insuranceNo
 * @property string $EPassportNO
 * @property string $EPassportExpiryDate
 * @property string $EVisaExpiryDate
 * @property integer $Nid
 * @property integer $Rid
 * @property string $AirportDestination
 * @property integer $travelFrequencyID
 * @property integer $commissionSchemeID
 * @property string $medicalInfo
 * @property integer $SchMasterId
 * @property integer $branchID
 * @property integer $userType
 * @property integer $isSystemUserYN
 * @property string $UserName
 * @property string $Password
 * @property integer $isDeleted
 * @property integer $HouseID
 * @property integer $HouseCatID
 * @property integer $HPID
 * @property integer $isPayrollEmployee
 * @property integer $payCurrencyID
 * @property string $payCurrency
 * @property integer $isLeft
 * @property string $DateLeft
 * @property string $LeftComment
 * @property integer $BloodGroup
 * @property string $DateAssumed
 * @property string $probationPeriod
 * @property integer $isDischarged
 * @property integer $dischargedByEmpID
 * @property integer $EmployeeConType
 * @property string $dischargedDate
 * @property string $lastWorkingDate
 * @property string $gratuityCalculationDate
 * @property integer $dischargeTypeID
 * @property integer $dischargeReasonID
 * @property string $dischargedComment
 * @property boolean $eligibleToRehire
 * @property integer $finalSettlementDoneYN
 * @property integer $MaritialStatus
 * @property integer $Nationality
 * @property integer $isLoginAttempt
 * @property integer $isChangePassword
 * @property integer $created_by
 * @property string $CreatedUserName
 * @property string|\Carbon\Carbon $CreatedDate
 * @property string $CreatedPC
 * @property integer $modified_by
 * @property string|\Carbon\Carbon $modified_at
 * @property string $ModifiedUserName
 * @property string|\Carbon\Carbon $Timestamp
 * @property string $ModifiedPC
 * @property integer $isActive
 * @property integer $NoOfLoginAttempt
 * @property integer $languageID
 * @property integer $locationID
 * @property integer $sponsorID
 * @property number $mobileCreditLimit
 * @property integer $segmentID
 * @property integer $Erp_companyID
 * @property integer $secondary_company_id
 * @property integer $insurance_category
 * @property string $insurance_code
 * @property string|\Carbon\Carbon $cover_from
 * @property string|\Carbon\Carbon $cover_to
 * @property integer $floorID
 * @property integer $deviceID
 * @property integer $empMachineID
 * @property integer $leaveGroupID
 * @property integer $isMobileCheckIn
 * @property integer $isCheckin
 * @property string $token
 * @property integer $overTimeGroup
 * @property integer $familyStatusID
 * @property integer $gratuityID
 * @property integer $isSystemAdmin
 * @property integer $isHRAdmin
 * @property string $contractStartDate
 * @property string $contractEndDate
 * @property string $contractRefNo
 * @property integer $confirmed_by
 * @property string $empConfirmDate
 * @property boolean $empConfirmedYN
 * @property string $rejoinDate
 * @property integer $previousEmpID
 * @property integer $gradeID
 * @property boolean $pos_userGroupMasterID
 * @property boolean $pos_userGroupMasterID_gpos
 * @property string $pos_barCode
 * @property integer $isLocalPosSyncEnable
 * @property integer $isLocalPosSalesRptEnable
 * @property integer $tibianType
 * @property string $LocalPOSUserType
 * @property string|\Carbon\Carbon $last_login
 * @property boolean $exclude_PASI
 */
class EmployeesDetails extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'srp_employeesdetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'serialNo',
        'ECode',
        'EmpSecondaryCode',
        'EmpTitleId',
        'manPowerNo',
        'ssoNo',
        'EmpDesignationId',
        'is_top_of_company',
        'Ename1',
        'Ename2',
        'AirportDestinationID',
        'Ename3',
        'Ename4',
        'empSecondName',
        'EFamilyName',
        'initial',
        'EmpShortCode',
        'Enameother1',
        'Enameother2',
        'Enameother3',
        'Enameother4',
        'empSecondNameOther',
        'EFamilyNameOther',
        'empSignature',
        'EmpImage',
        'EthumbnailImage',
        'Gender',
        'payee_emp_type',
        'EpAddress1',
        'EpAddress2',
        'EpAddress3',
        'EpAddress4',
        'ZipCode',
        'EpTelephone',
        'EpFax',
        'EpMobile',
        'EcAddress1',
        'EcAddress2',
        'EcAddress3',
        'EcAddress4',
        'EcPOBox',
        'EcPC',
        'EcArea',
        'EcTel',
        'EcExtension',
        'EcFax',
        'EcMobile',
        'EEmail',
        'personalEmail',
        'EDOB',
        'EDOJ',
        'NIC',
        'insuranceNo',
        'EPassportNO',
        'EPassportExpiryDate',
        'EVisaExpiryDate',
        'Nid',
        'Rid',
        'AirportDestination',
        'travelFrequencyID',
        'commissionSchemeID',
        'medicalInfo',
        'SchMasterId',
        'branchID',
        'userType',
        'isSystemUserYN',
        'UserName',
        'Password',
        'isDeleted',
        'HouseID',
        'HouseCatID',
        'HPID',
        'isPayrollEmployee',
        'payCurrencyID',
        'payCurrency',
        'isLeft',
        'DateLeft',
        'LeftComment',
        'BloodGroup',
        'DateAssumed',
        'probationPeriod',
        'isDischarged',
        'dischargedByEmpID',
        'EmployeeConType',
        'dischargedDate',
        'lastWorkingDate',
        'gratuityCalculationDate',
        'dischargeTypeID',
        'dischargeReasonID',
        'dischargedComment',
        'eligibleToRehire',
        'finalSettlementDoneYN',
        'MaritialStatus',
        'Nationality',
        'isLoginAttempt',
        'isChangePassword',
        'created_by',
        'CreatedUserName',
        'CreatedDate',
        'CreatedPC',
        'modified_by',
        'modified_at',
        'ModifiedUserName',
        'Timestamp',
        'ModifiedPC',
        'isActive',
        'NoOfLoginAttempt',
        'languageID',
        'locationID',
        'sponsorID',
        'mobileCreditLimit',
        'segmentID',
        'Erp_companyID',
        'secondary_company_id',
        'insurance_category',
        'insurance_code',
        'cover_from',
        'cover_to',
        'floorID',
        'deviceID',
        'empMachineID',
        'leaveGroupID',
        'isMobileCheckIn',
        'isCheckin',
        'token',
        'overTimeGroup',
        'familyStatusID',
        'gratuityID',
        'isSystemAdmin',
        'isHRAdmin',
        'contractStartDate',
        'contractEndDate',
        'contractRefNo',
        'confirmed_by',
        'empConfirmDate',
        'empConfirmedYN',
        'rejoinDate',
        'previousEmpID',
        'gradeID',
        'pos_userGroupMasterID',
        'pos_userGroupMasterID_gpos',
        'pos_barCode',
        'isLocalPosSyncEnable',
        'isLocalPosSalesRptEnable',
        'tibianType',
        'LocalPOSUserType',
        'last_login',
        'exclude_PASI'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'EIdNo' => 'integer',
        'serialNo' => 'integer',
        'ECode' => 'string',
        'EmpSecondaryCode' => 'string',
        'EmpTitleId' => 'integer',
        'manPowerNo' => 'string',
        'ssoNo' => 'string',
        'EmpDesignationId' => 'string',
        'is_top_of_company' => 'boolean',
        'Ename1' => 'string',
        'Ename2' => 'string',
        'AirportDestinationID' => 'integer',
        'Ename3' => 'string',
        'Ename4' => 'string',
        'empSecondName' => 'string',
        'EFamilyName' => 'string',
        'initial' => 'string',
        'EmpShortCode' => 'string',
        'Enameother1' => 'string',
        'Enameother2' => 'string',
        'Enameother3' => 'string',
        'Enameother4' => 'string',
        'empSecondNameOther' => 'string',
        'EFamilyNameOther' => 'string',
        'empSignature' => 'string',
        'EmpImage' => 'string',
        'EthumbnailImage' => 'string',
        'Gender' => 'string',
        'payee_emp_type' => 'integer',
        'EpAddress1' => 'string',
        'EpAddress2' => 'string',
        'EpAddress3' => 'string',
        'EpAddress4' => 'string',
        'ZipCode' => 'string',
        'EpTelephone' => 'string',
        'EpFax' => 'string',
        'EpMobile' => 'string',
        'EcAddress1' => 'string',
        'EcAddress2' => 'string',
        'EcAddress3' => 'string',
        'EcAddress4' => 'string',
        'EcPOBox' => 'string',
        'EcPC' => 'string',
        'EcArea' => 'string',
        'EcTel' => 'string',
        'EcExtension' => 'string',
        'EcFax' => 'string',
        'EcMobile' => 'string',
        'EEmail' => 'string',
        'personalEmail' => 'string',
        'EDOB' => 'date',
        'EDOJ' => 'date',
        'NIC' => 'string',
        'insuranceNo' => 'string',
        'EPassportNO' => 'string',
        'EPassportExpiryDate' => 'date',
        'EVisaExpiryDate' => 'date',
        'Nid' => 'integer',
        'Rid' => 'integer',
        'AirportDestination' => 'string',
        'travelFrequencyID' => 'integer',
        'commissionSchemeID' => 'integer',
        'medicalInfo' => 'string',
        'SchMasterId' => 'integer',
        'branchID' => 'integer',
        'userType' => 'integer',
        'isSystemUserYN' => 'integer',
        'UserName' => 'string',
        'Password' => 'string',
        'isDeleted' => 'integer',
        'HouseID' => 'integer',
        'HouseCatID' => 'integer',
        'HPID' => 'integer',
        'isPayrollEmployee' => 'integer',
        'payCurrencyID' => 'integer',
        'payCurrency' => 'string',
        'isLeft' => 'integer',
        'DateLeft' => 'date',
        'LeftComment' => 'string',
        'BloodGroup' => 'integer',
        'DateAssumed' => 'date',
        'probationPeriod' => 'date',
        'isDischarged' => 'integer',
        'dischargedByEmpID' => 'integer',
        'EmployeeConType' => 'integer',
        'dischargedDate' => 'date',
        'lastWorkingDate' => 'date',
        'gratuityCalculationDate' => 'date',
        'dischargeTypeID' => 'integer',
        'dischargeReasonID' => 'integer',
        'dischargedComment' => 'string',
        'eligibleToRehire' => 'boolean',
        'finalSettlementDoneYN' => 'integer',
        'MaritialStatus' => 'integer',
        'Nationality' => 'integer',
        'isLoginAttempt' => 'integer',
        'isChangePassword' => 'integer',
        'created_by' => 'integer',
        'CreatedUserName' => 'string',
        'CreatedDate' => 'datetime',
        'CreatedPC' => 'string',
        'modified_by' => 'integer',
        'modified_at' => 'datetime',
        'ModifiedUserName' => 'string',
        'Timestamp' => 'datetime',
        'ModifiedPC' => 'string',
        'isActive' => 'integer',
        'NoOfLoginAttempt' => 'integer',
        'languageID' => 'integer',
        'locationID' => 'integer',
        'sponsorID' => 'integer',
        'mobileCreditLimit' => 'float',
        'segmentID' => 'integer',
        'Erp_companyID' => 'integer',
        'secondary_company_id' => 'integer',
        'insurance_category' => 'integer',
        'insurance_code' => 'string',
        'cover_from' => 'datetime',
        'cover_to' => 'datetime',
        'floorID' => 'integer',
        'deviceID' => 'integer',
        'empMachineID' => 'integer',
        'leaveGroupID' => 'integer',
        'isMobileCheckIn' => 'integer',
        'isCheckin' => 'integer',
        'token' => 'string',
        'overTimeGroup' => 'integer',
        'familyStatusID' => 'integer',
        'gratuityID' => 'integer',
        'isSystemAdmin' => 'integer',
        'isHRAdmin' => 'integer',
        'contractStartDate' => 'date',
        'contractEndDate' => 'date',
        'contractRefNo' => 'string',
        'confirmed_by' => 'integer',
        'empConfirmDate' => 'date',
        'empConfirmedYN' => 'boolean',
        'rejoinDate' => 'date',
        'previousEmpID' => 'integer',
        'gradeID' => 'integer',
        'pos_userGroupMasterID' => 'boolean',
        'pos_userGroupMasterID_gpos' => 'boolean',
        'pos_barCode' => 'string',
        'isLocalPosSyncEnable' => 'integer',
        'isLocalPosSalesRptEnable' => 'integer',
        'tibianType' => 'integer',
        'LocalPOSUserType' => 'string',
        'last_login' => 'datetime',
        'exclude_PASI' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'serialNo' => 'nullable|integer',
        'ECode' => 'nullable|string|max:50',
        'EmpSecondaryCode' => 'nullable|string|max:30',
        'EmpTitleId' => 'nullable|integer',
        'manPowerNo' => 'nullable|string|max:50',
        'ssoNo' => 'nullable|string|max:20',
        'EmpDesignationId' => 'nullable|string|max:50',
        'is_top_of_company' => 'required|boolean',
        'Ename1' => 'nullable|string|max:200',
        'Ename2' => 'nullable|string|max:200',
        'AirportDestinationID' => 'nullable|integer',
        'Ename3' => 'nullable|string|max:200',
        'Ename4' => 'nullable|string|max:200',
        'empSecondName' => 'nullable|string|max:400',
        'EFamilyName' => 'nullable|string|max:400',
        'initial' => 'nullable|string|max:255',
        'EmpShortCode' => 'nullable|string|max:200',
        'Enameother1' => 'nullable|string|max:500',
        'Enameother2' => 'nullable|string|max:500',
        'Enameother3' => 'nullable|string|max:500',
        'Enameother4' => 'nullable|string|max:500',
        'empSecondNameOther' => 'nullable|string|max:500',
        'EFamilyNameOther' => 'nullable|string|max:500',
        'empSignature' => 'nullable|string|max:255',
        'EmpImage' => 'nullable|string|max:255',
        'EthumbnailImage' => 'nullable|string|max:255',
        'Gender' => 'nullable|string|max:1',
        'payee_emp_type' => 'nullable|integer',
        'EpAddress1' => 'nullable|string|max:50',
        'EpAddress2' => 'nullable|string|max:50',
        'EpAddress3' => 'nullable|string|max:50',
        'EpAddress4' => 'nullable|string|max:50',
        'ZipCode' => 'nullable|string|max:50',
        'EpTelephone' => 'nullable|string|max:50',
        'EpFax' => 'nullable|string|max:50',
        'EpMobile' => 'nullable|string|max:50',
        'EcAddress1' => 'nullable|string|max:255',
        'EcAddress2' => 'nullable|string|max:255',
        'EcAddress3' => 'nullable|string|max:255',
        'EcAddress4' => 'nullable|string|max:255',
        'EcPOBox' => 'nullable|string|max:50',
        'EcPC' => 'nullable|string|max:50',
        'EcArea' => 'nullable|string|max:50',
        'EcTel' => 'nullable|string|max:50',
        'EcExtension' => 'nullable|string|max:50',
        'EcFax' => 'nullable|string|max:50',
        'EcMobile' => 'nullable|string|max:50',
        'EEmail' => 'nullable|string|max:50',
        'personalEmail' => 'nullable|string|max:50',
        'EDOB' => 'nullable',
        'EDOJ' => 'nullable',
        'NIC' => 'nullable|string|max:255',
        'insuranceNo' => 'nullable|string|max:255',
        'EPassportNO' => 'nullable|string|max:50',
        'EPassportExpiryDate' => 'nullable',
        'EVisaExpiryDate' => 'nullable',
        'Nid' => 'nullable|integer',
        'Rid' => 'nullable|integer',
        'AirportDestination' => 'nullable|string|max:50',
        'travelFrequencyID' => 'nullable|integer',
        'commissionSchemeID' => 'nullable|integer',
        'medicalInfo' => 'nullable|string',
        'SchMasterId' => 'nullable|integer',
        'branchID' => 'nullable|integer',
        'userType' => 'nullable|integer',
        'isSystemUserYN' => 'nullable|integer',
        'UserName' => 'nullable|string|max:255',
        'Password' => 'nullable|string|max:255',
        'isDeleted' => 'nullable|integer',
        'HouseID' => 'nullable|integer',
        'HouseCatID' => 'nullable|integer',
        'HPID' => 'nullable|integer',
        'isPayrollEmployee' => 'nullable|integer',
        'payCurrencyID' => 'nullable|integer',
        'payCurrency' => 'nullable|string|max:5',
        'isLeft' => 'nullable|integer',
        'DateLeft' => 'nullable',
        'LeftComment' => 'nullable|string|max:45',
        'BloodGroup' => 'nullable|integer',
        'DateAssumed' => 'nullable',
        'probationPeriod' => 'nullable',
        'isDischarged' => 'nullable|integer',
        'dischargedByEmpID' => 'nullable|integer',
        'EmployeeConType' => 'nullable|integer',
        'dischargedDate' => 'nullable',
        'lastWorkingDate' => 'nullable',
        'gratuityCalculationDate' => 'nullable',
        'dischargeTypeID' => 'nullable|integer',
        'dischargeReasonID' => 'nullable|integer',
        'dischargedComment' => 'nullable|string|max:255',
        'eligibleToRehire' => 'nullable|boolean',
        'finalSettlementDoneYN' => 'nullable|integer',
        'MaritialStatus' => 'nullable|integer',
        'Nationality' => 'nullable|integer',
        'isLoginAttempt' => 'nullable|integer',
        'isChangePassword' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'CreatedUserName' => 'nullable|string|max:255',
        'CreatedDate' => 'nullable',
        'CreatedPC' => 'nullable|string|max:255',
        'modified_by' => 'nullable|integer',
        'modified_at' => 'nullable',
        'ModifiedUserName' => 'nullable|string|max:255',
        'Timestamp' => 'nullable',
        'ModifiedPC' => 'nullable|string|max:255',
        'isActive' => 'nullable|integer',
        'NoOfLoginAttempt' => 'nullable|integer',
        'languageID' => 'nullable|integer',
        'locationID' => 'nullable|integer',
        'sponsorID' => 'nullable|integer',
        'mobileCreditLimit' => 'nullable|numeric',
        'segmentID' => 'nullable|integer',
        'Erp_companyID' => 'nullable|integer',
        'secondary_company_id' => 'nullable|integer',
        'insurance_category' => 'nullable|integer',
        'insurance_code' => 'nullable|string',
        'cover_from' => 'nullable',
        'cover_to' => 'nullable',
        'floorID' => 'nullable|integer',
        'deviceID' => 'nullable|integer',
        'empMachineID' => 'nullable|integer',
        'leaveGroupID' => 'nullable|integer',
        'isMobileCheckIn' => 'nullable|integer',
        'isCheckin' => 'nullable|integer',
        'token' => 'nullable|string|max:400',
        'overTimeGroup' => 'nullable|integer',
        'familyStatusID' => 'nullable|integer',
        'gratuityID' => 'nullable|integer',
        'isSystemAdmin' => 'nullable|integer',
        'isHRAdmin' => 'nullable|integer',
        'contractStartDate' => 'nullable',
        'contractEndDate' => 'nullable',
        'contractRefNo' => 'nullable|string|max:255',
        'confirmed_by' => 'nullable|integer',
        'empConfirmDate' => 'nullable',
        'empConfirmedYN' => 'nullable|boolean',
        'rejoinDate' => 'nullable',
        'previousEmpID' => 'nullable|integer',
        'gradeID' => 'nullable|integer',
        'pos_userGroupMasterID' => 'nullable|boolean',
        'pos_userGroupMasterID_gpos' => 'nullable|boolean',
        'pos_barCode' => 'nullable|string|max:255',
        'isLocalPosSyncEnable' => 'nullable|integer',
        'isLocalPosSalesRptEnable' => 'nullable|integer',
        'tibianType' => 'nullable|integer',
        'LocalPOSUserType' => 'required|string',
        'last_login' => 'nullable',
        'exclude_PASI' => 'nullable|boolean'
    ];

    
}
