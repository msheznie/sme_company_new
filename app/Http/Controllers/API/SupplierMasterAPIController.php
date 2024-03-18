<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSupplierMasterAPIRequest;
use App\Http\Requests\API\UpdateSupplierMasterAPIRequest;
use App\Models\SupplierMaster;
use App\Repositories\SupplierMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SupplierMasterResource;
use Response;

/**
 * Class SupplierMasterController
 * @package App\Http\Controllers\API
 */

class SupplierMasterAPIController extends AppBaseController
{
    /** @var  SupplierMasterRepository */
    private $supplierMasterRepository;

    public function __construct(SupplierMasterRepository $supplierMasterRepo)
    {
        $this->supplierMasterRepository = $supplierMasterRepo;
    }

    /**
     * Display a listing of the SupplierMaster.
     * GET|HEAD /supplierMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $supplierMasters = $this->supplierMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SupplierMasterResource::collection($supplierMasters), 'Supplier Masters retrieved successfully');
    }

    /**
     * Store a newly created SupplierMaster in storage.
     * POST /supplierMasters
     *
     * @param CreateSupplierMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSupplierMasterAPIRequest $request)
    {
        $input = $request->all();

        $supplierMaster = $this->supplierMasterRepository->create($input);

        return $this->sendResponse(new SupplierMasterResource($supplierMaster), 'Supplier Master saved successfully');
    }

    /**
     * Display the specified SupplierMaster.
     * GET|HEAD /supplierMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SupplierMaster $supplierMaster */
        $supplierMaster = $this->supplierMasterRepository->find($id);

        if (empty($supplierMaster)) {
            return $this->sendError('Supplier Master not found');
        }

        return $this->sendResponse(new SupplierMasterResource($supplierMaster), 'Supplier Master retrieved successfully');
    }

    /**
     * Update the specified SupplierMaster in storage.
     * PUT/PATCH /supplierMasters/{id}
     *
     * @param int $id
     * @param UpdateSupplierMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupplierMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var SupplierMaster $supplierMaster */
        $supplierMaster = $this->supplierMasterRepository->find($id);

        if (empty($supplierMaster)) {
            return $this->sendError('Supplier Master not found');
        }

        $supplierMaster = $this->supplierMasterRepository->update($input, $id);

        return $this->sendResponse(new SupplierMasterResource($supplierMaster), 'SupplierMaster updated successfully');
    }

    /**
     * Remove the specified SupplierMaster from storage.
     * DELETE /supplierMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SupplierMaster $supplierMaster */
        $supplierMaster = $this->supplierMasterRepository->find($id);

        if (empty($supplierMaster)) {
            return $this->sendError('Supplier Master not found');
        }

        $supplierMaster->delete();

        return $this->sendSuccess('Supplier Master deleted successfully');
    }
}
