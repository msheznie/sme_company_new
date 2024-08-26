<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SupplierContactDetails
 * @package App\Models
 * @version August 26, 2024, 10:32 am +04
 *
 * @property integer $supplierID
 * @property integer $contactTypeID
 * @property string $contactPersonName
 * @property string $contactPersonTelephone
 * @property string $contactPersonFax
 * @property string $contactPersonEmail
 * @property integer $isDefault
 * @property string|\Carbon\Carbon $timestamp
 */
class SupplierContactDetails extends Model
{

    use HasFactory;

    public $table = 'suppliercontactdetails';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'supplierID',
        'contactTypeID',
        'contactPersonName',
        'contactPersonTelephone',
        'contactPersonFax',
        'contactPersonEmail',
        'isDefault',
        'timestamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'supplierContactID' => 'integer',
        'supplierID' => 'integer',
        'contactTypeID' => 'integer',
        'contactPersonName' => 'string',
        'contactPersonTelephone' => 'string',
        'contactPersonFax' => 'string',
        'contactPersonEmail' => 'string',
        'isDefault' => 'integer',
        'timestamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'supplierID' => 'nullable|integer',
        'contactTypeID' => 'nullable|integer',
        'contactPersonName' => 'nullable|string|max:200',
        'contactPersonTelephone' => 'nullable|string|max:200',
        'contactPersonFax' => 'nullable|string|max:20',
        'contactPersonEmail' => 'nullable|string|max:200',
        'isDefault' => 'nullable|integer',
        'timestamp' => 'nullable'
    ];


}
