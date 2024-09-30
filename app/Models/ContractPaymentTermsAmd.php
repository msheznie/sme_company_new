<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ContractPaymentTermsAmd
 * @package App\Models
 * @version September 25, 2024, 3:12 pm +04
 *
 * @property integer $id
 * @property integer $contract_history_id
 * @property string $uuid
 * @property integer $contract_id
 * @property string $title
 * @property string $description
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractPaymentTermsAmd extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'cm_payment_terms_amd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'amd_id';
    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'contract_history_id',
        'level_no',
        'uuid',
        'contract_id',
        'title',
        'description',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'amd_id' => 'integer',
        'contract_history_id' => 'integer',
        'level_no' => 'integer',
        'uuid' => 'string',
        'contract_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static function getContractPaymentTermsAmd($historyId, $fromApproval = false)
    {
        return self::where('contract_history_id', $historyId)
            ->when($fromApproval, function ($q)
            {
                $q->whereNotNull('id');
            })
            ->get();
    }
    public function getLevelNo($termId, $contractId)
    {
        $levelNo = self::where('id',$termId)
                ->where('contract_id', $contractId)
                ->max('level_no') + 1;

        return  max(1, $levelNo);
    }
    public static function getContractPaymentTermAmd($contractHistoryId, $amdUuid)
    {
        return self::where('uuid', $amdUuid)->where('contract_history_id', $contractHistoryId)->first();
    }
    public static function newContractPaymentTermsAmd($contractHistoryId)
    {
        return self::where('contract_history_id', $contractHistoryId)
            ->whereNull('id')
            ->get();
    }
    public static function checkUuidExists($uuid)
    {
        return self::where('uuid', $uuid)->exists();
    }
}
