<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEmployeeDepartmentsAPIRequest;
use App\Http\Requests\API\UpdateEmployeeDepartmentsAPIRequest;
use App\Models\EmployeeDepartments;
use App\Repositories\EmployeeDepartmentsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\EmployeeDepartmentsResource;
use Response;

/**
 * Class EmployeeDepartmentsController
 * @package App\Http\Controllers\API
 */

class EmployeeDepartmentsAPIController extends AppBaseController
{
    /** @var  EmployeeDepartmentsRepository */
    private $employeeDepartmentsRepository;

    public function __construct(EmployeeDepartmentsRepository $employeeDepartmentsRepo)
    {
        $this->employeeDepartmentsRepository = $employeeDepartmentsRepo;
    }

    /**
     * Display a listing of the EmployeeDepartments.
     * GET|HEAD /employeeDepartments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $employeeDepartments = $this->employeeDepartmentsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(EmployeeDepartmentsResource::collection($employeeDepartments), 'Employee Departments retrieved successfully');
    }

    /**
     * Store a newly created EmployeeDepartments in storage.
     * POST /employeeDepartments
     *
     * @param CreateEmployeeDepartmentsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeeDepartmentsAPIRequest $request)
    {
        $input = $request->all();

        $employeeDepartments = $this->employeeDepartmentsRepository->create($input);

        return $this->sendResponse(new EmployeeDepartmentsResource($employeeDepartments), 'Employee Departments saved successfully');
    }

    /**
     * Display the specified EmployeeDepartments.
     * GET|HEAD /employeeDepartments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EmployeeDepartments $employeeDepartments */
        $employeeDepartments = $this->employeeDepartmentsRepository->find($id);

        if (empty($employeeDepartments)) {
            return $this->sendError('Employee Departments not found');
        }

        return $this->sendResponse(new EmployeeDepartmentsResource($employeeDepartments), 'Employee Departments retrieved successfully');
    }

    /**
     * Update the specified EmployeeDepartments in storage.
     * PUT/PATCH /employeeDepartments/{id}
     *
     * @param int $id
     * @param UpdateEmployeeDepartmentsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeeDepartmentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var EmployeeDepartments $employeeDepartments */
        $employeeDepartments = $this->employeeDepartmentsRepository->find($id);

        if (empty($employeeDepartments)) {
            return $this->sendError('Employee Departments not found');
        }

        $employeeDepartments = $this->employeeDepartmentsRepository->update($input, $id);

        return $this->sendResponse(new EmployeeDepartmentsResource($employeeDepartments), 'EmployeeDepartments updated successfully');
    }

    /**
     * Remove the specified EmployeeDepartments from storage.
     * DELETE /employeeDepartments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EmployeeDepartments $employeeDepartments */
        $employeeDepartments = $this->employeeDepartmentsRepository->find($id);

        if (empty($employeeDepartments)) {
            return $this->sendError('Employee Departments not found');
        }

        $employeeDepartments->delete();

        return $this->sendSuccess('Employee Departments deleted successfully');
    }
}
