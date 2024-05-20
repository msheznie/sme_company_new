<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MilestoneStatusHistory
 * @package App\Models
 * @version May 17, 2024, 10:42 am +04
 *
 * @property string $uuid
 * @property integer $contractID
 * @property integer $milestoneID
 * @property integer $changedFrom
 * @property integer $changedTo
 * @property integer $companySystemID
 * @property integer $created_by
 * @property integer $updated_by
 */
class MilestoneStatusHistory extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'cm_milestone_status_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'contractID', 'milestoneID', 'created_by'];

    public $fillable = [
        'uuid',
        'contractID',
        'milestoneID',
        'changedFrom',
        'changedTo',
        'companySystemID',
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
        'contractID' => 'integer',
        'milestoneID' => 'integer',
        'changedFrom' => 'integer',
        'changedTo' => 'integer',
        'companySystemID' => 'integer',
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
    public function createdUser(){
        return $this->belongsTo(Employees::class, 'created_by', 'employeeSystemID');
    }
    public function milestoneDetail(){
        return $this->belongsTo(ContractMilestone::class, 'milestoneID', 'id');
    }
    public function getMilestoneStatusHistory($search, $milestoneID, $companySystemID) {
        $milestoneMasterID = $milestoneID['id'] ?? 0;
        $milestone = MilestoneStatusHistory::select('changedFrom', 'milestoneID', 'changedTo', 'created_by',
            'created_at')
            ->with([
               'createdUser' => function ($query) {
                    $query->select('employeeSystemID', 'empName');
               },
               'milestoneDetail' => function ($query) {
                   $query->select('id', 'title');
               }
            ])
            ->where('milestoneID', $milestoneMasterID)
            ->where('companySystemID', $companySystemID)
            ->orderBy('id', 'desc');
        if ($search) {
            $search = str_replace("\\", "\\\\", $search);
            $milestone = $milestone->where(function ($query) use ($search) {
                $query->whereHas('createdUser', function ($query3) use ($search) {
                    $query3->where('empName', 'LIKE', "%{$search}%");
                })->orWhereHas('milestoneDetail', function ($query3) use ($search) {
                    $query3->where('title', 'LIKE', "%{$search}%");
                });
            });
        }
        return $milestone;
    }

}
