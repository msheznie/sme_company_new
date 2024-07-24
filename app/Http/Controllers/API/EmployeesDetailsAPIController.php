<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEmployeesDetailsAPIRequest;
use App\Http\Requests\API\UpdateEmployeesDetailsAPIRequest;
use App\Models\EmployeesDetails;
use App\Repositories\EmployeesDetailsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\EmployeesDetailsResource;
use Response;

/**
 * Class EmployeesDetailsController
 * @package App\Http\Controllers\API
 */

class EmployeesDetailsAPIController extends AppBaseController
{
    /** @var  EmployeesDetailsRepository */
    private $employeesDetailsRepository;

    public function __construct(EmployeesDetailsRepository $employeesDetailsRepo)
    {
        $this->employeesDetailsRepository = $employeesDetailsRepo;
    }

    /**
     * Display a listing of the EmployeesDetails.
     * GET|HEAD /employeesDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $employeesDetails = $this->employeesDetailsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(EmployeesDetailsResource::collection($employeesDetails), 'Employees Details retrieved successfully');
    }

    /**
     * Store a newly created EmployeesDetails in storage.
     * POST /employeesDetails
     *
     * @param CreateEmployeesDetailsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeesDetailsAPIRequest $request)
    {
        $input = $request->all();

        $employeesDetails = $this->employeesDetailsRepository->create($input);

        return $this->sendResponse(new EmployeesDetailsResource($employeesDetails), 'Employees Details saved successfully');
    }

    /**
     * Display the specified EmployeesDetails.
     * GET|HEAD /employeesDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EmployeesDetails $employeesDetails */
        $employeesDetails = $this->employeesDetailsRepository->find($id);

        if (empty($employeesDetails)) {
            return $this->sendError('Employees Details not found');
        }

        return $this->sendResponse(new EmployeesDetailsResource($employeesDetails), 'Employees Details retrieved successfully');
    }

    /**
     * Update the specified EmployeesDetails in storage.
     * PUT/PATCH /employeesDetails/{id}
     *
     * @param int $id
     * @param UpdateEmployeesDetailsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeesDetailsAPIRequest $request)
    {
        $input = $request->all();

        /** @var EmployeesDetails $employeesDetails */
        $employeesDetails = $this->employeesDetailsRepository->find($id);

        if (empty($employeesDetails)) {
            return $this->sendError('Employees Details not found');
        }

        $employeesDetails = $this->employeesDetailsRepository->update($input, $id);

        return $this->sendResponse(new EmployeesDetailsResource($employeesDetails), 'EmployeesDetails updated successfully');
    }

    /**
     * Remove the specified EmployeesDetails from storage.
     * DELETE /employeesDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EmployeesDetails $employeesDetails */
        $employeesDetails = $this->employeesDetailsRepository->find($id);

        if (empty($employeesDetails)) {
            return $this->sendError('Employees Details not found');
        }

        $employeesDetails->delete();

        return $this->sendSuccess('Employees Details deleted successfully');
    }
}
