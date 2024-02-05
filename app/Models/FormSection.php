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
class FormSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'display_name',
        'icon',
        'sort',
        'status'
    ];

    public $timestamps = true;

    /**
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(FormGroup::class)
            ->where('status', true)
            ->orderBy('sort');
    }
}
