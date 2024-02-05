<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * Class SupplierDetailHistory
 * @package App\Models
 * @version November 13, 2023, 6:50 am +04
 *
 * @property integer $user_id
 * @property integer $tenant_id
 * @property integer $form_section_id
 * @property integer $form_group_id
 * @property integer $form_field_id
 * @property integer $form_data_id
 * @property integer $sort
 * @property string $value
 * @property integer $status
 * @property integer $master_id
 */
class SupplierDetailHistory extends Model
{
    use HasFactory;

    public $table = 'supplier_details_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
 
    public $fillable = [
        'user_id',
        'tenant_id',
        'form_section_id',
        'form_group_id',
        'form_field_id',
        'form_data_id',
        'sort',
        'value',
        'status',
        'master_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'tenant_id' => 'integer',
        'form_section_id' => 'integer',
        'form_group_id' => 'integer',
        'form_field_id' => 'integer',
        'form_data_id' => 'integer',
        'sort' => 'integer',
        'value' => 'string',
        'status' => 'integer',
        'master_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|integer',
        'tenant_id' => 'required|integer',
        'form_section_id' => 'required|integer',
        'form_group_id' => 'required|integer',
        'form_field_id' => 'required|integer',
        'form_data_id' => 'nullable|integer',
        'sort' => 'required|integer',
        'value' => 'required|string',
        'status' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'master_id' => 'required'
    ];

    public function supplierDetail(){ 
        return $this->hasOne(SupplierDetail::class,  'form_field_id', 'form_field_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'form_field_id', 'id')->where('status', true);
    }
 
    public function group(): BelongsTo
    {
        return $this->belongsTo(FormGroup::class, 'form_group_id', 'id')->where('status', true);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(FormSection::class, 'form_section_id', 'id')->where('status', true);
    }

    public function options()
    {
        return $this->hasOne(FormData::class,'id','form_data_id')->where('status', true);
    }
    
}
