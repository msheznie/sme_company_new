<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserTenant extends Model
{
    use HasFactory;

    public $table = 'user_tenant';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tenant_id',
        'company_id',
        'email',
        'status',
        'kyc_status',
        'is_bid_tender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'id', 'tenant_id')->select(['id', 'name']);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function supplierDetail(): HasMany
    {
        return $this->hasMany(SupplierDetail::class, 'tenant_id', 'tenant_id')->where('status', true);
    }
}
