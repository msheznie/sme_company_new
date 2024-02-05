<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormOptionDetails extends Model
{
    use HasFactory;

    public $table = 'form_option_details';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'form_option_master_id',
        'name'
    ];
}
