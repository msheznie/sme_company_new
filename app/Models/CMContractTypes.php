<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

/**
 * Class CMContractTypes
 * @package App\Models
 * @version February 22, 2024, 2:41 pm +04
 *
 * @property string $cm_type_name
 * @property integer $cmMaster_id
 * @property integer $cmIntent_id
 * @property integer $cmPartyA_id
 * @property integer $cmPartyB_id
 * @property integer $cmCounterParty_id
 * @property string $cm_type_description
 * @property boolean $ct_active
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class CMContractTypes extends Model
{
    use HasFactory;

    public $table = 'cm_contract_types';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = ['contract_typeId', 'cmParty_id', 'created_by'];

    public $fillable = [
        'uuid',
        'cm_type_name',
        'cmMaster_id',
        'cmIntent_id',
        'cmPartyA_id',
        'cmPartyB_id',
        'cmCounterParty_id',
        'cm_type_description',
        'ct_active',
        'companySystemID',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'contract_typeId' => 'integer',
        'cm_type_name' => 'string',
        'cmMaster_id' => 'integer',
        'cmIntent_id' => 'integer',
        'cmPartyA_id' => 'integer',
        'cmPartyB_id' => 'integer',
        'cmCounterParty_id' => 'integer',
        'cm_type_description' => 'string',
        'ct_active' => 'boolean',
        'companySystemID' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cm_type_name' => 'nullable|string|max:100',
        'cmMaster_id' => 'nullable|integer',
        'cmIntent_id' => 'nullable|integer',
        'cmPartyA_id' => 'nullable|integer',
        'cmPartyB_id' => 'nullable|integer',
        'cmCounterParty_id' => 'nullable|integer',
        'cm_type_description' => 'nullable|string',
        'ct_active' => 'required|boolean',
        'companySystemID' => 'nullable|integer',
        'created_by' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_by' => 'nullable|integer',
        'updated_at' => 'nullable'
    ];

    public function contratMasterWithtypes()
    {
        return $this->belongsTo(CMContractsMaster::class, 'cmMaster_id', 'cmMaster_id');
    }

    public function intentMasterWithtypes()
    {
        return $this->belongsTo(CMIntentsMaster::class, 'cmIntent_id', 'cmIntent_id');
    }

    public function partiesMasterWithtypes()
    {
        return $this->belongsTo(CMPartiesMaster::class, 'cmParty_id', 'cmParty_id');
    }

    public function counterPartiesWithtypes()
    {
        return $this->belongsTo(CMCounterPartiesMaster::class, 'cmCounterParty_id', 'cmCounterParty_id');
    }

    public function createdUserWithtypes()
    {
        return $this->belongsTo(Employees::class, 'created_by', 'employeeSystemID');
    }

    public function listOfContractTypes($search, $companyId, $filter)
    {
        $query = CMContractTypes::select
        ('uuid', 'cm_type_name', 'cmMaster_id', 'cmIntent_id', 'cmPartyA_id', 'cmPartyB_id',
            'cmCounterParty_id', 'cm_type_description', 'ct_active',
        'companySystemID', 'created_by', 'created_at')
        ->with(['contratMasterWithtypes' => function ($q) {
            $q->select('cmMaster_id', 'cmMaster_description');
        }, 'intentMasterWithtypes' => function ($q2) {
            $q2->select('cmIntent_id', 'cmIntent_detail');
        }, 'partiesMasterWithtypes' => function ($q3) {
            $q3->select('cmParty_id', 'cmParty_name');
        }, 'counterPartiesWithtypes' => function ($q4) {
            $q4->select('cmCounterParty_id', 'cmCounterParty_name');
        }, 'createdUserWithtypes' => function ($q5) {
            $q5->select('employeeSystemID', 'empName');
        }])->where('companySystemID', $companyId)
            ->orderBy('contract_typeId', 'desc');
        if ($filter) {
            if (isset($filter['catTypeID'])) {
                $query->where('cmMaster_id', $filter['catTypeID']);
            }
            if (isset($filter['intentTypeID'])) {
                $query->where('cmIntent_id', $filter['intentTypeID']);
            }
            if (isset($filter['is_status'])) {
                $query->where('ct_active', $filter['is_status']);
            }
        }
        if ($search) {
            $search = str_replace("\\", "\\\\", $search);
            $query = $query->where(function ($query) use ($search) {
                $query->orWhere('cm_type_name', 'LIKE', "%{$search}%");
                $query->orWhereHas('contratMasterWithtypes', function ($query1) use ($search) {
                    $query1->where('cmMaster_description', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('intentMasterWithtypes', function ($query2) use ($search) {
                    $query2->where('cmIntent_detail', 'LIKE', "%{$search}%");
                });
                $query->orWhereHas('createdUserWithtypes', function ($query3) use ($search) {
                    $query3->where('empName', 'LIKE', "%{$search}%");
                });
            });
        }
        return $query;
    }

    public static function getContractType($uuid)
    {
        return self::where('uuid',$uuid)->first();
    }
    public function partyA()
    {
        return $this->belongsTo(CMPartiesMaster::class, 'cmPartyA_id', 'cmParty_id');
    }
    public function partyB()
    {
        return $this->belongsTo(CMPartiesMaster::class, 'cmPartyB_id', 'cmParty_id');
    }

    public static function getContractTypeIdByName($contractType,$companyId)
    {
        $contractType = trim($contractType);

        return CMContractTypes::select('uuid')
            ->where('cm_type_name', $contractType)
            ->where('companySystemID', $companyId)
            ->first();
    }
}
