<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ThirdPartyIntegrationKeys
 * @package App\Models
 * @version July 24, 2024, 12:10 pm +04
 *
 * @property integer $company_id
 * @property integer $third_party_system_id
 * @property string $api_key
 * @property string $api_external_key
 * @property string $api_external_url
 * @property string|\Carbon\Carbon $expiryDate
 */
class ThirdPartyIntegrationKeys extends Model
{
    use HasFactory;

    public $table = 'third_party_integration_keys';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'company_id',
        'third_party_system_id',
        'api_key',
        'api_external_key',
        'api_external_url',
        'expiryDate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_id' => 'integer',
        'third_party_system_id' => 'integer',
        'api_key' => 'string',
        'api_external_key' => 'string',
        'api_external_url' => 'string',
        'expiryDate' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'company_id' => 'required|integer',
        'third_party_system_id' => 'required|integer',
        'api_key' => 'required|string|max:255',
        'api_external_key' => 'nullable|string|max:255',
        'api_external_url' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'expiryDate' => 'nullable'
    ];

    public static function getData($id, $value)
    {
       return self::where([
            ['third_party_system_id', '=', $id],
            ['api_key', '=', $value]
        ])->first();
    }
}
