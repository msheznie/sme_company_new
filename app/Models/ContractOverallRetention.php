<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasContractIdColumn;
use App\Traits\HasCompanyIdColumn;
/**
 * Class ContractOverallRetention
 * @package App\Models
 * @version April 22, 2024, 4:09 pm +04
 *
 * @property string $uuid
 * @property integer $contractId
 * @property number $contractAmount
 * @property number $retentionPercentage
 * @property number $retentionAmount
 * @property string|\Carbon\Carbon $startDate
 * @property string|\Carbon\Carbon $dueDate
 * @property string|\Carbon\Carbon $retentionWithholdPeriod
 * @property integer $companySystemId
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractOverallRetention extends Model
{


    use HasFactory;
    use HasContractIdColumn;
    use HasCompanyIdColumn;

    public $table = 'cm_overall_retention';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
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
        return $this->belongsTo(ContractMaster::class, 'contractId', 'id');
    }

    public static function getContractIdColumn()
    {
        return 'contractId';
    }

    public static function getCompanyIdColumn()
    {
        return 'companySystemId';
    }

}
