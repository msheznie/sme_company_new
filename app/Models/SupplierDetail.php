<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed|string user_id
 * @property mixed|string tenant_id
 * @property mixed|string form_section_id
 * @property mixed|string form_group_id
 * @property mixed|string form_field_id
 * @property mixed|string form_data_id
 * @property mixed|string sort
 * @property mixed|string value
 * @property mixed|string status
 *
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static insert(array $attributes = [])
 * @method static truncate()
 * @method public Builder update(array $values)
 */
class SupplierDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tenant_id',
        'form_section_id',
        'form_group_id',
        'form_field_id',
        'form_data_id',
        'sort',
        'value',
        'status'
    ];

    public $timestamps = true;

    /**
     * get form field
     * @return BelongsTo
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'form_field_id', 'id')->where('status', true);
    }

    /**
     * get form group
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(FormGroup::class, 'form_group_id', 'id')->where('status', true);
    }

    /**
     * get form field
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(FormSection::class, 'form_section_id', 'id')->where('status', true);
    }

    /**
     * get form field
     * @return HasMany
     */
    public function data(): HasMany
    {
        return $this->hasMany(FormData::class, 'id', 'form_data_id')->where('status', true);
    }

    public function userTenant(): BelongsTo
    {
        return $this->belongsTo(UserTenant::class, 'tenant_id', 'tenant_id')->where('status', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function options()
    {
        return $this->hasOne(FormData::class,'id','form_data_id')->where('status', true);
    }
}
