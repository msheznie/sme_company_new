<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Users
 * @package App\Models
 * @version February 13, 2024, 6:08 pm +04
 *
 * @property integer $employee_id
 * @property string $empID
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $remember_token
 * @property string $login_token
 * @property string $uuid
 */
class Users extends Authenticatable
{

    use HasApiTokens, Notifiable;

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'employee_id',
        'empID',
        'name',
        'email',
        'username',
        'password',
        'remember_token',
        'login_token',
        'uuid'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'empID' => 'string',
        'name' => 'string',
        'email' => 'string',
        'username' => 'string',
        'password' => 'string',
        'remember_token' => 'string',
        'login_token' => 'string',
        'uuid' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'employee_id' => 'required',
        'empID' => 'required'
    ];

    public function employee()
    {
        return $this->hasOne(Employees::class,'employeeSystemID','employee_id');
    }

}
