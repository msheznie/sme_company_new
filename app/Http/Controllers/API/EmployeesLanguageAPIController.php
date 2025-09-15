<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEmployeesLanguageAPIRequest;
use App\Http\Requests\API\UpdateEmployeesLanguageAPIRequest;
use App\Models\EmployeesLanguage;
use App\Repositories\EmployeesLanguageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\EmployeesLanguageResource;
use Response;

/**
 * Class EmployeesLanguageController
 * @package App\Http\Controllers\API
 */

class EmployeesLanguageAPIController extends AppBaseController
{
    /** @var  EmployeesLanguageRepository */
    private $employeesLanguageRepository;

    public function __construct(EmployeesLanguageRepository $employeesLanguageRepo)
    {
        $this->employeesLanguageRepository = $employeesLanguageRepo;
    }

    /**
     * Display a listing of the EmployeesLanguage.
     * GET|HEAD /employeesLanguages
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $employeesLanguages = $this->employeesLanguageRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(EmployeesLanguageResource::collection($employeesLanguages), 'Employees Languages retrieved successfully');
    }

    /**
     * Store a newly created EmployeesLanguage in storage.
     * POST /employeesLanguages
     *
     * @param CreateEmployeesLanguageAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeesLanguageAPIRequest $request)
    {
        $input = $request->all();

        $employeesLanguage = $this->employeesLanguageRepository->create($input);

        return $this->sendResponse(new EmployeesLanguageResource($employeesLanguage), 'Employees Language saved successfully');
    }

    /**
     * Display the specified EmployeesLanguage.
     * GET|HEAD /employeesLanguages/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EmployeesLanguage $employeesLanguage */
        $employeesLanguage = $this->employeesLanguageRepository->find($id);

        if (empty($employeesLanguage)) {
            return $this->sendError('Employees Language not found');
        }

        return $this->sendResponse(new EmployeesLanguageResource($employeesLanguage), 'Employees Language retrieved successfully');
    }

    /**
     * Update the specified EmployeesLanguage in storage.
     * PUT/PATCH /employeesLanguages/{id}
     *
     * @param int $id
     * @param UpdateEmployeesLanguageAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeesLanguageAPIRequest $request)
    {
        $input = $request->all();

        /** @var EmployeesLanguage $employeesLanguage */
        $employeesLanguage = $this->employeesLanguageRepository->find($id);

        if (empty($employeesLanguage)) {
            return $this->sendError('Employees Language not found');
        }

        $employeesLanguage = $this->employeesLanguageRepository->update($input, $id);

        return $this->sendResponse(new EmployeesLanguageResource($employeesLanguage), 'EmployeesLanguage updated successfully');
    }

    /**
     * Remove the specified EmployeesLanguage from storage.
     * DELETE /employeesLanguages/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EmployeesLanguage $employeesLanguage */
        $employeesLanguage = $this->employeesLanguageRepository->find($id);

        if (empty($employeesLanguage)) {
            return $this->sendError('Employees Language not found');
        }

        $employeesLanguage->delete();

        return $this->sendSuccess('Employees Language deleted successfully');
    }
}
