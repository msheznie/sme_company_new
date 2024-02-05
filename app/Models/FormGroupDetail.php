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
class FormGroupDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_group_id',
        'form_field_id',
        'sort',
        'status'
    ];

    public $timestamps = true;

    /**
     * get form field
     * @return BelongsTo
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'form_field_id', 'id')
            ->where('status', true)
            ->orderBy('sort');
    }

    /**
     * get form group
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(FormGroup::class)
            ->where('status', true)
            ->orderBy('sort');
    }
}
