<?php

namespace App\Http\Controllers\API;

use App\Helpers\General;
use App\Http\Requests\API\CreateContractUserGroupAPIRequest;
use App\Http\Requests\API\UpdateContractUserGroupAPIRequest;
use App\Models\ContractUserGroup;
use App\Models\ContractUserGroupAssignedUser;
use App\Repositories\ContractUserGroupAssignedUserRepository;
use App\Repositories\ContractUserGroupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractUserGroupResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * Class ContractUserGroupController
 * @package App\Http\Controllers\API
 */

class ContractUserGroupAPIController extends AppBaseController
{
    /** @var  ContractUserGroupRepository */
    private $contractUserGroupRepository;
    private $contractUserGroupAssignedRepository;

    public function __construct(ContractUserGroupRepository $contractUserGroupRepo,
                                ContractUserGroupAssignedUserRepository $contractUserGroupAssignedRepository
    )
    {
        $this->contractUserGroupRepository = $contractUserGroupRepo;
        $this->contractUserGroupAssignedRepository = $contractUserGroupAssignedRepository;
    }

    public function index(Request $request)
    {
        $contractUserGroups = $this->contractUserGroupRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(
            ContractUserGroupResource::collection($contractUserGroups),
            trans('common.user_group_retrieved_successfully')
        );
    }

    public function store(CreateContractUserGroupAPIRequest $request)
    {
        try {
            $input = $request->all();
            $uuid = $input['uuid'];
            if($uuid === 0){
                $isExist = ContractUserGroup::where('groupName', $input['groupName'])->exists();
                if(!$isExist){
                    $input['uuid'] = bin2hex(random_bytes(16));
                    $contractUserGroup = $this->contractUserGroupRepository->create($input);
                } else {
                    return $this->sendError(trans('common.group_name_already_exists '), 409);
                }

            } else {
                $contractUserGroup = ContractUserGroup::select('id')->where('uuid', $uuid)->first();
            }

            if (!empty($input['selectedUsers'])) {
                foreach ($input['selectedUsers'] as $userId) {
                    $assignedUserInput = [
                        'uuid' => bin2hex(random_bytes(16)),
                        'userGroupId' => $contractUserGroup->id,
                        'companySystemID' => $input['companySystemID'],
                        'contractUserId' => $userId['id'],
                        'giveAccessToExistingContracts' => $input['giveAccessToExistingContracts'],
                        'created_by' => General::currentEmployeeId(),
                        'updated_by' => null,
                    ];
                    ContractUserGroupAssignedUser::create($assignedUserInput);
                }
            }
            return $this->sendResponse(
                new ContractUserGroupResource($contractUserGroup),
                trans('common.user_group_saved_successfully')
            );

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return $this->sendError(trans('common.group_name_already_exists '), 409);
            }
            return $this->sendError(trans('common.database_error'), 500);
        }
    }

    public function show($id)
    {
        /** @var ContractUserGroup $contractUserGroup */
        $contractUserGroup = $this->contractUserGroupRepository->find($id);

        if (empty($contractUserGroup)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        return $this->sendResponse(
            new ContractUserGroupResource($contractUserGroup),
            trans('common.user_group_retrieved_successfully')
        );

    }

    public function update($id, UpdateContractUserGroupAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractUserGroup $contractUserGroup */
        $contractUserGroup = $this->contractUserGroupRepository->find($id);

        if (empty($contractUserGroup)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        $contractUserGroup = $this->contractUserGroupRepository->update($input, $id);

        return $this->sendResponse(
            new ContractUserGroupResource($contractUserGroup),
            trans('common.contract_user_group_updated_successfully')
        );

    }

    public function destroy($id)
    {
        /** @var ContractUserGroup $contractUserGroup */
        $contractUserGroup = $this->contractUserGroupRepository->find($id);

        if (empty($contractUserGroup)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        $contractUserGroup->delete();

        return $this->sendSuccess(trans('common.contract_user_group_deleted_successfully'));
    }

    public function getContractUserGroupList(Request $request) {
        return $this->contractUserGroupRepository->getContractUserGroupList($request);
    }

    public function getContractUserGroupAssignedUsers(Request $request) {
        return $this->contractUserGroupRepository->getContractUserGroupAssignedUsers($request);
    }

    public function updateStatus($id, UpdateContractUserGroupAPIRequest $request)
    {
        $input = $request->all();
        $result = ContractUserGroup::select('id')
            ->where('uuid', $input['uuid'])
            ->first();

        if (empty($result)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        $contractUserGroup = $this->contractUserGroupRepository->find($result->id);

        if (empty($contractUserGroup)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        $status = $input['status'];
        $contractUserGroup->status = $status;
        $contractUserGroup->save();

        return $this->sendResponse(
            new ContractUserGroupResource($contractUserGroup),
            trans('common.user_group_status_updated_successfully')
        );

    }

    public function removeAssignedUserFromUserGroup($id)
    {

        $contractUserGroupAssignedUser = ContractUserGroupAssignedUser::where('uuid', $id)->first();

        if (empty($contractUserGroupAssignedUser)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        $contractUserGroupAssignedUser->delete();

        return $this->sendSuccess('User deleted successfully');
    }

    public function contractUserList(Request $request) {
        return $this->contractUserGroupRepository->getContractUserListToAssign($request);
    }

    public function contractUserListForUserGroup(Request $request) {
        return $this->contractUserGroupRepository->getContractUserListForUserGroup($request);
    }

    public function contractUserGroupList(Request $request) {
        return $this->contractUserGroupRepository->contractUserGroupList($request);
    }

}
