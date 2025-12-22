<?php
/**
 * =============================================
 * -- File Name : ItemMaster.php
 * -- Project Name : ERP
 * -- Module Name :  Item Master
 * -- Author : Jayan Anuranga
 * -- Create date : 26- April 2024
 * -- Description : This file is used to interact with database table and it contains relationships to the tables.
 * -- REVISION HISTORY
 */
namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ItemMaster
 * @package App\Models
 * @version 26 - April 2024, 10:35 am UTC
 *
 * @property string primaryItemCode
 * @property integer runningSerialOrder
 * @property integer documentSystemID
 * @property string documentID
 * @property integer primaryCompanySystemID
 * @property string primaryCompanyID
 * @property string primaryCode
 * @property string secondaryItemCode
 * @property string barcode
 * @property string itemDescription
 * @property string itemShortDescription
 * @property string itemUrl
 * @property integer unit
 * @property integer financeCategoryMaster
 * @property integer financeCategorySub
 * @property string itemPicture
 * @property integer selectedForAssign
 * @property integer isActive
 * @property integer sentConfirmationEmail
 * @property string confirmationEmailSentByEmpID
 * @property string confirmationEmailSentByEmpName
 * @property integer itemConfirmedYN
 * @property string itemConfirmedByEMPID
 * @property string itemConfirmedByEMPName
 * @property string|\Carbon\Carbon itemConfirmedDate
 * @property string itemApprovedBy
 * @property integer itemApprovedYN
 * @property string|\Carbon\Carbon itemApprovedDate
 * @property string itemApprovedComment
 * @property string createdUserGroup
 * @property string createdPcID
 * @property string createdUserID
 * @property string modifiedPc
 * @property string modifiedUser
 * @property string|\Carbon\Carbon createdDateTime
 * @property string|\Carbon\Carbon timestamp
 */
class ItemMaster extends Model
{
    //use SoftDeletes;

    public $table = 'itemmaster';

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'timeStamp';
    protected $primaryKey  = 'itemCodeSystem';
    protected $hidden = ['itemCodeSystem'];


    protected $dates = ['deleted_at'];


    public $fillable = [
        'primaryItemCode',
        'runningSerialOrder',
        'documentSystemID',
        'documentID',
        'primaryCompanySystemID',
        'primaryCompanyID',
        'primaryCode',
        'secondaryItemCode',
        'barcode',
        'itemDescription',
        'itemShortDescription',
        'itemUrl',
        'unit',
        'financeCategoryMaster',
        'financeCategorySub',
        'faFinanceCatID',
        'itemPicture',
        'selectedForAssign',
        'isActive',
        'isSubItem',
        'mainItemID',
        'RollLevForApp_curr',
        'sentConfirmationEmail',
        'confirmationEmailSentByEmpID',
        'confirmationEmailSentByEmpName',
        'itemConfirmedYN',
        'itemConfirmedByEMPSystemID',
        'itemConfirmedByEMPID',
        'itemConfirmedByEMPName',
        'itemConfirmedDate',
        'itemApprovedBySystemID',
        'itemApprovedBy',
        'itemApprovedYN',
        'itemApprovedDate',
        'itemApprovedComment',
        'createdUserGroup',
        'createdPcID',
        'createdUserID',
        'modifiedPc',
        'modifiedUser',
        'createdDateTime',
        'timestamp',
        'createdUserSystemID',
        'modifiedUserSystemID',
        'refferedBackYN',
        'timesReferred',
        'isPOSItem',
        'vatSubCategory',
        'expiryYN',
        'faCatID',
        'trackingType',
        'faSubCatID',
        'faSubCatID2',
        'faSubCatID3',
        'pos_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'itemCodeSystem' => 'integer',
        'primaryItemCode' => 'string',
        'runningSerialOrder' => 'integer',
        'trackingType' => 'integer',
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'primaryCompanySystemID' => 'integer',
        'primaryCompanyID' => 'string',
        'primaryCode' => 'string',
        'secondaryItemCode' => 'string',
        'barcode' => 'string',
        'itemDescription' => 'string',
        'itemShortDescription' => 'string',
        'itemUrl' => 'string',
        'unit' => 'integer',
        'financeCategoryMaster' => 'integer',
        'financeCategorySub' => 'integer',
        'faFinanceCatID' => 'integer',
        'itemPicture' => 'string',
        'selectedForAssign' => 'integer',
        'isActive' => 'integer',
        'RollLevForApp_curr' => 'integer',
        'sentConfirmationEmail' => 'integer',
        'confirmationEmailSentByEmpID' => 'string',
        'confirmationEmailSentByEmpName' => 'string',
        'itemConfirmedYN' => 'integer',
        'itemConfirmedByEMPSystemID'  => 'integer',
        'itemConfirmedByEMPID' => 'string',
        'itemConfirmedByEMPName' => 'string',
        'itemApprovedBySystemID' => 'integer',
        'itemApprovedBy' => 'string',
        'itemApprovedYN' => 'integer',
        'itemApprovedComment' => 'string',
        'createdUserGroup' => 'string',
        'createdPcID' => 'string',
        'createdUserID' => 'string',
        'modifiedPc' => 'string',
        'modifiedUser' => 'string',
        'createdUserSystemID' => 'integer',
        'modifiedUserSystemID' => 'integer',
        'refferedBackYN' => 'integer',
        'timesReferred' => 'integer',
        'isPOSItem' => 'integer',
        'vatSubCategory'=>'integer',
        'expiryYN'=>'integer',
        'faCatID'=>'integer',
        'faSubCatID'=>'integer',
        'faSubCatID2'=>'integer',
        'faSubCatID3'=>'integer',
        'pos_type' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public function itemAssigned(){
        return $this->belongsTo('App\Models\ItemAssigned','itemCodeSystem','itemCodeSystem');
    }

    public function unit(){
        return $this->hasOne(Unit::class,'UnitID','unit');
    }
}
