<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class FormOptionMaster extends Model
{
    use HasFactory;

    public $table = 'form_option_masters';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'name'
    ];

    public function formOptionMasterDetails(): HasMany
    {
        return $this->hasMany(FormOptionDetails::class);
    }
}
