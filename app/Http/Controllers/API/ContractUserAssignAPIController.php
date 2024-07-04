<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractUserAssignAPIRequest;
use App\Http\Requests\API\UpdateContractUserAssignAPIRequest;
use App\Models\ContractUserAssign;
use App\Models\ContractUserGroup;
use App\Models\ContractUsers;
use App\Repositories\ContractUserAssignRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class ContractUserAssignController
 * @package App\Http\Controllers\API
 */

class ContractUserAssignAPIController extends AppBaseController
{
    /** @var  ContractUserAssignRepository */
    private $contractUserAssignRepository;

    public function __construct(ContractUserAssignRepository $contractUserAssignRepo)
    {
        $this->contractUserAssignRepository = $contractUserAssignRepo;
    }

    /**
     * Display a listing of the ContractUserAssign.
     * GET|HEAD /contractUserAssigns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractUserAssigns = $this->contractUserAssignRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse('',trans('common.contract_user_assigns_retrieved_successfully'));
    }

    /**
     * Store a newly created ContractUserAssign in storage.
     * POST /contractUserAssigns
     *
     * @param CreateContractUserAssignAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractUserAssignAPIRequest $request)
    {
        $input = $request->all();

        $result =  $this->contractUserAssignRepository->createRecord($input);

        if ($result) {
            return $this->sendResponse('', trans('common.contract_user_assign_saved_successfully'));
        } else {
            return $this->sendError('Failed to assign users to contract.');
        }
    }

    /**
     * Display the specified ContractUserAssign.
     * GET|HEAD /contractUserAssigns/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractUserAssign $contractUserAssign */
        $contractUserAssign = $this->contractUserAssignRepository->find($id);

        if (empty($contractUserAssign)) {
            return $this->sendError('',trans('common.contract_user_assign_not_found'));
        }

        return $this->sendResponse('',trans('common.contract_user_assigns_retrieved_successfully'));
    }

    /**
     * Update the specified ContractUserAssign in storage.
     * PUT/PATCH /contractUserAssigns/{id}
     *
     * @param int $id
     * @param UpdateContractUserAssignAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractUserAssignAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractUserAssign $contractUserAssign */
        $contractUserAssign = $this->contractUserAssignRepository->find($id);

        if (empty($contractUserAssign)) {
            return $this->sendError('',trans('common.contract_user_assign_not_found'));
        }

        $this->contractUserAssignRepository->update($input, $id);

        return $this->sendResponse('',trans('common.contract_user_assign_updated_successfully'));
    }

    /**
     * Remove the specified ContractUserAssign from storage.
     * DELETE /contractUserAssigns/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractUserAssign $contractUserAssign */
        $contractUserAssign = $this->contractUserAssignRepository->find($id);

        if (empty($contractUserAssign)) {
            return $this->sendError('',trans('common.contract_user_assign_not_found'));
        }

        $contractUserAssign->delete();

        return $this->sendResponse('',trans('common.contract_user_assign_deleted_successfully'));
    }

    public function deleteAssignedUsers(CreateContractUserAssignAPIRequest $request)
    {
        $input = $request->all();
        /** @var ContractUserAssign $contractUserAssign */
        if($input['userGroupId'] == null){
            $contractUserAssignedId = ContractUserAssign::select('id')->where('uuid', $input['id'])->first();
            $contractUserAssign = $this->contractUserAssignRepository->find($contractUserAssignedId->id);

            if (empty($contractUserAssign)) {
                return $this->sendError('',trans('common.contract_user_assign_not_found'));
            }
            $input['userGroupId'] = 0;
            $input['status'] = 0;
            $input['updatedBy'] = General::currentEmployeeId();
            $this->contractUserAssignRepository->update($input, $contractUserAssignedId->id);
        } else {
            $getUserGroupId = ContractUserGroup::select('id')
                ->where('uuid', $input['userGroupId'])
                ->first();

            $companyId = $input['selectedCompanyID'];
            $activeDefaultUserGroups = ContractUserGroup::getActiveDefaultUserGroups($companyId);
            $assignUserGroups = ContractUserAssign::getAssignUserGroups($input['contractId']);
            $activeDefaultUserGroupsIds = $activeDefaultUserGroups->pluck('id');
            $assignUserGroupsIds = $assignUserGroups->pluck('userGroupId');
            $commonIds = $activeDefaultUserGroupsIds->intersect($assignUserGroupsIds);
            $commonCount = $commonIds->count();

            $defaultUserGroupIds = $activeDefaultUserGroups->pluck('id')->toArray();
            $isInActiveDefaultUserGroups = in_array($getUserGroupId->id, $defaultUserGroupIds);

            $contractUserAssigns = ContractUserAssign::where('contractId', $input['contractId'])
                ->where('userGroupId', $getUserGroupId->id)
                ->select('id as i')
                ->get();


            if($commonCount == 1 && $isInActiveDefaultUserGroups==1)
            {
                throw new CommonException('At least one default user group must be present in a contract');
            } else
            {
                foreach ($contractUserAssigns as $contractUserAssign) {
                    $this->contractUserAssignRepository->update([
                        'status' => 0,
                        'updatedBy' => General::currentEmployeeId()
                    ], $contractUserAssign->i);
                }
            }
        }

        return $this->sendResponse('',trans('common.contract_user_assign_deleted_successfully'));
    }

    public function getAssignedUsers(Request $request) {
        return $this->contractUserAssignRepository->getAssignedUsers($request);
    }
}
