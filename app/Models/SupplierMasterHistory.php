<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SupplierMasterHistory
 * @package App\Models
 * @version November 13, 2023, 6:47 am +04
 *
 * @property integer $edit_referred
 * @property string $ammend_comment
 * @property integer $user_id
 * @property integer $tenant_id
 */
class SupplierMasterHistory extends Model
{ 
    use HasFactory;

    public $table = 'supplier_master_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'edit_referred',
        'ammend_comment',
        'user_id',
        'tenant_id',
        'user_tenant_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'edit_referred' => 'integer',
        'ammend_comment' => 'string',
        'user_id' => 'integer',
        'tenant_id' => 'integer',
        'user_tenant_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'edit_referred' => 'required',
        'ammend_comment' => 'nullable|string|max:255',
        'user_id' => 'required|integer',
        'tenant_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function supplierHistoryHasDetails()
    {
        return $this->HasMany(SupplierDetailHistory::class,'master_id','id');
    } 
}
