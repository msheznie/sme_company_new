<?php

namespace App\Http\Controllers\API;

use App\Helpers\General;
use App\Http\Requests\API\CreateUsersAPIRequest;
use App\Http\Requests\API\UpdateUsersAPIRequest;
use App\Models\Users;
use App\Models\WebEmployeeProfile;
use App\Repositories\UsersRepository;
Use App\Repositories\NavigationUserGroupSetupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UsersResource;
use Response;

/**
 * Class UsersController
 * @package App\Http\Controllers\API
 */

class UsersAPIController extends AppBaseController
{
    /** @var  UsersRepository */
    private $usersRepository;
    private $navigationUserGroupSetup;

    public function __construct(UsersRepository $usersRepo, NavigationUserGroupSetupRepository $navigationUserGroupSetup)
    {
        $this->usersRepository = $usersRepo;
        $this->navigationUserGroupSetup = $navigationUserGroupSetup;
    }

    /**
     * Display a listing of the Users.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->usersRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(UsersResource::collection($users), 'Users retrieved successfully');
    }

    /**
     * Store a newly created Users in storage.
     * POST /users
     *
     * @param CreateUsersAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUsersAPIRequest $request)
    {
        $input = $request->all();

        $users = $this->usersRepository->create($input);

        return $this->sendResponse(new UsersResource($users), 'Users saved successfully');
    }

    /**
     * Display the specified Users.
     * GET|HEAD /users/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Users $users */
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            return $this->sendError('Users not found');
        }

        return $this->sendResponse(new UsersResource($users), 'Users retrieved successfully');
    }

    /**
     * Update the specified Users in storage.
     * PUT/PATCH /users/{id}
     *
     * @param int $id
     * @param UpdateUsersAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUsersAPIRequest $request)
    {
        $input = $request->all();

        /** @var Users $users */
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            return $this->sendError('Users not found');
        }

        $users = $this->usersRepository->update($input, $id);

        return $this->sendResponse(new UsersResource($users), 'Users updated successfully');
    }

    /**
     * Remove the specified Users from storage.
     * DELETE /users/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Users $users */
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            return $this->sendError('Users not found');
        }

        $users->delete();

        return $this->sendSuccess('Users deleted successfully');
    }

    public function getCurrentUser(Request $request)
    {
        $user = General::currentUser();
        $input = $request->all();

        $companySystemID = $input['selectedCompanyID'] ?? $user['employee']['empCompanySystemID'] ?? 0;
        $user['companies'] = [];
        $user['tenant_id'] = $user['employee']['empCompanySystemID'] ?? 0;
        $user['name'] = $user['employee']['empName'] ?? '';
        $user['profile_url'] = $this->usersRepository->getEmployeeImage($user['employee_id']);
        $navigations = $this->navigationUserGroupSetup->userMenusByCompany($companySystemID, $user['employee_id']);
        $user['navigations'] = $navigations;
        return $user;
    }
}
