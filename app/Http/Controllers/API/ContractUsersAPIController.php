<?php

namespace App\Http\Controllers\API;

use App\Helpers\General;
use App\Http\Requests\API\CreateContractUsersAPIRequest;
use App\Http\Requests\API\UpdateContractUsersAPIRequest;
use App\Models\ContractUsers;
use App\Repositories\ContractUsersRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractUsersResource;
use App\Utilities\ContractManagementUtils;
use Illuminate\Support\Facades\Crypt;
use Response;

/**
 * Class ContractUsersController
 * @package App\Http\Controllers\API
 */

class ContractUsersAPIController extends AppBaseController
{
    /** @var  ContractUsersRepository */
    private $contractUsersRepository;

    public function __construct(ContractUsersRepository $contractUsersRepo)
    {
        $this->contractUsersRepository = $contractUsersRepo;
    }

    /**
     * Display a listing of the ContractUsers.
     * GET|HEAD /contractUsers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        return $this->contractUsersRepository->getContractUserList($request);
    }

    /**
     * Store a newly created ContractUsers in storage.
     * POST /contractUsers
     *
     * @param CreateContractUsersAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractUsersAPIRequest $request)
    {
        $input = $request->all();
        $contractUsers = $this->contractUsersRepository->storeContractUser($input);
        if($contractUsers['status']){
            return $this->sendResponse([], $contractUsers['message']);
        } else{
            $statusCode = $contractUsers['code'] ?? 404;
            return $this->sendError($statusCode['message'], $statusCode);
        }
    }

    /**
     * Display the specified ContractUsers.
     * GET|HEAD /contractUsers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractUsers $contractUsers */

        $contractUsers = $this->contractUsersRepository->findByUuid($id, ['uuid', 'contractUserId', 'contractUserType', 'contractUserCode', 'contractUserName', 'isActive']);

        if (empty($contractUsers)) {
            return $this->sendError(trans('common.contract_user_not_found'));
        }

        return $this->sendResponse($contractUsers, trans('common.contract_user_retrieved_successfully'));
    }

    /**
     * Update the specified ContractUsers in storage.
     * PUT/PATCH /contractUsers/{id}
     *
     * @param int $id
     * @param UpdateContractUsersAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractUsersAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractUsers $contractUsers */
        $contractUsers = $this->contractUsersRepository->findByUuid($id);

        if (empty($contractUsers)) {
            return $this->sendError(trans('contract_user_not_found'));
        }

        $contractUsers = $this->contractUsersRepository->updateUser($input, $id);

        if($contractUsers['status']){
            return $this->sendResponse([], $contractUsers['message']);
        } else{
            $statusCode = $contractUsers['code'] ?? 404;
            return $this->sendError($contractUsers['message'], $statusCode);
        }
    }

    /**
     * Remove the specified ContractUsers from storage.
     * DELETE /contractUsers/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractUsers $contractUsers */
        $contractUsers = $this->contractUsersRepository->findByUuid($id);

        if (empty($contractUsers)) {
            return $this->sendError(trans('common.contract_user_not_found'));
        }

        $contractUsers->delete();

        return $this->sendSuccess( trans('common.contract_user_deleted_successfully'));
    }

    public function contractUserList(Request $request) {
        return $this->contractUsersRepository->getContractUserList($request);
    }

    public function contractUserListForUserGroup(Request $request) {
        return $this->contractUsersRepository->getContractUserListForUserGroup($request);
    }

    public function contractUserFormData(Request $request) {
        return $this->contractUsersRepository->contractUserFormData($request);
    }
}
