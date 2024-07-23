<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractUserGroupAPIRequest;
use App\Http\Requests\API\UpdateContractUserGroupAPIRequest;
use App\Models\ContractUserAssign;
use App\Models\ContractUserGroup;
use App\Models\ContractUserGroupAssignedUser;
use App\Models\ContractUsers;
use App\Repositories\ContractUserGroupAssignedUserRepository;
use App\Repositories\ContractUserGroupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractUserGroupResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
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

    private $contractUserAssignRepository;

    public function __construct(ContractUserGroupRepository $contractUserGroupRepo,
                                ContractUserGroupAssignedUserRepository $contractUserGroupAssignedRepository,
                                ContractUserAssign $contractUserAssignRepository
    )
    {
        $this->contractUserGroupRepository = $contractUserGroupRepo;
        $this->contractUserGroupAssignedRepository = $contractUserGroupAssignedRepository;
        $this->contractUserAssignRepository = $contractUserAssignRepository;
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
        $result = $this->contractUserGroupRepository->createRecord($request);
        if ($result['success']) {
            return $this->sendResponse($result['data'], trans('common.user_group_saved_successfully'));
        } else {
            return $this->sendError($result['message'], $result['code']);
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
        $companyId = $input['selectedCompanyID'];
        $result = ContractUserGroup::select('id')
            ->where('uuid', $input['uuid'])
            ->first();

        $activeDefaultUserGroupsCount = ContractUserGroup::getActiveDefaultUserGroups($companyId)->count();
        $defaultUserGroupIds = ContractUserGroup::getActiveDefaultUserGroups($companyId)->pluck('id')->toArray();
        $isInActiveDefaultUserGroups = in_array($result->id, $defaultUserGroupIds);

        if (empty($result)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        $contractUserGroup = $this->contractUserGroupRepository->find($result->id);

        if (empty($contractUserGroup)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        if ($activeDefaultUserGroupsCount == 1 && $isInActiveDefaultUserGroups==1)
        {
            throw new CommonException('At least one default user group must be enabled');
        } else
        {
            $status = $input['status'];
            $contractUserGroup->status = $status;
            $contractUserGroup->save();

            return $this->sendResponse(
                new ContractUserGroupResource($contractUserGroup),
                trans('common.user_group_status_updated_successfully')
            );
        }

    }

    public function removeAssignedUserFromUserGroup($id, UpdateContractUserGroupAPIRequest $request)
    {
        $input = $request->all();
        $contractUserGroupAssignedUser = ContractUserGroupAssignedUser::where('uuid', $id)->first();
        $userGroupId = $contractUserGroupAssignedUser->userGroupId;
        $activeUserCountOfTheContract = ContractUserGroupAssignedUser::where('userGroupId', $userGroupId)
            ->where('status', 1)
            ->count();

        $userGroup = ContractUserGroup::where('id', $userGroupId )->first();

        if($input['status'] == 0 && $activeUserCountOfTheContract == 1 && $userGroup->isDefault == 1){
            return $this->sendError(trans('common.active_user_should_jn_default_user_group'));
        }

        if (empty($contractUserGroupAssignedUser)) {
            return $this->sendError(trans('common.contract_user_group_not_found'));
        }

        $contractUserGroupAssignedUser->status = $input['status'];
        $contractUserGroupAssignedUser->save();

        return $this->sendSuccess(trans('common.user_status_updated_successfully'));
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
