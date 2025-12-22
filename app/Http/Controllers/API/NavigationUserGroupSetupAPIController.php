<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateNavigationUserGroupSetupAPIRequest;
use App\Http\Requests\API\UpdateNavigationUserGroupSetupAPIRequest;
use App\Models\NavigationUserGroupSetup;
use App\Repositories\NavigationUserGroupSetupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\NavigationUserGroupSetupResource;
use Response;

/**
 * Class NavigationUserGroupSetupController
 * @package App\Http\Controllers\API
 */

class NavigationUserGroupSetupAPIController extends AppBaseController
{
    /** @var  NavigationUserGroupSetupRepository */
    private $navigationUserGroupSetupRepository;

    public function __construct(NavigationUserGroupSetupRepository $navigationUserGroupSetupRepo)
    {
        $this->navigationUserGroupSetupRepository = $navigationUserGroupSetupRepo;
    }

    /**
     * Display a listing of the NavigationUserGroupSetup.
     * GET|HEAD /navigationUserGroupSetups
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $navigationUserGroupSetups = $this->navigationUserGroupSetupRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(NavigationUserGroupSetupResource::collection($navigationUserGroupSetups), 'Navigation User Group Setups retrieved successfully');
    }

    /**
     * Store a newly created NavigationUserGroupSetup in storage.
     * POST /navigationUserGroupSetups
     *
     * @param CreateNavigationUserGroupSetupAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateNavigationUserGroupSetupAPIRequest $request)
    {
        $input = $request->all();

        $navigationUserGroupSetup = $this->navigationUserGroupSetupRepository->create($input);

        return $this->sendResponse(new NavigationUserGroupSetupResource($navigationUserGroupSetup), 'Navigation User Group Setup saved successfully');
    }

    /**
     * Display the specified NavigationUserGroupSetup.
     * GET|HEAD /navigationUserGroupSetups/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var NavigationUserGroupSetup $navigationUserGroupSetup */
        $navigationUserGroupSetup = $this->navigationUserGroupSetupRepository->find($id);

        if (empty($navigationUserGroupSetup)) {
            return $this->sendError('Navigation User Group Setup not found');
        }

        return $this->sendResponse(new NavigationUserGroupSetupResource($navigationUserGroupSetup), 'Navigation User Group Setup retrieved successfully');
    }

    /**
     * Update the specified NavigationUserGroupSetup in storage.
     * PUT/PATCH /navigationUserGroupSetups/{id}
     *
     * @param int $id
     * @param UpdateNavigationUserGroupSetupAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNavigationUserGroupSetupAPIRequest $request)
    {
        $input = $request->all();

        /** @var NavigationUserGroupSetup $navigationUserGroupSetup */
        $navigationUserGroupSetup = $this->navigationUserGroupSetupRepository->find($id);

        if (empty($navigationUserGroupSetup)) {
            return $this->sendError('Navigation User Group Setup not found');
        }

        $navigationUserGroupSetup = $this->navigationUserGroupSetupRepository->update($input, $id);

        return $this->sendResponse(new NavigationUserGroupSetupResource($navigationUserGroupSetup), 'NavigationUserGroupSetup updated successfully');
    }

    /**
     * Remove the specified NavigationUserGroupSetup from storage.
     * DELETE /navigationUserGroupSetups/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var NavigationUserGroupSetup $navigationUserGroupSetup */
        $navigationUserGroupSetup = $this->navigationUserGroupSetupRepository->find($id);

        if (empty($navigationUserGroupSetup)) {
            return $this->sendError('Navigation User Group Setup not found');
        }

        $navigationUserGroupSetup->delete();

        return $this->sendSuccess('Navigation User Group Setup deleted successfully');
    }
}
