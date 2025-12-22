<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SupplierAssigned
 * @package App\Models
 * @version May 29, 2025, 3:12 pm +04
 *
 * @property integer $supplierCodeSytem
 * @property integer $companySystemID
 * @property string $companyID
 * @property string $uniqueTextcode
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
 * @property string $registrationExprity
 * @property integer $supplierImportanceID
 * @property integer $supplierNatureID
 * @property integer $supplierTypeID
 * @property integer $WHTApplicable
 * @property integer $vatEligible
 * @property string $vatNumber
 * @property integer $vatPercentage
 * @property integer $supCategoryICVMasterID
 * @property integer $supCategorySubICVID
 * @property integer $isLCCYN
 * @property integer $isMarkupPercentage
 * @property number $markupPercentage
 * @property integer $isRelatedPartyYN
 * @property integer $isCriticalYN
 * @property string $jsrsNo
 * @property string $jsrsExpiry
 * @property integer $isActive
 * @property integer $isAssigned
 * @property string|\Carbon\Carbon $timestamp
 * @property integer $isBlocked
 * @property integer $blockedBy
 * @property string|\Carbon\Carbon $blockedDate
 * @property string $blockedReason
 * @property integer $createdFrom
 * @property integer $advanceAccountSystemID
 * @property string $AdvanceAccount
 */
class SupplierAssigned extends Model
{
    use HasFactory;

    public $table = 'supplierassigned';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'supplierCodeSytem',
        'companySystemID',
        'companyID',
        'uniqueTextcode',
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
        'supplierImportanceID',
        'supplierNatureID',
        'supplierTypeID',
        'WHTApplicable',
        'vatEligible',
        'vatNumber',
        'vatPercentage',
        'supCategoryICVMasterID',
        'supCategorySubICVID',
        'isLCCYN',
        'isMarkupPercentage',
        'markupPercentage',
        'isRelatedPartyYN',
        'isCriticalYN',
        'jsrsNo',
        'jsrsExpiry',
        'isActive',
        'isAssigned',
        'timestamp',
        'isBlocked',
        'blockedBy',
        'blockedDate',
        'blockedReason',
        'createdFrom',
        'advanceAccountSystemID',
        'AdvanceAccount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'supplierAssignedID' => 'integer',
        'supplierCodeSytem' => 'integer',
        'companySystemID' => 'integer',
        'companyID' => 'string',
        'uniqueTextcode' => 'string',
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
        'registrationExprity' => 'string',
        'supplierImportanceID' => 'integer',
        'supplierNatureID' => 'integer',
        'supplierTypeID' => 'integer',
        'WHTApplicable' => 'integer',
        'vatEligible' => 'integer',
        'vatNumber' => 'string',
        'vatPercentage' => 'integer',
        'supCategoryICVMasterID' => 'integer',
        'supCategorySubICVID' => 'integer',
        'isLCCYN' => 'integer',
        'isMarkupPercentage' => 'integer',
        'markupPercentage' => 'float',
        'isRelatedPartyYN' => 'integer',
        'isCriticalYN' => 'integer',
        'jsrsNo' => 'string',
        'jsrsExpiry' => 'date',
        'isActive' => 'integer',
        'isAssigned' => 'integer',
        'timestamp' => 'datetime',
        'isBlocked' => 'integer',
        'blockedBy' => 'integer',
        'blockedDate' => 'datetime',
        'blockedReason' => 'string',
        'createdFrom' => 'integer',
        'advanceAccountSystemID' => 'integer',
        'AdvanceAccount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'supplierCodeSytem' => 'nullable|integer',
        'companySystemID' => 'nullable|integer',
        'companyID' => 'nullable|string|max:45',
        'uniqueTextcode' => 'nullable|string|max:45',
        'primarySupplierCode' => 'nullable|string|max:45',
        'secondarySupplierCode' => 'nullable|string|max:45',
        'supplierName' => 'nullable|string',
        'liabilityAccountSysemID' => 'nullable|integer',
        'liabilityAccount' => 'nullable|string|max:45',
        'UnbilledGRVAccountSystemID' => 'nullable|integer',
        'UnbilledGRVAccount' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'countryID' => 'nullable|integer',
        'supplierCountryID' => 'nullable|string|max:45',
        'telephone' => 'nullable|string|max:45',
        'fax' => 'nullable|string|max:45',
        'supEmail' => 'nullable|string',
        'webAddress' => 'nullable|string',
        'currency' => 'nullable|integer',
        'nameOnPaymentCheque' => 'nullable|string|max:500',
        'creditLimit' => 'nullable|numeric',
        'creditPeriod' => 'nullable|numeric',
        'supCategoryMasterID' => 'nullable|integer',
        'supCategorySubID' => 'nullable|integer',
        'supplier_category_id' => 'nullable|integer',
        'supplier_group_id' => 'nullable|integer',
        'registrationNumber' => 'nullable|string|max:45',
        'registrationExprity' => 'nullable|string|max:45',
        'supplierImportanceID' => 'nullable|integer',
        'supplierNatureID' => 'nullable|integer',
        'supplierTypeID' => 'nullable|integer',
        'WHTApplicable' => 'nullable|integer',
        'vatEligible' => 'nullable|integer',
        'vatNumber' => 'nullable|string|max:100',
        'vatPercentage' => 'nullable|integer',
        'supCategoryICVMasterID' => 'nullable|integer',
        'supCategorySubICVID' => 'nullable|integer',
        'isLCCYN' => 'nullable|integer',
        'isMarkupPercentage' => 'nullable|integer',
        'markupPercentage' => 'nullable|numeric',
        'isRelatedPartyYN' => 'nullable|integer',
        'isCriticalYN' => 'nullable|integer',
        'jsrsNo' => 'nullable|string|max:255',
        'jsrsExpiry' => 'nullable',
        'isActive' => 'nullable|integer',
        'isAssigned' => 'nullable|integer',
        'timestamp' => 'nullable',
        'isBlocked' => 'nullable|integer',
        'blockedBy' => 'nullable|integer',
        'blockedDate' => 'nullable',
        'blockedReason' => 'nullable|string',
        'createdFrom' => 'nullable|integer',
        'advanceAccountSystemID' => 'nullable|integer',
        'AdvanceAccount' => 'nullable|string|max:255'
    ];


}
