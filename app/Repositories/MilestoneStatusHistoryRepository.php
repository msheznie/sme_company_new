<?php

namespace App\Repositories;

use App\Models\ContractMilestone;
use App\Models\MilestoneStatusHistory;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Response;
use Yajra\DataTables\DataTables;

/**
 * Class MilestoneStatusHistoryRepository
 * @package App\Repositories
 * @version May 17, 2024, 10:42 am +04
*/

class MilestoneStatusHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MilestoneStatusHistory::class;
    }

   public function getMilestoneStatusHistory(Request $request){
       $input  = $request->all();
       $search_keyword = $request->input('search.value');
       $selectedCompanyID =  $input['selectedCompanyID'];
       $milestoneUuid = $input['milestoneUuid'] ?? 0;
       $milestoneMaster = self::getMilestoneID($milestoneUuid) ?? null;
       $languages =  $this->model->getMilestoneStatusHistory($search_keyword, $milestoneMaster, $selectedCompanyID);
       return DataTables::eloquent($languages)
           ->addColumn('Actions', 'Actions', "Actions")
           ->addIndexColumn()
           ->make(true);

   }
   public function getMilestoneID($milestoneUuid) {
        return ContractMilestone::select('id')->where('uuid', $milestoneUuid)->first();
   }
}
