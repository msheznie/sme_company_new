<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePurchaseOrderDetailAPIRequest;
use App\Http\Requests\API\UpdatePurchaseOrderDetailAPIRequest;
use App\Models\PurchaseOrderDetail;
use App\Repositories\PurchaseOrderDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PurchaseOrderDetailResource;
use Response;

/**
 * Class PurchaseOrderDetailController
 * @package App\Http\Controllers\API
 */

class PurchaseOrderDetailAPIController extends AppBaseController
{
    /** @var  PurchaseOrderDetailRepository */
    private $purchaseOrderDetailRepository;

    public function __construct(PurchaseOrderDetailRepository $purchaseOrderDetailRepo)
    {
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepo;
    }

    /**
     * Display a listing of the PurchaseOrderDetail.
     * GET|HEAD /purchaseOrderDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $purchaseOrderDetails = $this->purchaseOrderDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PurchaseOrderDetailResource::collection($purchaseOrderDetails), 'Purchase Order Details retrieved successfully');
    }

    /**
     * Store a newly created PurchaseOrderDetail in storage.
     * POST /purchaseOrderDetails
     *
     * @param CreatePurchaseOrderDetailAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePurchaseOrderDetailAPIRequest $request)
    {
        $input = $request->all();

        $purchaseOrderDetail = $this->purchaseOrderDetailRepository->create($input);

        return $this->sendResponse(new PurchaseOrderDetailResource($purchaseOrderDetail), 'Purchase Order Detail saved successfully');
    }

    /**
     * Display the specified PurchaseOrderDetail.
     * GET|HEAD /purchaseOrderDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PurchaseOrderDetail $purchaseOrderDetail */
        $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($id);

        if (empty($purchaseOrderDetail)) {
            return $this->sendError('Purchase Order Detail not found');
        }

        return $this->sendResponse(new PurchaseOrderDetailResource($purchaseOrderDetail), 'Purchase Order Detail retrieved successfully');
    }

    /**
     * Update the specified PurchaseOrderDetail in storage.
     * PUT/PATCH /purchaseOrderDetails/{id}
     *
     * @param int $id
     * @param UpdatePurchaseOrderDetailAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePurchaseOrderDetailAPIRequest $request)
    {
        $input = $request->all();

        /** @var PurchaseOrderDetail $purchaseOrderDetail */
        $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($id);

        if (empty($purchaseOrderDetail)) {
            return $this->sendError('Purchase Order Detail not found');
        }

        $purchaseOrderDetail = $this->purchaseOrderDetailRepository->update($input, $id);

        return $this->sendResponse(new PurchaseOrderDetailResource($purchaseOrderDetail), 'PurchaseOrderDetail updated successfully');
    }

    /**
     * Remove the specified PurchaseOrderDetail from storage.
     * DELETE /purchaseOrderDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PurchaseOrderDetail $purchaseOrderDetail */
        $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($id);

        if (empty($purchaseOrderDetail)) {
            return $this->sendError('Purchase Order Detail not found');
        }

        $purchaseOrderDetail->delete();

        return $this->sendSuccess('Purchase Order Detail deleted successfully');
    }
}
