<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpEmployeesDepartmentsAPIRequest;
use App\Http\Requests\API\UpdateErpEmployeesDepartmentsAPIRequest;
use App\Models\ErpEmployeesDepartments;
use App\Repositories\ErpEmployeesDepartmentsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpEmployeesDepartmentsResource;
use Response;

/**
 * Class ErpEmployeesDepartmentsController
 * @package App\Http\Controllers\API
 */

class ErpEmployeesDepartmentsAPIController extends AppBaseController
{
    /** @var  ErpEmployeesDepartmentsRepository */
    private $erpEmployeesDepartmentsRepository;

    public function __construct(ErpEmployeesDepartmentsRepository $erpEmployeesDepartmentsRepo)
    {
        $this->erpEmployeesDepartmentsRepository = $erpEmployeesDepartmentsRepo;
    }

    /**
     * Display a listing of the ErpEmployeesDepartments.
     * GET|HEAD /erpEmployeesDepartments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpEmployeesDepartments = $this->erpEmployeesDepartmentsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpEmployeesDepartmentsResource::collection($erpEmployeesDepartments),
            'Erp Employees Departments retrieved successfully');
    }

    /**
     * Store a newly created ErpEmployeesDepartments in storage.
     * POST /erpEmployeesDepartments
     *
     * @param CreateErpEmployeesDepartmentsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpEmployeesDepartmentsAPIRequest $request)
    {
        $input = $request->all();

        $erpEmployeesDepartments = $this->erpEmployeesDepartmentsRepository->create($input);

        return $this->sendResponse(new ErpEmployeesDepartmentsResource($erpEmployeesDepartments),
            'Erp Employees Departments saved successfully');
    }

    /**
     * Display the specified ErpEmployeesDepartments.
     * GET|HEAD /erpEmployeesDepartments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpEmployeesDepartments $erpEmployeesDepartments */
        $erpEmployeesDepartments = $this->erpEmployeesDepartmentsRepository->find($id);

        if (empty($erpEmployeesDepartments))
        {
            return $this->sendError(trans('common.employees_department_is_not_found'));
        }

        return $this->sendResponse(new ErpEmployeesDepartmentsResource($erpEmployeesDepartments),
            'Erp Employees Departments retrieved successfully');
    }

    /**
     * Update the specified ErpEmployeesDepartments in storage.
     * PUT/PATCH /erpEmployeesDepartments/{id}
     *
     * @param int $id
     * @param UpdateErpEmployeesDepartmentsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpEmployeesDepartmentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpEmployeesDepartments $erpEmployeesDepartments */
        $erpEmployeesDepartments = $this->erpEmployeesDepartmentsRepository->find($id);

        if (empty($erpEmployeesDepartments))
        {
            return $this->sendError(trans('common.employees_department_is_not_found'));
        }

        $erpEmployeesDepartments = $this->erpEmployeesDepartmentsRepository->update($input, $id);

        return $this->sendResponse(new ErpEmployeesDepartmentsResource($erpEmployeesDepartments),
            'ErpEmployeesDepartments updated successfully');
    }

    /**
     * Remove the specified ErpEmployeesDepartments from storage.
     * DELETE /erpEmployeesDepartments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpEmployeesDepartments $erpEmployeesDepartments */
        $erpEmployeesDepartments = $this->erpEmployeesDepartmentsRepository->find($id);

        if (empty($erpEmployeesDepartments))
        {
            return $this->sendError(trans('common.employees_department_is_not_found'));
        }

        $erpEmployeesDepartments->delete();

        return $this->sendSuccess('Erp Employees Departments deleted successfully');
    }
}
