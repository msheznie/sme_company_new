<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractMaster;
use App\Models\ContractUserAssign;
use App\Models\ContractUserGroupAssignedUser;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
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
            ->addIndexColumn()
            ->make(true);
    }

    public function createRecord($input)
    {
        $contractResult = ContractMaster::select()->where('uuid', $input['contractuuid'])->first();
        $selectedUserGroupsUuid = array_column($input['selectedUserGroups'], 'id');
        $userIdsAssignedUserGroup = ContractUserGroupAssignedUser::select('contractUserId', 'userGroupId')
            ->whereIn('userGroupId', $selectedUserGroupsUuid)
            ->where('status', 1)
            ->get();
        foreach ($userIdsAssignedUserGroup as $user) {
            $contractId = $contractResult->id;
            $userGroupId = $user['userGroupId'];
            $userId = $user['contractUserId'];
            $existingRecord = ContractUserAssign::where('contractId', $contractId)
                ->where('userGroupId', $userGroupId)
                ->where('userId', $userId)
                ->where('status', 1)
                ->first();

            if (!$existingRecord) {
                $input['uuid'] = bin2hex(random_bytes(16));
                $input['contractId'] = $contractId;
                $input['userGroupId'] = $userGroupId;
                $input['userId'] = $user['contractUserId'];
                $input['createdBy'] = General::currentEmployeeId();
                $input['updated_at'] = null;
                $this->contractUserAssignRepository->create($input);
            }
        }

        foreach ($input['selectedUsers'] as $user) {
            $contractId = $contractResult->id;
            $userGroupId = 0;
            $userId = $user['id'];
            // Check if a record exists for the given contractId and userGroupId where status is 1
            $existingRecord = ContractUserAssign::where('contractId', $contractId)
                ->where('userGroupId', 0)
                ->where('userId', $userId)
                ->where('status', 1)
                ->first();

            if (!$existingRecord) {
                $newRecord = [
                    'uuid' => bin2hex(random_bytes(16)),
                    'contractId' => $contractId,
                    'userGroupId' => $userGroupId,
                    'userId' => $userId,
                    'status' => 1,
                    'createdBy' => General::currentEmployeeId(),
                    'updated_at' => null
                ];

                $this->contractUserAssignRepository->create($newRecord);
            }
        }

        return $this->sendResponse('',trans('common.contract_user_assign_saved_successfully'));
    }
}
