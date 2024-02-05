<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static insert(array $attributes = [])
 * @method static truncate()
 * @method public Builder update(array $values)
 */
class FormValidator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'status'
    ];

    public $timestamps = true;
}
