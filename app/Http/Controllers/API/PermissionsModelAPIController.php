<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePermissionsModelAPIRequest;
use App\Http\Requests\API\UpdatePermissionsModelAPIRequest;
use App\Models\PermissionsModel;
use App\Repositories\PermissionsModelRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PermissionsModelResource;
use Response;

/**
 * Class PermissionsModelController
 * @package App\Http\Controllers\API
 */

class PermissionsModelAPIController extends AppBaseController
{
    /** @var  PermissionsModelRepository */
    private $permissionsModelRepository;

    public function __construct(PermissionsModelRepository $permissionsModelRepo)
    {
        $this->permissionsModelRepository = $permissionsModelRepo;
    }

    /**
     * Display a listing of the PermissionsModel.
     * GET|HEAD /permissionsModels
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $permissionsModels = $this->permissionsModelRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PermissionsModelResource::collection($permissionsModels), 'Permissions Models retrieved successfully');
    }

    /**
     * Store a newly created PermissionsModel in storage.
     * POST /permissionsModels
     *
     * @param CreatePermissionsModelAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePermissionsModelAPIRequest $request)
    {
        $input = $request->all();

        $permissionsModel = $this->permissionsModelRepository->create($input);

        return $this->sendResponse(new PermissionsModelResource($permissionsModel), 'Permissions Model saved successfully');
    }

    /**
     * Display the specified PermissionsModel.
     * GET|HEAD /permissionsModels/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PermissionsModel $permissionsModel */
        $permissionsModel = $this->permissionsModelRepository->find($id);

        if (empty($permissionsModel)) {
            return $this->sendError('Permissions Model not found');
        }

        return $this->sendResponse(new PermissionsModelResource($permissionsModel), 'Permissions Model retrieved successfully');
    }

    /**
     * Update the specified PermissionsModel in storage.
     * PUT/PATCH /permissionsModels/{id}
     *
     * @param int $id
     * @param UpdatePermissionsModelAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePermissionsModelAPIRequest $request)
    {
        $input = $request->all();

        /** @var PermissionsModel $permissionsModel */
        $permissionsModel = $this->permissionsModelRepository->find($id);

        if (empty($permissionsModel)) {
            return $this->sendError('Permissions Model not found');
        }

        $permissionsModel = $this->permissionsModelRepository->update($input, $id);

        return $this->sendResponse(new PermissionsModelResource($permissionsModel), 'PermissionsModel updated successfully');
    }

    /**
     * Remove the specified PermissionsModel from storage.
     * DELETE /permissionsModels/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PermissionsModel $permissionsModel */
        $permissionsModel = $this->permissionsModelRepository->find($id);

        if (empty($permissionsModel)) {
            return $this->sendError('Permissions Model not found');
        }

        $permissionsModel->delete();

        return $this->sendSuccess('Permissions Model deleted successfully');
    }
}
