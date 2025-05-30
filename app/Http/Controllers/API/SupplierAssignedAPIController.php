<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSupplierAssignedAPIRequest;
use App\Http\Requests\API\UpdateSupplierAssignedAPIRequest;
use App\Models\SupplierAssigned;
use App\Repositories\SupplierAssignedRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SupplierAssignedResource;
use Response;

/**
 * Class SupplierAssignedController
 * @package App\Http\Controllers\API
 */

class SupplierAssignedAPIController extends AppBaseController
{
    /** @var  SupplierAssignedRepository */
    private $supplierAssignedRepository;

    public function __construct(SupplierAssignedRepository $supplierAssignedRepo)
    {
        $this->supplierAssignedRepository = $supplierAssignedRepo;
    }

    /**
     * Display a listing of the SupplierAssigned.
     * GET|HEAD /supplierAssigneds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $supplierAssigneds = $this->supplierAssignedRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SupplierAssignedResource::collection($supplierAssigneds), 'Supplier Assigneds retrieved successfully');
    }

    /**
     * Store a newly created SupplierAssigned in storage.
     * POST /supplierAssigneds
     *
     * @param CreateSupplierAssignedAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSupplierAssignedAPIRequest $request)
    {
        $input = $request->all();

        $supplierAssigned = $this->supplierAssignedRepository->create($input);

        return $this->sendResponse(new SupplierAssignedResource($supplierAssigned), 'Supplier Assigned saved successfully');
    }

    /**
     * Display the specified SupplierAssigned.
     * GET|HEAD /supplierAssigneds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SupplierAssigned $supplierAssigned */
        $supplierAssigned = $this->supplierAssignedRepository->find($id);

        if (empty($supplierAssigned)) {
            return $this->sendError(trans('common.supplier_assigned_not_found'));
        }

        return $this->sendResponse(new SupplierAssignedResource($supplierAssigned), 'Supplier Assigned retrieved successfully');
    }

    /**
     * Update the specified SupplierAssigned in storage.
     * PUT/PATCH /supplierAssigneds/{id}
     *
     * @param int $id
     * @param UpdateSupplierAssignedAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupplierAssignedAPIRequest $request)
    {
        $input = $request->all();

        /** @var SupplierAssigned $supplierAssigned */
        $supplierAssigned = $this->supplierAssignedRepository->find($id);

        if (empty($supplierAssigned)) {
            return $this->sendError(trans('common.supplier_assigned_not_found'));
        }

        $supplierAssigned = $this->supplierAssignedRepository->update($input, $id);

        return $this->sendResponse(new SupplierAssignedResource($supplierAssigned), 'SupplierAssigned updated successfully');
    }

    /**
     * Remove the specified SupplierAssigned from storage.
     * DELETE /supplierAssigneds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SupplierAssigned $supplierAssigned */
        $supplierAssigned = $this->supplierAssignedRepository->find($id);

        if (empty($supplierAssigned)) {
            return $this->sendError(trans('common.supplier_assigned_not_found'));
        }

        $supplierAssigned->delete();

        return $this->sendSuccess('Supplier Assigned deleted successfully');
    }
}
