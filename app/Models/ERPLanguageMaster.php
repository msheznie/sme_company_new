<?php

namespace App\Models;
use Eloquent as Model;

class ERPLanguageMaster extends Model
{

    public $table = 'srp_erp_lang_languages';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'systemDescription',
        'description',
        'languageShortCode',
        'languageSecShortCode',
        'isActive',
        'icon'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'languageID' => 'integer',
        'systemDescription' => 'string',
        'description' => 'string',
        'languageShortCode' => 'string',
        'languageSecShortCode' => 'string',
        'isActive' => 'integer',
        'icon' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static function getActiveERPLanguages()
    {
        return ERPLanguageMaster::select('languageID', 'systemDescription', 'description', 'languageShortCode',
            'languageSecShortCode', 'icon', 'isActive')
            ->where('isActive', 1)->get();
    }

    public function languages()
    {
        return $this->hasOne(EmployeesLanguage::class,  'languageID', 'languageID');
    }

}
