<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSupplierDetailHistoryAPIRequest;
use App\Http\Requests\API\UpdateSupplierDetailHistoryAPIRequest;
use App\Models\SupplierDetailHistory;
use App\Repositories\SupplierDetailHistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SupplierDetailHistoryResource;
use Response;

/**
 * Class SupplierDetailHistoryController
 * @package App\Http\Controllers\API
 */

class SupplierDetailHistoryAPIController extends AppBaseController
{
    /** @var  SupplierDetailHistoryRepository */
    private $supplierDetailHistoryRepository;

    public function __construct(SupplierDetailHistoryRepository $supplierDetailHistoryRepo)
    {
        $this->supplierDetailHistoryRepository = $supplierDetailHistoryRepo;
    }

    /**
     * Display a listing of the SupplierDetailHistory.
     * GET|HEAD /supplierDetailHistories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $supplierDetailHistories = $this->supplierDetailHistoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SupplierDetailHistoryResource::collection($supplierDetailHistories), 'Supplier Detail Histories retrieved successfully');
    }

    /**
     * Store a newly created SupplierDetailHistory in storage.
     * POST /supplierDetailHistories
     *
     * @param CreateSupplierDetailHistoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSupplierDetailHistoryAPIRequest $request)
    {
        $input = $request->all();

        $supplierDetailHistory = $this->supplierDetailHistoryRepository->create($input);

        return $this->sendResponse(new SupplierDetailHistoryResource($supplierDetailHistory), 'Supplier Detail History saved successfully');
    }

    /**
     * Display the specified SupplierDetailHistory.
     * GET|HEAD /supplierDetailHistories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SupplierDetailHistory $supplierDetailHistory */
        $supplierDetailHistory = $this->supplierDetailHistoryRepository->find($id);

        if (empty($supplierDetailHistory)) {
            return $this->sendError('Supplier Detail History not found');
        }

        return $this->sendResponse(new SupplierDetailHistoryResource($supplierDetailHistory), 'Supplier Detail History retrieved successfully');
    }

    /**
     * Update the specified SupplierDetailHistory in storage.
     * PUT/PATCH /supplierDetailHistories/{id}
     *
     * @param int $id
     * @param UpdateSupplierDetailHistoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupplierDetailHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var SupplierDetailHistory $supplierDetailHistory */
        $supplierDetailHistory = $this->supplierDetailHistoryRepository->find($id);

        if (empty($supplierDetailHistory)) {
            return $this->sendError('Supplier Detail History not found');
        }

        $supplierDetailHistory = $this->supplierDetailHistoryRepository->update($input, $id);

        return $this->sendResponse(new SupplierDetailHistoryResource($supplierDetailHistory), 'SupplierDetailHistory updated successfully');
    }

    /**
     * Remove the specified SupplierDetailHistory from storage.
     * DELETE /supplierDetailHistories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SupplierDetailHistory $supplierDetailHistory */
        $supplierDetailHistory = $this->supplierDetailHistoryRepository->find($id);

        if (empty($supplierDetailHistory)) {
            return $this->sendError('Supplier Detail History not found');
        }

        $supplierDetailHistory->delete();

        return $this->sendSuccess('Supplier Detail History deleted successfully');
    }
}
