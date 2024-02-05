<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static insert(array $attributes = [])
 * @method static truncate()
 * @method public Builder update(array $values)
 */
class FormFieldValidator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_field_id',
        'form_validator_id',
        'value',
        'status'
    ];

    public $timestamps = true;

    /**
     * get form field validators
     * @return BelongsTo
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(FormValidator::class, 'form_validator_id', 'id')->where('status', true);
    }
}
