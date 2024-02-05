<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateNavigationRoleAPIRequest;
use App\Http\Requests\API\UpdateNavigationRoleAPIRequest;
use App\Models\NavigationRole;
use App\Repositories\NavigationRoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\NavigationRoleResource;
use Response;

/**
 * Class NavigationRoleController
 * @package App\Http\Controllers\API
 */

class NavigationRoleAPIController extends AppBaseController
{
    /** @var  NavigationRoleRepository */
    private $navigationRoleRepository;

    public function __construct(NavigationRoleRepository $navigationRoleRepo)
    {
        $this->navigationRoleRepository = $navigationRoleRepo;
    }

    /**
     * Display a listing of the NavigationRole.
     * GET|HEAD /navigationRoles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $navigationRoles = $this->navigationRoleRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(NavigationRoleResource::collection($navigationRoles), 'Navigation Roles retrieved successfully');
    }

    /**
     * Store a newly created NavigationRole in storage.
     * POST /navigationRoles
     *
     * @param CreateNavigationRoleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateNavigationRoleAPIRequest $request)
    {
        $input = $request->all();

        $navigationRole = $this->navigationRoleRepository->create($input);

        return $this->sendResponse(new NavigationRoleResource($navigationRole), 'Navigation Role saved successfully');
    }

    /**
     * Display the specified NavigationRole.
     * GET|HEAD /navigationRoles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var NavigationRole $navigationRole */
        $navigationRole = $this->navigationRoleRepository->find($id);

        if (empty($navigationRole)) {
            return $this->sendError('Navigation Role not found');
        }

        return $this->sendResponse(new NavigationRoleResource($navigationRole), 'Navigation Role retrieved successfully');
    }

    /**
     * Update the specified NavigationRole in storage.
     * PUT/PATCH /navigationRoles/{id}
     *
     * @param int $id
     * @param UpdateNavigationRoleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNavigationRoleAPIRequest $request)
    {
        $input = $request->all();

        /** @var NavigationRole $navigationRole */
        $navigationRole = $this->navigationRoleRepository->find($id);

        if (empty($navigationRole)) {
            return $this->sendError('Navigation Role not found');
        }

        $navigationRole = $this->navigationRoleRepository->update($input, $id);

        return $this->sendResponse(new NavigationRoleResource($navigationRole), 'NavigationRole updated successfully');
    }

    /**
     * Remove the specified NavigationRole from storage.
     * DELETE /navigationRoles/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var NavigationRole $navigationRole */
        $navigationRole = $this->navigationRoleRepository->find($id);

        if (empty($navigationRole)) {
            return $this->sendError('Navigation Role not found');
        }

        $navigationRole->delete();

        return $this->sendSuccess('Navigation Role deleted successfully');
    }
}
