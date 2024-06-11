<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ErpDocumentMaster
 * @package App\Models
 * @version May 10, 2024, 3:22 pm +04
 *
 * @property string $documentID
 * @property string $documentDescription
 * @property integer $departmentSystemID
 * @property string $departmentID
 * @property boolean $isPrintable
 * @property string|\Carbon\Carbon $timeStamp
 */
class ErpDocumentMaster extends Model
{
    use HasFactory;

    public $table = 'erp_documentmaster';

    public $fillable = [
        'documentID',
        'documentDescription',
        'departmentSystemID',
        'departmentID',
        'isPrintable',
        'timeStamp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documentSystemID' => 'integer',
        'documentID' => 'string',
        'documentDescription' => 'string',
        'departmentSystemID' => 'integer',
        'departmentID' => 'string',
        'isPrintable' => 'boolean',
        'timeStamp' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'documentID' => 'required|string|max:20',
        'documentDescription' => 'nullable|string|max:100',
        'departmentSystemID' => 'nullable|integer',
        'departmentID' => 'nullable|string|max:10',
        'isPrintable' => 'required|boolean',
        'timeStamp' => 'nullable'
    ];

    public function documentMasterData($documentMasterID)
    {
        return ErpDocumentMaster::where('documentSystemID', $documentMasterID)->first();
    }

}
