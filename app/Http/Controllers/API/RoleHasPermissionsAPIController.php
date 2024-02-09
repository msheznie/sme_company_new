<?php

namespace App\Http\Controllers\API;

use App\Helper\Helper;
use App\Http\Requests\API\CreateRoleHasPermissionsAPIRequest;
use App\Http\Requests\API\UpdateRoleHasPermissionsAPIRequest;
use App\Models\RoleHasPermissions;
use App\Repositories\RoleHasPermissionsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\RoleHasPermissionsResource;
use App\Models\Role;
use Response;

/**
 * Class RoleHasPermissionsController
 * @package App\Http\Controllers\API
 */

class RoleHasPermissionsAPIController extends AppBaseController
{
    /** @var  RoleHasPermissionsRepository */
    private $roleHasPermissionsRepository;

    public function __construct(RoleHasPermissionsRepository $roleHasPermissionsRepo)
    {
        $this->roleHasPermissionsRepository = $roleHasPermissionsRepo;
    }

    /**
     * Display a listing of the RoleHasPermissions.
     * GET|HEAD /roleHasPermissions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $roleHasPermissions = $this->roleHasPermissionsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(RoleHasPermissionsResource::collection($roleHasPermissions), 'Role Has Permissions retrieved successfully');
    }

    /**
     * Store a newly created RoleHasPermissions in storage.
     * POST /roleHasPermissions
     *
     * @param CreateRoleHasPermissionsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleHasPermissionsAPIRequest $request)
    {
        $input = $request->all();

        $roleHasPermissions = $this->roleHasPermissionsRepository->create($input);

        return $this->sendResponse(new RoleHasPermissionsResource($roleHasPermissions), 'Role Has Permissions saved successfully');
    }

    /**
     * Display the specified RoleHasPermissions.
     * GET|HEAD /roleHasPermissions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RoleHasPermissions $roleHasPermissions */
        $roleHasPermissions = $this->roleHasPermissionsRepository->find($id);

        if (empty($roleHasPermissions)) {
            return $this->sendError('Role Has Permissions not found');
        }

        return $this->sendResponse(new RoleHasPermissionsResource($roleHasPermissions), 'Role Has Permissions retrieved successfully');
    }

    /**
     * Update the specified RoleHasPermissions in storage.
     * PUT/PATCH /roleHasPermissions/{id}
     *
     * @param int $id
     * @param UpdateRoleHasPermissionsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleHasPermissionsAPIRequest $request)
    {
        $input = $request->all();

        /** @var RoleHasPermissions $roleHasPermissions */
        $roleHasPermissions = $this->roleHasPermissionsRepository->find($id);

        if (empty($roleHasPermissions)) {
            return $this->sendError('Role Has Permissions not found');
        }

        $roleHasPermissions = $this->roleHasPermissionsRepository->update($input, $id);

        return $this->sendResponse(new RoleHasPermissionsResource($roleHasPermissions), 'RoleHasPermissions updated successfully');
    }

    /**
     * Remove the specified RoleHasPermissions from storage.
     * DELETE /roleHasPermissions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RoleHasPermissions $roleHasPermissions */
        $roleHasPermissions = $this->roleHasPermissionsRepository->find($id);

        if (empty($roleHasPermissions)) {
            return $this->sendError('Role Has Permissions not found');
        }

        $roleHasPermissions->delete();

        return $this->sendSuccess('Role Has Permissions deleted successfully');
    }

    public function updatePermission(Request $request)
    {
        $menus = $request->post('menus');
        $permissions = $request->post('permissions');
        $role_id = $request->post('roleID');
        try {
            if (Role::find($role_id)->name === 'Super Admin') {
                throw new \Exception('You can not edit super admin permissions');
            }
            $this->roleHasPermissionsRepository->updateRolePermission($permissions, $role_id);
            $this->roleHasPermissionsRepository->updateRoleNavigation($menus, $role_id);
            return $this->sendResponse([], 'Permission updated');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function getFormData()
    {
        return $this->roleHasPermissionsRepository->getFormData();
    }
}
