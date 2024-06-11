<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public $table = 'cm_contract_deliverables';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';





    public $fillable = [
        'uuid',
        'contractID',
        'milestoneID',
        'description',
        'startDate',
        'endDate',
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
        'id' => 'integer',
        'uuid' => 'string',
        'contractID' => 'integer',
        'milestoneID' => 'integer',
        'description' => 'string',
        'startDate' => 'string',
        'endDate' => 'string',
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
        'description' => 'required|string|max:255',
        'startDate' => 'required',
        'endDate' => 'required',
        'companySystemID' => 'required|integer',
        'created_by' => 'required|integer',
        'updated_by' => 'required|integer',
        'deleted_at' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function milestone() {
        return $this->belongsTo(ContractMilestone::class, 'milestoneID', 'id');
    }
}
