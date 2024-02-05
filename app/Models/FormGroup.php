<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static insert(array $attributes = [])
 * @method static truncate()
 * @method public Builder update(array $values)
 */
class FormGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_section_id',
        'display_name',
        'name',
        'at_least',
        'sort',
        'status'
    ];

    public $timestamps = true;

    /**
     * get form section
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(FormSection::class);
    }


    /**
     * get form fields
     * NOTE: name as "controls" for special reason in frontend integration otherwise we can use "groupDetails" as standard
     * @return HasMany
     */
    public function controls(): HasMany
    {
        return $this->hasMany(FormGroupDetail::class)
            ->where('status', true)
            ->orderBy('sort');
    }
}
