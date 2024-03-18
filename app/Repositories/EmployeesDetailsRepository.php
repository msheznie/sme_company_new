<?php

namespace App\Repositories;

use App\Models\EmployeesDetails;
use App\Repositories\BaseRepository;

/**
 * Class EmployeesDetailsRepository
 * @package App\Repositories
 * @version March 6, 2024, 9:54 am +04
*/

class EmployeesDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return EmployeesDetails::class;
    }
}
