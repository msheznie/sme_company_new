<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class CMContractOverallRetentionAmd
 * @package App\Models
 * @version July 3, 2024, 3:33 pm +04
 *
 * @property integer $retention_id
 * @property string $uuid
 * @property integer $contractId
 * @property number $contractAmount
 * @property number $retentionPercentage
 * @property number $retentionAmount
 * @property string|\Carbon\Carbon $startDate
 * @property string|\Carbon\Carbon $dueDate
 * @property string $retentionWithholdPeriod
 * @property integer $companySystemId
 * @property integer $created_by
 * @property integer $updated_by
 */
class CMContractOverallRetentionAmd extends Model
{
    use HasFactory;

    public $table = 'cm_overall_retention_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'retention_id',
        'contract_history_id',
        'uuid',
        'contractId',
        'contractAmount',
        'retentionPercentage',
        'retentionAmount',
        'startDate',
        'dueDate',
        'retentionWithholdPeriod',
        'companySystemId',
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
        'retention_id' => 'integer',
        'contract_history_id' => 'integer',
        'uuid' => 'string',
        'contractId' => 'integer',
        'contractAmount' => 'float',
        'retentionPercentage' => 'float',
        'retentionAmount' => 'float',
        'startDate' => 'string',
        'dueDate' => 'string',
        'retentionWithholdPeriod' => 'string',
        'companySystemId' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function contract()
    {
        return $this->belongsTo(CMContractMasterAmd::class, 'contractId', 'id');
    }

}
