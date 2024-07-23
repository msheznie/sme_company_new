<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Http\Resources\ContractUserGroupResource;
use App\Models\ContractMaster;
use App\Models\ContractUserAssign;
use App\Models\ContractUserGroup;
use App\Models\ContractUserGroupAssignedUser;
use App\Models\ContractUsers;
use App\Repositories\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        'isDefault',
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
            ->addIndexColumn()
            ->make(true);
    }

    public function getContractUserListForUserGroup(Request $request) {
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $uuid =  $input['uuid'];
        $contractId = 0;
        if(isset($uuid) && $uuid !== '0'){
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

    public function contractUserGroupList(Request $request){
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $contractuuid =  $input['contractuuid'];
        return $this->model->contractUserGroupList($companyId, $contractuuid);
    }

    public function getContractUserListToAssign(Request $request){
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $uuid =  $input['uuid'];
        $contractId = 0;
        if(isset($uuid) && $uuid !== 0){
            $result = ContractMaster::select('id')->where('uuid', $uuid)->first();
            $contractId = $result->id;
        }
        return $this->model->getContractUserListToAssign($companyId, $contractId);
    }

    public function getContractUserAssignedToUserGroup(Request $request){
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $uuid =  $input['uuid'];
        $userGroupId = 0;
        if(isset($uuid) && $uuid !== 0){
            $result = ContractUserGroup::select('id')->where('uuid', $uuid)->first();
            $userGroupId = $result->id;
        }
        return $this->model->getContractUserAssinedToUserGroup($companyId, $userGroupId);
    }

    public function createRecord($request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $uuid = $input['uuid'];

            // Handle group creation or fetching existing group
            $contractUserGroup = $this->handleGroup($input, $uuid);
            if (!$contractUserGroup['success']) {
                return $contractUserGroup;
            }
            $contractUserGroup = $contractUserGroup['data'];

            // Assign selected users to the group
            if (!empty($input['selectedUsers'])) {
                $this->assignUsersToGroup($input, $contractUserGroup);
            }

            DB::commit();
            return ['success' => true, 'data' => new ContractUserGroupResource($contractUserGroup)];

        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1062) {
                return ['success' => false, 'message' => trans('common.group_name_already_exists'), 'code' => 409];
            }

            return ['success' => false, 'message' => trans('common.database_error'), 'code' => 500];
        }
    }

    private function handleGroup($input, $uuid)
    {
        $result = ['success' => false, 'message' => '', 'code' => 400];

        if ($uuid === '0') {
            $isExist = ContractUserGroup::where('groupName', $input['groupName'])->exists();
            if ($isExist) {
                $result['message'] = trans('common.group_name_already_exists');
                $result['code'] = 409;
            } else {
                $input['uuid'] = bin2hex(random_bytes(16));
                $contractUserGroup = $this->create($input);
                $result['success'] = true;
                $result['data'] = $contractUserGroup;
            }
        } else {
            $contractUserGroup = ContractUserGroup::where('uuid', $uuid)->first();
            if (!$contractUserGroup) {
                $result['message'] = trans('common.group_not_found');
                $result['code'] = 404;
            } else {
                $assignedUserCount = ContractUserGroupAssignedUser::where('userGroupId', $contractUserGroup->id)
                    ->where('status', 1)
                    ->count();

                if ($input['isDefault'] && $assignedUserCount == 0) {
                    $result['message'] = trans('common.active_user_should_jn_default_user_group');
                } else {
                    $contractUserGroup->isDefault = $input['isDefault'];
                    $contractUserGroup->save();
                    $result['success'] = true;
                    $result['data'] = $contractUserGroup;
                }
            }
        }

        return $result;
    }


    private function assignUsersToGroup($input, $contractUserGroup)
    {
        foreach ($input['selectedUsers'] as $user) {
            $contractUserId = ContractUsers::where('uuid', $user['id'])->first();
            if (!$contractUserId) {
                continue;
            }

            $assignedUserInput = [
                'uuid' => bin2hex(random_bytes(16)),
                'userGroupId' => $contractUserGroup->id,
                'companySystemID' => $input['companySystemID'],
                'contractUserId' => $contractUserId->id,
                'giveAccessToExistingContracts' => $input['giveAccessToExistingContracts'],
                'created_by' => General::currentEmployeeId(),
                'updated_by' => null,
            ];

            ContractUserGroupAssignedUser::create($assignedUserInput);

            if ($input['giveAccessToExistingContracts'] && isset($input['update']) && $input['update']) {
                $this->assignExistingContracts($contractUserGroup, $contractUserId);
            }
        }
    }

    private function assignExistingContracts($contractUserGroup, $contractUserId)
    {
        $existingAssignedContractList = ContractUserAssign::select('contractId')
            ->where('userGroupId', $contractUserGroup->id)
            ->distinct()
            ->get();

        $contractIdArray = $existingAssignedContractList->pluck('contractId')->toArray();

        foreach ($contractIdArray as $contractId) {
            $newRecord = [
                'uuid' => bin2hex(random_bytes(16)),
                'contractId' => $contractId,
                'userGroupId' => $contractUserGroup->id,
                'userId' => $contractUserId->id,
                'status' => 1,
                'createdBy' => General::currentEmployeeId(),
                'updated_at' => null
            ];

            ContractUserAssign::create($newRecord);
        }
    }



}
