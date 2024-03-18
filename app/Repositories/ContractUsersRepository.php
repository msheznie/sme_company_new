<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractUsers;
use App\Models\Employees;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\CrudOperations;

/**
 * Class ContractUsersRepository
 * @package App\Repositories
 * @version March 6, 2024, 10:12 am +04
*/

class ContractUsersRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;

    protected $fieldSearchable = [
        'contractUserId',
        'isActive',
        'companySystemId',
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
        return ContractUsers::class;
    }

    protected function getModel()
    {
        return new ContractUsers();
    }

    public function getContractUserList(Request $request) {
        $searchKeyword = $request->input('search.value');
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $filter = $input['filter'] ?? null;
        $contractUserList =  $this->model->getContractUserList($searchKeyword, $companyId, $filter);
        return DataTables::eloquent($contractUserList)
            ->addColumn('Actions', 'Actions', "Actions")
            ->order(function ($query) use ($input) {
                if (request()->has('order')) {
                    if ($input['order'][0]['column'] == 0) {
                        $query->orderBy('id', $input['order'][0]['dir']);
                    }
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function contractUserFormData($request) {
        $input = $request->all();
        $selectedUserType = $request->input('selectUserType');
        $selectedCompanyId = $request->input('selectedCompanyID');
        $searchKeyword = $request->input('search.value');

        if($selectedUserType == 1) {
            $contractUsers =  $this->model->getInternalUserList($selectedCompanyId, $searchKeyword);
        } else if($selectedUserType == 2) {
            $contractUsers =  $this->model->getSupplierUserList($selectedCompanyId, $searchKeyword);
        } else {
            $contractUsers =  $this->model->getCustomerUserList($selectedCompanyId, $searchKeyword);
        }

        return DataTables::eloquent($contractUsers)
            ->addColumn('Actions', 'Actions', "Actions")
            ->order(function ($query) use ($input) {
                if (request()->has('order')) {
                    if ($input['order'][0]['column'] == 0) {
                        $query->orderBy('id', $input['order'][0]['dir']);
                    }
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function storeContractUser($input) {
        $companySystemID = $input['selectedCompanyID'];
        $userType = $input['userType'];
        $userList = $input['userList'];
        $insertArray = [];

        DB::beginTransaction();
        try{

            foreach($userList as $user){
                $insertArray[] = [
                    'contractUserId' => $user['contractUserId'],
                    'contractUserType' => $userType,
                    'contractUserCode' => $user['contractUserCode'],
                    'contractUserName' => $user['contractUserName'],
                    'uuid' => bin2hex(random_bytes(16)),
                    'isActive' => 1,
                    'companySystemId' => $companySystemID,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];
            }

            $insertResponse = ContractUsers::insert($insertArray);
            if($insertResponse) {
                DB::commit();
                return ['status' => true, 'message' => trans('common.contract_users_successfully_pulled')];
            }

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }

    public function updateUser($input, $id){
        DB::beginTransaction();
        try{
            $insertResponse = ContractUsers::where('uuid', $id)->update(['isActive' => $input['isActive']]);
            if($insertResponse) {
                DB::commit();
                return ['status' => true, 'message' => trans('common.contract_user_updated_successfully')];
            }

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }
}
