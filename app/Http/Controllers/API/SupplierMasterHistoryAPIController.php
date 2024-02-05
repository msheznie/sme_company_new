<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSupplierMasterHistoryAPIRequest;
use App\Http\Requests\API\UpdateSupplierMasterHistoryAPIRequest;
use App\Models\SupplierMasterHistory;
use App\Repositories\SupplierMasterHistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SupplierMasterHistoryResource;
use Response;

/**
 * Class SupplierMasterHistoryController
 * @package App\Http\Controllers\API
 */

class SupplierMasterHistoryAPIController extends AppBaseController
{
    /** @var  SupplierMasterHistoryRepository */
    private $supplierMasterHistoryRepository;

    public function __construct(SupplierMasterHistoryRepository $supplierMasterHistoryRepo)
    {
        $this->supplierMasterHistoryRepository = $supplierMasterHistoryRepo;
    }

    /**
     * Display a listing of the SupplierMasterHistory.
     * GET|HEAD /supplierMasterHistories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $supplierMasterHistories = $this->supplierMasterHistoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SupplierMasterHistoryResource::collection($supplierMasterHistories), 'Supplier Master Histories retrieved successfully');
    }

    /**
     * Store a newly created SupplierMasterHistory in storage.
     * POST /supplierMasterHistories
     *
     * @param CreateSupplierMasterHistoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSupplierMasterHistoryAPIRequest $request)
    {
        $input = $request->all();

        $supplierMasterHistory = $this->supplierMasterHistoryRepository->create($input);

        return $this->sendResponse(new SupplierMasterHistoryResource($supplierMasterHistory), 'Supplier Master History saved successfully');
    }

    /**
     * Display the specified SupplierMasterHistory.
     * GET|HEAD /supplierMasterHistories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SupplierMasterHistory $supplierMasterHistory */
        $supplierMasterHistory = $this->supplierMasterHistoryRepository->find($id);

        if (empty($supplierMasterHistory)) {
            return $this->sendError('Supplier Master History not found');
        }

        return $this->sendResponse(new SupplierMasterHistoryResource($supplierMasterHistory), 'Supplier Master History retrieved successfully');
    }

    /**
     * Update the specified SupplierMasterHistory in storage.
     * PUT/PATCH /supplierMasterHistories/{id}
     *
     * @param int $id
     * @param UpdateSupplierMasterHistoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupplierMasterHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var SupplierMasterHistory $supplierMasterHistory */
        $supplierMasterHistory = $this->supplierMasterHistoryRepository->find($id);

        if (empty($supplierMasterHistory)) {
            return $this->sendError('Supplier Master History not found');
        }

        $supplierMasterHistory = $this->supplierMasterHistoryRepository->update($input, $id);

        return $this->sendResponse(new SupplierMasterHistoryResource($supplierMasterHistory), 'SupplierMasterHistory updated successfully');
    }

    /**
     * Remove the specified SupplierMasterHistory from storage.
     * DELETE /supplierMasterHistories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SupplierMasterHistory $supplierMasterHistory */
        $supplierMasterHistory = $this->supplierMasterHistoryRepository->find($id);

        if (empty($supplierMasterHistory)) {
            return $this->sendError('Supplier Master History not found');
        }

        $supplierMasterHistory->delete();

        return $this->sendSuccess('Supplier Master History deleted successfully');
    }

    public function ammendKYCApprovalDetails(Request $request)
    {  
        $ammendKYCData = $this->supplierMasterHistoryRepository->saveAmmendData($request);

        if(!$ammendKYCData['status']){
            $statusCode = isset($ammendKYCData['code']) ? $ammendKYCData['code'] : 404;
            return $this->sendError($ammendKYCData['message'], $statusCode);
        }

        return $this->sendResponse([], $ammendKYCData['message']); 
    }
}
