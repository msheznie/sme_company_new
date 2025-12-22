<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEmployeesAPIRequest;
use App\Http\Requests\API\UpdateEmployeesAPIRequest;
use App\Models\Employees;
use App\Repositories\EmployeesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\EmployeesResource;
use Response;

/**
 * Class EmployeesController
 * @package App\Http\Controllers\API
 */

class EmployeesAPIController extends AppBaseController
{
    /** @var  EmployeesRepository */
    private $employeesRepository;

    public function __construct(EmployeesRepository $employeesRepo)
    {
        $this->employeesRepository = $employeesRepo;
    }

    /**
     * Display a listing of the Employees.
     * GET|HEAD /employees
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $employees = $this->employeesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(EmployeesResource::collection($employees), 'Employees retrieved successfully');
    }

    /**
     * Store a newly created Employees in storage.
     * POST /employees
     *
     * @param CreateEmployeesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeesAPIRequest $request)
    {
        $input = $request->all();

        $employees = $this->employeesRepository->create($input);

        return $this->sendResponse(new EmployeesResource($employees), 'Employees saved successfully');
    }

    /**
     * Display the specified Employees.
     * GET|HEAD /employees/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Employees $employees */
        $employees = $this->employeesRepository->find($id);

        if (empty($employees)) {
            return $this->sendError('Employees not found');
        }

        return $this->sendResponse(new EmployeesResource($employees), 'Employees retrieved successfully');
    }

    /**
     * Update the specified Employees in storage.
     * PUT/PATCH /employees/{id}
     *
     * @param int $id
     * @param UpdateEmployeesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeesAPIRequest $request)
    {
        $input = $request->all();

        /** @var Employees $employees */
        $employees = $this->employeesRepository->find($id);

        if (empty($employees)) {
            return $this->sendError('Employees not found');
        }

        $employees = $this->employeesRepository->update($input, $id);

        return $this->sendResponse(new EmployeesResource($employees), 'Employees updated successfully');
    }

    /**
     * Remove the specified Employees from storage.
     * DELETE /employees/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Employees $employees */
        $employees = $this->employeesRepository->find($id);

        if (empty($employees)) {
            return $this->sendError('Employees not found');
        }

        $employees->delete();

        return $this->sendSuccess('Employees deleted successfully');
    }
}
