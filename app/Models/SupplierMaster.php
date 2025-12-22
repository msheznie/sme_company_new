<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SupplierMaster
 * @package App\Models
 * @version March 8, 2024, 3:05 pm +04
 *
 * @property string $uniqueTextcode
 * @property integer $primaryCompanySystemID
 * @property string $primaryCompanyID
 * @property integer $documentSystemID
 * @property string $documentID
 * @property string $primarySupplierCode
 * @property string $secondarySupplierCode
 * @property string $supplierName
 * @property integer $liabilityAccountSysemID
 * @property string $liabilityAccount
 * @property integer $UnbilledGRVAccountSystemID
 * @property string $UnbilledGRVAccount
 * @property string $address
 * @property integer $countryID
 * @property string $supplierCountryID
 * @property string $telephone
 * @property string $fax
 * @property string $supEmail
 * @property string $webAddress
 * @property integer $currency
 * @property string $nameOnPaymentCheque
 * @property number $creditLimit
 * @property number $creditPeriod
 * @property integer $supCategoryMasterID
 * @property integer $supCategorySubID
 * @property integer $supplier_category_id
 * @property integer $supplier_group_id
 * @property string $registrationNumber
 * @property string|\Carbon\Carbon $registrationExprity
 * @property integer $approvedYN
 * @property integer $approvedEmpSystemID
 * @property string $approvedby
 * @property string|\Carbon\Carbon $approvedDate
 * @property string $approvedComment
 * @property integer $isActive
 * @property integer $isSupplierForiegn
 * @property integer $supplierConfirmedYN
 * @property string $supplierConfirmedEmpID
 * @property integer $supplierConfirmedEmpSystemID
 * @property string $supplierConfirmedEmpName
 * @property string|\Carbon\Carbon $supplierConfirmedDate
 * @property integer $isCriticalYN
 * @property integer $interCompanyYN
 * @property integer $companyLinkedToSystemID
 * @property string $companyLinkedTo
 * @property integer $linkCustomerYN
 * @property integer $linkCustomerID
 * @property string $createdUserGroup
 * @property string $createdPcID
 * @property string $createdUserID
 * @property string $modifiedPc
 * @property string $modifiedUser
 * @property string|\Carbon\Carbon $createdDateTime
 * @property integer $createdFrom
 * @property integer $isDirect
 * @property integer $supplierImportanceID
 * @property integer $supplierNatureID
 * @property integer $supplierTypeID
 * @property integer $WHTApplicable
 * @property integer $vatEligible
 * @property string $vatNumber
 * @property integer $vatPercentage
 * @property number $retentionPercentage
 * @property integer $supCategoryICVMasterID
 * @property integer $supCategorySubICVID
 * @property integer $isLCCYN
 * @property integer $isSMEYN
 * @property integer $isMarkupPercentage
 * @property number $markupPercentage
 * @property integer $RollLevForApp_curr
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property string $jsrsNo
 * @property string $jsrsExpiry
 * @property string|\Carbon\Carbon $timestamp
 * @property integer $createdUserSystemID
 * @property integer $modifiedUserSystemID
 * @property integer $isBlocked
 * @property integer $blockedBy
 * @property string|\Carbon\Carbon $blockedDate
 * @property string $blockedReason
 * @property string|\Carbon\Carbon $last_activity
 * @property integer $advanceAccountSystemID
 * @property string $AdvanceAccount
 */
class SupplierMaster extends Model
{
    public $table = 'suppliermaster';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = ['supplierCodeSystem'];

    public $fillable = [
        'uniqueTextcode',
        'primaryCompanySystemID',
        'primaryCompanyID',
        'documentSystemID',
        'documentID',
        'primarySupplierCode',
        'secondarySupplierCode',
        'supplierName',
        'liabilityAccountSysemID',
        'liabilityAccount',
        'UnbilledGRVAccountSystemID',
        'UnbilledGRVAccount',
        'address',
        'countryID',
        'supplierCountryID',
        'telephone',
        'fax',
        'supEmail',
        'webAddress',
        'currency',
        'nameOnPaymentCheque',
        'creditLimit',
        'creditPeriod',
        'supCategoryMasterID',
        'supCategorySubID',
        'supplier_category_id',
        'supplier_group_id',
        'registrationNumber',
        'registrationExprity',
        'approvedYN',
        'approvedEmpSystemID',
        'approvedby',
        'approvedDate',
        'approvedComment',
        'isActive',
        'isSupplierForiegn',
        'supplierConfirmedYN',
        'supplierConfirmedEmpID',
        'supplierConfirmedEmpSystemID',
        'supplierConfirmedEmpName',
        'supplierConfirmedDate',
        'isCriticalYN',
        'interCompanyYN',
        'companyLinkedToSystemID',
        'companyLinkedTo',
        'linkCustomerYN',
        'linkCustomerID',
        'createdUserGroup',
        'createdPcID',
        'createdUserID',
        'modifiedPc',
        'modifiedUser',
        'createdDateTime',
        'createdFrom',
        'isDirect',
        'supplierImportanceID',
        'supplierNatureID',
        'supplierTypeID',
        'WHTApplicable',
        'vatEligible',
        'vatNumber',
        'vatPercentage',
        'retentionPercentage',
        'supCategoryICVMasterID',
        'supCategorySubICVID',
        'isLCCYN',
        'isSMEYN',
        'isMarkupPercentage',
        'markupPercentage',
        'RollLevForApp_curr',
        'refferedBackYN',
        'timesReferred',
        'jsrsNo',
        'jsrsExpiry',
        'timestamp',
        'createdUserSystemID',
        'modifiedUserSystemID',
        'isBlocked',
        'blockedBy',
        'blockedDate',
        'blockedReason',
        'last_activity',
        'advanceAccountSystemID',
        'AdvanceAccount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'supplierCodeSystem' => 'integer',
        'uniqueTextcode' => 'string',
        'primaryCompanySystemID' => 'integer',
        'primaryCompanyID' => 'string',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'primarySupplierCode' => 'string',
        'secondarySupplierCode' => 'string',
        'supplierName' => 'string',
        'liabilityAccountSysemID' => 'integer',
        'liabilityAccount' => 'string',
        'UnbilledGRVAccountSystemID' => 'integer',
        'UnbilledGRVAccount' => 'string',
        'address' => 'string',
        'countryID' => 'integer',
        'supplierCountryID' => 'string',
        'telephone' => 'string',
        'fax' => 'string',
        'supEmail' => 'string',
        'webAddress' => 'string',
        'currency' => 'integer',
        'nameOnPaymentCheque' => 'string',
        'creditLimit' => 'float',
        'creditPeriod' => 'float',
        'supCategoryMasterID' => 'integer',
        'supCategorySubID' => 'integer',
        'supplier_category_id' => 'integer',
        'supplier_group_id' => 'integer',
        'registrationNumber' => 'string',
        'registrationExprity' => 'datetime',
        'approvedYN' => 'integer',
        'approvedEmpSystemID' => 'integer',
        'approvedby' => 'string',
        'approvedDate' => 'datetime',
        'approvedComment' => 'string',
        'isActive' => 'integer',
        'isSupplierForiegn' => 'integer',
        'supplierConfirmedYN' => 'integer',
        'supplierConfirmedEmpID' => 'string',
        'supplierConfirmedEmpSystemID' => 'integer',
        'supplierConfirmedEmpName' => 'string',
        'supplierConfirmedDate' => 'datetime',
        'isCriticalYN' => 'integer',
        'interCompanyYN' => 'integer',
        'companyLinkedToSystemID' => 'integer',
        'companyLinkedTo' => 'string',
        'linkCustomerYN' => 'integer',
        'linkCustomerID' => 'integer',
        'createdUserGroup' => 'string',
        'createdPcID' => 'string',
        'createdUserID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUser' => 'string',
        'createdDateTime' => 'datetime',
        'createdFrom' => 'integer',
        'isDirect' => 'integer',
        'supplierImportanceID' => 'integer',
        'supplierNatureID' => 'integer',
        'supplierTypeID' => 'integer',
        'WHTApplicable' => 'integer',
        'vatEligible' => 'integer',
        'vatNumber' => 'string',
        'vatPercentage' => 'integer',
        'retentionPercentage' => 'float',
        'supCategoryICVMasterID' => 'integer',
        'supCategorySubICVID' => 'integer',
        'isLCCYN' => 'integer',
        'isSMEYN' => 'integer',
        'isMarkupPercentage' => 'integer',
        'markupPercentage' => 'float',
        'RollLevForApp_curr' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'jsrsNo' => 'string',
        'jsrsExpiry' => 'date',
        'timestamp' => 'datetime',
        'createdUserSystemID' => 'integer',
        'modifiedUserSystemID' => 'integer',
        'isBlocked' => 'integer',
        'blockedBy' => 'integer',
        'blockedDate' => 'datetime',
        'blockedReason' => 'string',
        'last_activity' => 'datetime',
        'advanceAccountSystemID' => 'integer',
        'AdvanceAccount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uniqueTextcode' => 'nullable|string|max:45',
        'primaryCompanySystemID' => 'nullable|integer',
        'primaryCompanyID' => 'nullable|string|max:45',
        'documentSystemID' => 'nullable|integer',
        'documentID' => 'nullable|string|max:20',
        'primarySupplierCode' => 'nullable|string|max:50',
        'secondarySupplierCode' => 'nullable|string|max:50',
        'supplierName' => 'nullable|string',
        'liabilityAccountSysemID' => 'nullable|integer',
        'liabilityAccount' => 'nullable|string|max:20',
        'UnbilledGRVAccountSystemID' => 'nullable|integer',
        'UnbilledGRVAccount' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'countryID' => 'nullable|integer',
        'supplierCountryID' => 'nullable|string|max:5',
        'telephone' => 'nullable|string|max:50',
        'fax' => 'nullable|string|max:50',
        'supEmail' => 'nullable|string',
        'webAddress' => 'nullable|string',
        'currency' => 'nullable|integer',
        'nameOnPaymentCheque' => 'nullable|string|max:400',
        'creditLimit' => 'nullable|numeric',
        'creditPeriod' => 'nullable|numeric',
        'supCategoryMasterID' => 'nullable|integer',
        'supCategorySubID' => 'nullable|integer',
        'supplier_category_id' => 'nullable|integer',
        'supplier_group_id' => 'nullable|integer',
        'registrationNumber' => 'nullable|string|max:45',
        'registrationExprity' => 'nullable',
        'approvedYN' => 'nullable|integer',
        'approvedEmpSystemID' => 'nullable|integer',
        'approvedby' => 'nullable|string|max:20',
        'approvedDate' => 'nullable',
        'approvedComment' => 'nullable|string',
        'isActive' => 'nullable|integer',
        'isSupplierForiegn' => 'nullable|integer',
        'supplierConfirmedYN' => 'nullable|integer',
        'supplierConfirmedEmpID' => 'nullable|string|max:20',
        'supplierConfirmedEmpSystemID' => 'nullable|integer',
        'supplierConfirmedEmpName' => 'nullable|string|max:500',
        'supplierConfirmedDate' => 'nullable',
        'isCriticalYN' => 'nullable|integer',
        'interCompanyYN' => 'nullable|integer',
        'companyLinkedToSystemID' => 'nullable|integer',
        'companyLinkedTo' => 'nullable|string|max:45',
        'linkCustomerYN' => 'nullable|integer',
        'linkCustomerID' => 'nullable|integer',
        'createdUserGroup' => 'nullable|string|max:255',
        'createdPcID' => 'nullable|string|max:255',
        'createdUserID' => 'nullable|string|max:20',
        'modifiedPc' => 'nullable|string|max:255',
        'modifiedUser' => 'nullable|string|max:20',
        'createdDateTime' => 'nullable',
        'createdFrom' => 'nullable|integer',
        'isDirect' => 'nullable|integer',
        'supplierImportanceID' => 'nullable|integer',
        'supplierNatureID' => 'nullable|integer',
        'supplierTypeID' => 'nullable|integer',
        'WHTApplicable' => 'nullable|integer',
        'vatEligible' => 'nullable|integer',
        'vatNumber' => 'nullable|string|max:100',
        'vatPercentage' => 'nullable|integer',
        'retentionPercentage' => 'required|numeric',
        'supCategoryICVMasterID' => 'nullable|integer',
        'supCategorySubICVID' => 'nullable|integer',
        'isLCCYN' => 'nullable|integer',
        'isSMEYN' => 'nullable|integer',
        'isMarkupPercentage' => 'nullable|integer',
        'markupPercentage' => 'nullable|numeric',
        'RollLevForApp_curr' => 'nullable|integer',
        'refferedBackYN' => 'nullable|integer',
        'timesReferred' => 'nullable|integer',
        'jsrsNo' => 'nullable|string|max:255',
        'jsrsExpiry' => 'nullable',
        'timestamp' => 'nullable',
        'createdUserSystemID' => 'nullable|integer',
        'modifiedUserSystemID' => 'nullable|integer',
        'isBlocked' => 'nullable|integer',
        'blockedBy' => 'nullable|integer',
        'blockedDate' => 'nullable',
        'blockedReason' => 'nullable|string',
        'last_activity' => 'nullable',
        'advanceAccountSystemID' => 'nullable|integer',
        'AdvanceAccount' => 'nullable|string|max:255'
    ];

    public function pulledContractUser(){
        return $this->belongsTo(ContractUsers::class, 'supplierCodeSystem', 'contractUserId');
    }

    public function supplierContactDetails()
    {
        return $this->hasMany(SupplierContactDetails::class, 'supplierID', 'supplierCodeSystem');
    }

    public function assignedSuppliers(){
        return $this->belongsTo(SupplierAssigned::class, 'supplierCodeSystem', 'supplierCodeSytem');
    }

    public static function getSupplierBySupplierCodeSystem($id)
    {
        return self::select('supplierCodeSystem', 'supplierName', 'supEmail')
            ->where('supplierCodeSystem', $id)
            ->first();
    }

}
