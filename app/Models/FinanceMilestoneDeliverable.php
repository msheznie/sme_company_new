<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FinanceMilestoneDeliverable
 * @package App\Models
 * @version September 2, 2024, 9:06 am +04
 *
 * @property string $uuid
 * @property integer $finance_document_id
 * @property boolean $document_type
 * @property boolean $document
 * @property integer $master_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class FinanceMilestoneDeliverable extends Model
{
    use HasFactory;

    public $table = 'cm_finance_milestone_deliverables';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $hidden = ['id', 'finance_document_id', 'master_id'];

    public $fillable = [
        'uuid',
        'finance_document_id',
        'document_type',
        'document',
        'master_id',
        'company_id',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'finance_document_id' => 'integer',
        'document_type' => 'boolean',
        'document' => 'boolean',
        'master_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function milestone()
    {
        return $this->belongsTo(ContractMilestone::class, 'master_id', 'id');
    }
    public function deliverable()
    {
        return $this->belongsTo(ContractDeliverables::class, 'master_id', 'id');
    }
    public static function getExistsMilestoneDeliverables($financeDocumentId, $milestoneYN)
    {
        return FinanceMilestoneDeliverable::select('id', 'document_type', 'document', 'master_id')
            ->when($milestoneYN == 1, function ($q)
            {
                $q->where('document', 1);
            })
            ->when($milestoneYN == 0, function ($q)
            {
                $q->where('document', 2);
            })
            ->where('finance_document_id', $financeDocumentId)
            ->get();
    }
}
