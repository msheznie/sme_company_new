<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpBookingSupplierMasterAPIRequest;
use App\Http\Requests\API\UpdateErpBookingSupplierMasterAPIRequest;
use App\Models\ErpBookingSupplierMaster;
use App\Repositories\ErpBookingSupplierMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpBookingSupplierMasterResource;
use Response;

/**
 * Class ErpBookingSupplierMasterController
 * @package App\Http\Controllers\API
 */

class ErpBookingSupplierMasterAPIController extends AppBaseController
{
    /** @var  ErpBookingSupplierMasterRepository */
    private $erpBookingSupplierMasterRepository;

    public function __construct(ErpBookingSupplierMasterRepository $erpBookingSupplierMasterRepo)
    {
        $this->erpBookingSupplierMasterRepository = $erpBookingSupplierMasterRepo;
    }

    /**
     * Display a listing of the ErpBookingSupplierMaster.
     * GET|HEAD /erpBookingSupplierMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpBookingSupplierMasters = $this->erpBookingSupplierMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpBookingSupplierMasterResource::collection($erpBookingSupplierMasters), 'Erp Booking Supplier Masters retrieved successfully');
    }

    /**
     * Store a newly created ErpBookingSupplierMaster in storage.
     * POST /erpBookingSupplierMasters
     *
     * @param CreateErpBookingSupplierMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpBookingSupplierMasterAPIRequest $request)
    {
        $input = $request->all();

        $erpBookingSupplierMaster = $this->erpBookingSupplierMasterRepository->create($input);

        return $this->sendResponse(new ErpBookingSupplierMasterResource($erpBookingSupplierMaster), 'Erp Booking Supplier Master saved successfully');
    }

    /**
     * Display the specified ErpBookingSupplierMaster.
     * GET|HEAD /erpBookingSupplierMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpBookingSupplierMaster $erpBookingSupplierMaster */
        $erpBookingSupplierMaster = $this->erpBookingSupplierMasterRepository->find($id);

        if (empty($erpBookingSupplierMaster)) {
            return $this->sendError('Erp Booking Supplier Master not found');
        }

        return $this->sendResponse(new ErpBookingSupplierMasterResource($erpBookingSupplierMaster), 'Erp Booking Supplier Master retrieved successfully');
    }

    /**
     * Update the specified ErpBookingSupplierMaster in storage.
     * PUT/PATCH /erpBookingSupplierMasters/{id}
     *
     * @param int $id
     * @param UpdateErpBookingSupplierMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpBookingSupplierMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpBookingSupplierMaster $erpBookingSupplierMaster */
        $erpBookingSupplierMaster = $this->erpBookingSupplierMasterRepository->find($id);

        if (empty($erpBookingSupplierMaster)) {
            return $this->sendError('Erp Booking Supplier Master not found');
        }

        $erpBookingSupplierMaster = $this->erpBookingSupplierMasterRepository->update($input, $id);

        return $this->sendResponse(new ErpBookingSupplierMasterResource($erpBookingSupplierMaster), 'ErpBookingSupplierMaster updated successfully');
    }

    /**
     * Remove the specified ErpBookingSupplierMaster from storage.
     * DELETE /erpBookingSupplierMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpBookingSupplierMaster $erpBookingSupplierMaster */
        $erpBookingSupplierMaster = $this->erpBookingSupplierMasterRepository->find($id);

        if (empty($erpBookingSupplierMaster)) {
            return $this->sendError('Erp Booking Supplier Master not found');
        }

        $erpBookingSupplierMaster->delete();

        return $this->sendSuccess('Erp Booking Supplier Master deleted successfully');
    }
}
