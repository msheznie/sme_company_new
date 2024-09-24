<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDepartmentMasterAPIRequest;
use App\Http\Requests\API\UpdateDepartmentMasterAPIRequest;
use App\Models\DepartmentMaster;
use App\Repositories\DepartmentMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DepartmentMasterResource;
use Response;

/**
 * Class DepartmentMasterController
 * @package App\Http\Controllers\API
 */

class DepartmentMasterAPIController extends AppBaseController
{
    /** @var  DepartmentMasterRepository */
    private $departmentMasterRepository;

    public function __construct(DepartmentMasterRepository $departmentMasterRepo)
    {
        $this->departmentMasterRepository = $departmentMasterRepo;
    }

    /**
     * Display a listing of the DepartmentMaster.
     * GET|HEAD /departmentMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $departmentMasters = $this->departmentMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(DepartmentMasterResource::collection($departmentMasters), 'Department Masters retrieved successfully');
    }

    /**
     * Store a newly created DepartmentMaster in storage.
     * POST /departmentMasters
     *
     * @param CreateDepartmentMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDepartmentMasterAPIRequest $request)
    {
        $input = $request->all();

        $departmentMaster = $this->departmentMasterRepository->create($input);

        return $this->sendResponse(new DepartmentMasterResource($departmentMaster), 'Department Master saved successfully');
    }

    /**
     * Display the specified DepartmentMaster.
     * GET|HEAD /departmentMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DepartmentMaster $departmentMaster */
        $departmentMaster = $this->departmentMasterRepository->find($id);

        if (empty($departmentMaster)) {
            return $this->sendError('Department Master not found');
        }

        return $this->sendResponse(new DepartmentMasterResource($departmentMaster), 'Department Master retrieved successfully');
    }

    /**
     * Update the specified DepartmentMaster in storage.
     * PUT/PATCH /departmentMasters/{id}
     *
     * @param int $id
     * @param UpdateDepartmentMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDepartmentMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var DepartmentMaster $departmentMaster */
        $departmentMaster = $this->departmentMasterRepository->find($id);

        if (empty($departmentMaster)) {
            return $this->sendError('Department Master not found');
        }

        $departmentMaster = $this->departmentMasterRepository->update($input, $id);

        return $this->sendResponse(new DepartmentMasterResource($departmentMaster), 'DepartmentMaster updated successfully');
    }

    /**
     * Remove the specified DepartmentMaster from storage.
     * DELETE /departmentMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DepartmentMaster $departmentMaster */
        $departmentMaster = $this->departmentMasterRepository->find($id);

        if (empty($departmentMaster)) {
            return $this->sendError('Department Master not found');
        }

        $departmentMaster->delete();

        return $this->sendSuccess('Department Master deleted successfully');
    }
}
