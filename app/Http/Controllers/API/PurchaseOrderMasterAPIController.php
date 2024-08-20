<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePurchaseOrderMasterAPIRequest;
use App\Http\Requests\API\UpdatePurchaseOrderMasterAPIRequest;
use App\Models\PurchaseOrderMaster;
use App\Repositories\PurchaseOrderMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PurchaseOrderMasterResource;
use Response;

/**
 * Class PurchaseOrderMasterController
 * @package App\Http\Controllers\API
 */

class PurchaseOrderMasterAPIController extends AppBaseController
{
    /** @var  PurchaseOrderMasterRepository */
    private $purchaseOrderMasterRepository;

    public function __construct(PurchaseOrderMasterRepository $purchaseOrderMasterRepo)
    {
        $this->purchaseOrderMasterRepository = $purchaseOrderMasterRepo;
    }

    /**
     * Display a listing of the PurchaseOrderMaster.
     * GET|HEAD /purchaseOrderMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $purchaseOrderMasters = $this->purchaseOrderMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PurchaseOrderMasterResource::collection($purchaseOrderMasters), 'Purchase Order Masters retrieved successfully');
    }

    /**
     * Store a newly created PurchaseOrderMaster in storage.
     * POST /purchaseOrderMasters
     *
     * @param CreatePurchaseOrderMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePurchaseOrderMasterAPIRequest $request)
    {
        $input = $request->all();

        $purchaseOrderMaster = $this->purchaseOrderMasterRepository->create($input);

        return $this->sendResponse(new PurchaseOrderMasterResource($purchaseOrderMaster), 'Purchase Order Master saved successfully');
    }

    /**
     * Display the specified PurchaseOrderMaster.
     * GET|HEAD /purchaseOrderMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PurchaseOrderMaster $purchaseOrderMaster */
        $purchaseOrderMaster = $this->purchaseOrderMasterRepository->find($id);

        if (empty($purchaseOrderMaster)) {
            return $this->sendError('Purchase Order Master not found');
        }

        return $this->sendResponse(new PurchaseOrderMasterResource($purchaseOrderMaster), 'Purchase Order Master retrieved successfully');
    }

    /**
     * Update the specified PurchaseOrderMaster in storage.
     * PUT/PATCH /purchaseOrderMasters/{id}
     *
     * @param int $id
     * @param UpdatePurchaseOrderMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePurchaseOrderMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var PurchaseOrderMaster $purchaseOrderMaster */
        $purchaseOrderMaster = $this->purchaseOrderMasterRepository->find($id);

        if (empty($purchaseOrderMaster)) {
            return $this->sendError('Purchase Order Master not found');
        }

        $purchaseOrderMaster = $this->purchaseOrderMasterRepository->update($input, $id);

        return $this->sendResponse(new PurchaseOrderMasterResource($purchaseOrderMaster), 'PurchaseOrderMaster updated successfully');
    }

    /**
     * Remove the specified PurchaseOrderMaster from storage.
     * DELETE /purchaseOrderMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PurchaseOrderMaster $purchaseOrderMaster */
        $purchaseOrderMaster = $this->purchaseOrderMasterRepository->find($id);

        if (empty($purchaseOrderMaster)) {
            return $this->sendError('Purchase Order Master not found');
        }

        $purchaseOrderMaster->delete();

        return $this->sendSuccess('Purchase Order Master deleted successfully');
    }
}
