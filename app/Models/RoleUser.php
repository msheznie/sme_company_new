<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    protected $table = 'role_users';


    public function rolePermissions(){
        return $this->hasMany(RoleHasPermissions::class, 'role_id', 'role_id');
    }

    /**
     * user role
     * @return BelongsTo
     */
    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }
}

