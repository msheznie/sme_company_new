<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CMContractMasterAmd
 * @package App\Models
 * @version July 1, 2024, 10:32 am +04
 *
 * @property integer $contract_history_id
 * @property string $uuid
 * @property string $contractCode
 * @property string $title
 * @property string $description
 * @property integer $contractType
 * @property integer $counterParty
 * @property integer $counterPartyName
 * @property string $referenceCode
 * @property integer $contractOwner
 * @property number $contractAmount
 * @property string|\Carbon\Carbon $startDate
 * @property string|\Carbon\Carbon $endDate
 * @property string|\Carbon\Carbon $agreementSignDate
 * @property integer $notifyDays
 * @property string $contractTermPeriod
 * @property string|\Carbon\Carbon $contractRenewalDate
 * @property string|\Carbon\Carbon $contractExtensionDate
 * @property string|\Carbon\Carbon $contractTerminateDate
 * @property string|\Carbon\Carbon $contractRevisionDate
 * @property string $primaryCounterParty
 * @property string $primaryEmail
 * @property string $primaryPhoneNumber
 * @property string $secondaryCounterParty
 * @property string $secondaryEmail
 * @property string $secondaryPhoneNumber
 * @property integer $documentMasterId
 * @property integer $status
 * @property integer $companySystemID
 * @property integer $confirmed_yn
 * @property string|\Carbon\Carbon $confirmed_date
 * @property integer $confirm_by
 * @property string $confirmed_comment
 * @property integer $rollLevelOrder
 * @property integer $refferedBackYN
 * @property integer $timesReferred
 * @property integer $approved_yn
 * @property integer $approved_by
 * @property string|\Carbon\Carbon $approved_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $is_amendment
 * @property integer $is_addendum
 * @property integer $is_renewal
 * @property integer $is_extension
 * @property integer $is_revision
 * @property integer $is_termination
 * @property integer $parent_id
 * @property integer $tender_id
 */
class CMContractMasterAmd extends Model
{

    use HasFactory;

    public $table = 'cm_contract_master_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'id',
        'contract_history_id',
        'level_no',
        'uuid',
        'contractCode',
        'title',
        'description',
        'contractType',
        'counterParty',
        'counterPartyName',
        'referenceCode',
        'contractOwner',
        'contractAmount',
        'startDate',
        'endDate',
        'agreementSignDate',
        'notifyDays',
        'contractTermPeriod',
        'contractRenewalDate',
        'contractExtensionDate',
        'contractTerminateDate',
        'contractRevisionDate',
        'primaryCounterParty',
        'primaryEmail',
        'primaryPhoneNumber',
        'secondaryCounterParty',
        'secondaryEmail',
        'secondaryPhoneNumber',
        'documentMasterId',
        'status',
        'companySystemID',
        'confirmed_yn',
        'confirmed_date',
        'confirm_by',
        'confirmed_comment',
        'rollLevelOrder',
        'refferedBackYN',
        'timesReferred',
        'approved_yn',
        'approved_by',
        'approved_date',
        'created_by',
        'updated_by',
        'is_amendment',
        'is_addendum',
        'is_renewal',
        'is_extension',
        'is_revision',
        'is_termination',
        'parent_id',
        'tender_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contract_history_id' => 'integer',
        'level_no' => 'integer',
        'uuid' => 'string',
        'contractCode' => 'string',
        'title' => 'string',
        'description' => 'string',
        'contractType' => 'integer',
        'counterParty' => 'integer',
        'counterPartyName' => 'integer',
        'referenceCode' => 'string',
        'contractOwner' => 'integer',
        'contractAmount' => 'float',
        'startDate' => 'string',
        'endDate' => 'string',
        'agreementSignDate' => 'string',
        'notifyDays' => 'integer',
        'contractTermPeriod' => 'string',
        'contractRenewalDate' => 'string',
        'contractExtensionDate' => 'string',
        'contractTerminateDate' => 'string',
        'contractRevisionDate' => 'string',
        'primaryCounterParty' => 'string',
        'primaryEmail' => 'string',
        'primaryPhoneNumber' => 'string',
        'secondaryCounterParty' => 'string',
        'secondaryEmail' => 'string',
        'secondaryPhoneNumber' => 'string',
        'documentMasterId' => 'integer',
        'status' => 'integer',
        'companySystemID' => 'integer',
        'confirmed_yn' => 'integer',
        'confirmed_date' => 'datetime',
        'confirm_by' => 'integer',
        'confirmed_comment' => 'string',
        'rollLevelOrder' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'approved_yn' => 'integer',
        'approved_by' => 'integer',
        'approved_date' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'is_amendment' => 'integer',
        'is_addendum' => 'integer',
        'is_renewal' => 'integer',
        'is_extension' => 'integer',
        'is_revision' => 'integer',
        'is_termination' => 'integer',
        'parent_id' => 'integer',
        'tender_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public static function getContractMasterData($id)
    {
        return CMContractMasterAmd::select('uuid', 'contractCode', 'title', 'contractType',
        'counterParty', 'counterPartyName', 'referenceCode',
        'startDate', 'endDate', 'status', 'contractOwner', 'contractAmount', 'description',
        'primaryCounterParty', 'primaryEmail', 'primaryPhoneNumber', 'secondaryCounterParty',
        'secondaryEmail', 'secondaryPhoneNumber', 'agreementSignDate', 'startDate', 'endDate',
        'notifyDays', 'contractTermPeriod','is_amendment','is_addendum','is_renewal','is_extension',
        'is_revision','is_termination','parent_id', 'confirmed_yn', 'approved_yn', 'refferedBackYN', 'tender_id')
            ->with(["contractTypes" => function ($query)
            {
             $query->select('contract_typeId', 'uuid', 'cm_type_name');
            }])
        ->where('contract_history_id',$id)
        ->first();
    }


    public function contractTypes()
    {
        return $this->belongsTo(CMContractTypes::class, 'contractType', 'contract_typeId');
    }
    public function contractUsers()
    {
        return $this->belongsTo(ContractUsers::class, 'counterPartyName', 'id');
    }

    public function contractOwners()
    {
        return $this->belongsTo(ContractUsers::class, 'contractOwner', 'id');
    }

    public function getLevelNo($contractId)
    {
       $levelNo = self::where('id',$contractId)
            ->max('level_no') + 1;

        return  max(1, $levelNo);
    }

    public static function getContractIdColumn()
    {
        return 'id';
    }

    public static function getCompanyIdColumn()
    {
        return 'companySystemID';
    }
}
