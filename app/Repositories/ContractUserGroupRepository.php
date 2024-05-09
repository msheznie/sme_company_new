<?php

namespace App\Repositories;

use App\Models\ContractUserGroup;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

/**
 * Class ContractUserGroupRepository
 * @package App\Repositories
 * @version May 7, 2024, 10:59 am +04
*/

class ContractUserGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'groupName',
        'companySystemID',
        'status'
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
        return ContractUserGroup::class;
    }

    public function getContractUserGroupList(Request $request) {
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $contractUserGroupList =  $this->model->getContractUserGroupList($companyId);
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

    public function getContractUserGroupAssignedUsers(Request $request) {
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $selectUserGroup =  $input['selectUserGroup'];
        $contractUserGroupList = $this->model->getContractUserGroupAssignedUsers($companyId, $selectUserGroup);
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

    public function getContractUserListForUserGroup(Request $request) {
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $uuid =  $input['uuid'];
        $contractId = 0;
        if(isset($uuid) && $uuid !== 0){
            $result = ContractUserGroup::select('id')->where('uuid', $uuid)->first();
            $contractId = $result->id;
        }
        $contractUserList =  $this->model->getContractUserListForUserGroup($companyId, $contractId);
        return DataTables::eloquent($contractUserList)
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
