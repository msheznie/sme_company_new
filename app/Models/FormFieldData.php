<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static truncate()
 * @method static create(array $attributes = [])
 * @method static insert(array $attributes = [])
 * @method public Builder update(array $values)
 */
class FormFieldData extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_field_id',
        'form_data_id',
        'status'
    ];

    public $timestamps = true;

    /**
     * get form field validators
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(FormData::class, 'form_data_id', 'id')->where('status', true);
    }
}
