<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractPaymentTerms
 * @package App\Models
 * @version July 2, 2024, 8:37 am +04
 *
 * @property string $uuid
 * @property integer $contractID
 * @property string $title
 * @property string $description
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class ContractPaymentTerms extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cm_payment_terms';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'contract_id',
        'title',
        'description',
        'company_id',
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
        'contract_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static function getContractPaymentTerms($contractID, $companySystemID)
    {
        return ContractPaymentTerms::select('uuid', 'title', 'description')
            ->where('contract_id', $contractID)
            ->where('company_id', $companySystemID)
            ->get();

    }

    public static function paymentTermExist($contractId, $companySystemID)
    {
        return ContractPaymentTerms::where('contract_id', $contractId)
            ->where('company_id', $companySystemID)
            ->first();
    }

    public function getPaymentTerms($contractId)
    {
        return self::where('contract_id', $contractId)->get();
    }
    public static function deletePaymentTermsFromAmd($amdRecordIds, $contractId)
    {
        ContractPaymentTerms::whereNotIn('id', $amdRecordIds)
            ->where('contract_id',$contractId)
            ->delete();
    }
    public static function checkUuidExists($uuid)
    {
        return self::where('uuid', $uuid)->exists();
    }
}
