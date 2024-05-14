<?php

namespace App\Repositories;

use App\Models\ContractUserAssign;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

/**
 * Class ContractUserAssignRepository
 * @package App\Repositories
 * @version May 13, 2024, 5:42 am +04
*/

class ContractUserAssignRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contractId',
        'userGroupId',
        'userId',
        'status',
        'createdBy',
        'updatedBy'
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
        return ContractUserAssign::class;
    }

    public function getAssignedUsers(Request $request) {
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $uuid =  $input['uuid'];
        $contractUserGroupList =  $this->model->getAssignedUsers($companyId, $uuid);
        return DataTables::eloquent($contractUserGroupList)
            ->addColumn('Actions', 'Actions', "Actions")
            ->order(function ($query) use ($input) {
                if (request()->has('order') && $input['order'][0]['column'] == 0) {
                    $query->orderBy('id', $input['order'][0]['dir']);
                }
            })
            ->addIndexColumn()
            ->make(true);
    }
}
