<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FcmToken
 * @package App\Models
 * @version September 12, 2024, 11:20 am +04
 *
 * @property integer $userID
 * @property string $fcm_token
 * @property string $deviceType
 */
class FcmToken extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'fcmtoken';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'userID',
        'fcm_token',
        'deviceType'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'userID' => 'integer',
        'fcm_token' => 'string',
        'deviceType' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'userID' => 'nullable|integer',
        'fcm_token' => 'nullable|string|max:255',
        'deviceType' => 'nullable|string|max:255'
    ];

    
}
