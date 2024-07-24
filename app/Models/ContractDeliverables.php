<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;
/**
 * Class ContractDeliverables
 * @package App\Models
 * @version April 26, 2024, 2:39 pm +04
 *
 * @property string $uuid
 * @property integer $contractID
 * @property integer $milestoneID
 * @property string $description
 * @property string|\Carbon\Carbon $startDate
 * @property string|\Carbon\Carbon $endDate
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractDeliverables extends Model
{
    use HasFactory;
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_contract_deliverables';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';





    public $fillable = [
        'uuid',
        'contractID',
        'milestoneID',
        'title',
        'description',
        'companySystemID',
        'created_by',
        'updated_by',
        'dueDate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'contractID' => 'integer',
        'milestoneID' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'dueDate' => 'string',
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
        'uuid' => 'required|string|max:200',
        'contractID' => 'required|integer',
        'milestoneID' => 'required|integer',
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'companySystemID' => 'required|integer',
        'created_by' => 'required|integer',
        'updated_by' => 'required|integer',
        'deleted_at' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'dueDate' => 'string'
    ];

    public function milestone()
    {
        return $this->belongsTo(ContractMilestone::class, 'milestoneID', 'id');
    }

    public static function getContractIdColumn()
    {
        return 'contractID';
    }

    public static function getCompanyIdColumn()
    {
        return 'companySystemID';
    }

    public static function getDeliverables($contractID, $companySystemID)
    {
        return ContractDeliverables::select('uuid', 'milestoneID', 'title', 'description', 'dueDate')
            ->with([
                'milestone' => function ($q)
                {
                    $q->select('id', 'uuid', 'title');
                }
            ])
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->get();
    }
    public static function checkDeliverableExist($title, $description, $id, $companySystemID, $contractID)
    {
        return ContractDeliverables::where(function ($query) use ($title, $description, $contractID, $companySystemID) {
            $query->where('contractID', $contractID)
                ->where('companySystemID', $companySystemID)
                ->where(function ($q) use ($title, $description) {
                    $q->where('description', $description)
                        ->orWhere('title', $title);
                });
        })
            ->when($id > 0, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->exists();
    }
    public static function getContractDeliverable($contractID)
    {
        return self::where('contractID', $contractID)
            ->get();
    }

    public static function checkDeliverableAddedForContract($contractId, $companySystemID)
    {
        return ContractDeliverables::where('contractID', $contractId)
            ->where('companySystemID', $companySystemID)
            ->exists();
    }
}
