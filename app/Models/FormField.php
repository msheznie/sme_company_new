<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static insert(array $attributes = [])
 * @method static truncate()
 * @method public Builder update(array $values)
 */
class FormField extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'type',
        'placeholder',
        'class',
        'sort',
        'status',
        'data_from_tenant'
    ];

    public $timestamps = true;

    /**
     * get form field options
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(FormFieldData::class)->where('status', true);
    }

    /**
     * get form field validators
     * @return HasMany
     */
    public function validators(): HasMany
    {
        return $this->hasMany(FormFieldValidator::class)->where('status', true);
    }

    /**
     * get form field validators
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(SupplierDetail::class, 'form_field_id', 'id')->where('status', true);
    }
}
