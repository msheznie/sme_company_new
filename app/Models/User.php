<?php

namespace App\Models;

use App\Notifications\sendPasswordResetNotificationQueued;
use App\Notifications\VerifyEmailQueued;
use App\Services\Supplier\SupplierService;
use App\Services\User\UserService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable  implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'email_verified_at',
        'password',
        'registration_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot() {

        parent::boot();

        static::creating(function($user) { });

        static::created(function($user) {
            // add default roles when created new user
            $userService = new UserService();
            $userService->addDefaultRoleToUser($user);
        });

        static::updating(function($user) { });

        static::updated(function($user) { });

        static::deleted(function($user) { });
    }

    public function fetchUserRole()
    {
        return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }


    /**
     * send email verification mail
     */

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued());
    }

    /**
     * send password reset mail
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new sendPasswordResetNotificationQueued($token));
    }

    public function supplierDetail(): HasMany
    {
        return $this->hasMany(SupplierDetail::class, 'user_id', 'id');
    }

    public function userTenant(): HasMany
    {
        return $this->hasMany(UserTenant::class, 'tenant_id', 'id');
    }
}
