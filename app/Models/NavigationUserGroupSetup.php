<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class NavigationUserGroupSetup
 * @package App\Models
 * @version February 19, 2024, 2:14 pm +04
 *
 * @property integer $userGroupID
 * @property integer $companyID
 * @property integer $navigationMenuID
 * @property string $description
 * @property integer $masterID
 * @property string $url
 * @property string $pageID
 * @property string $pageTitle
 * @property string $pageIcon
 * @property integer $levelNo
 * @property integer $sortOrder
 * @property integer $isSubExist
 * @property boolean $readonly
 * @property boolean $create
 * @property boolean $update
 * @property boolean $delete
 * @property boolean $print
 * @property boolean $export
 * @property string|\Carbon\Carbon $timestamp
 * @property integer $isPortalYN
 * @property string $externalLink
 */
class NavigationUserGroupSetup extends Model
{

    public $table = 'srp_erp_navigationusergroupsetup';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'userGroupID',
        'companyID',
        'navigationMenuID',
        'description',
        'masterID',
        'url',
        'pageID',
        'pageTitle',
        'pageIcon',
        'levelNo',
        'sortOrder',
        'isSubExist',
        'readonly',
        'create',
        'update',
        'delete',
        'print',
        'export',
        'timestamp',
        'isPortalYN',
        'externalLink'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'userGroupID' => 'integer',
        'companyID' => 'integer',
        'navigationMenuID' => 'integer',
        'description' => 'string',
        'masterID' => 'integer',
        'url' => 'string',
        'pageID' => 'string',
        'pageTitle' => 'string',
        'pageIcon' => 'string',
        'levelNo' => 'integer',
        'sortOrder' => 'integer',
        'isSubExist' => 'integer',
        'readonly' => 'boolean',
        'create' => 'boolean',
        'update' => 'boolean',
        'delete' => 'boolean',
        'print' => 'boolean',
        'export' => 'boolean',
        'timestamp' => 'datetime',
        'isPortalYN' => 'integer',
        'externalLink' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'userGroupID' => 'nullable|integer',
        'companyID' => 'nullable|integer',
        'navigationMenuID' => 'nullable|integer',
        'description' => 'nullable|string|max:255',
        'masterID' => 'nullable|integer',
        'url' => 'nullable|string',
        'pageID' => 'nullable|string|max:255',
        'pageTitle' => 'nullable|string|max:255',
        'pageIcon' => 'nullable|string|max:255',
        'levelNo' => 'nullable|integer',
        'sortOrder' => 'nullable|integer',
        'isSubExist' => 'nullable|integer',
        'readonly' => 'nullable|boolean',
        'create' => 'nullable|boolean',
        'update' => 'nullable|boolean',
        'delete' => 'nullable|boolean',
        'print' => 'nullable|boolean',
        'export' => 'nullable|boolean',
        'timestamp' => 'nullable',
        'isPortalYN' => 'nullable|integer',
        'externalLink' => 'nullable|string'
    ];

    public function parent()
    {
        return $this->belongsTo(NavigationUserGroupSetup::class,'UserGroupSetupID');
    }
    public function child()
    {
        return $this->hasMany(NavigationUserGroupSetup::class,'masterID','navigationMenuID');
    }
    public function navigationFormat(){
        return [
            'id' => $this->navigationMenuID,
            'path' => $this->url ? $this->url : '/dashboard',
            'title' => $this->description,
            'icon' => "home",
            'type' => count($this->child) > 0 ? 'sub' : 'link',
            'badgeType' => count($this->child) > 0 ? 'primary' : '',
            'parent_id' => $this->masterID,
            'badgeValue' => '', // new
            'active' => false,
            'bookmark' => false,
            'order_index' => $this->sortOrder,
            'children' => $this->child->transform(function ($data){
                return $data->navigationFormat();
            })
        ];
    }
    public function userMenusByCompany($companyId,$userGroupId)
    {
        return NavigationUserGroupSetup::where('masterID', NULL)
            ->where('isPortalYN', 5)
            ->where('userGroupID', $userGroupId)
            ->where('companyID', $companyId)
            ->with(['child' => function ($query) use ($companyId, $userGroupId) {
                $query->where('userGroupID', $userGroupId)
                    ->where('companyID', $companyId)
                    ->orderBy("sortOrder", "asc");
            },'child.child' => function ($query) use ($companyId, $userGroupId) {
                $query->where('userGroupID', $userGroupId)
                    ->where('companyID', $companyId)
                    ->orderBy("sortOrder", "asc");
            }])
            ->orderBy("sortOrder", "asc")
            ->get()
            ->transform(function ($data) {
                return $data->navigationFormat();
            });
    }

}
