<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSupplierContactDetailsAPIRequest;
use App\Http\Requests\API\UpdateSupplierContactDetailsAPIRequest;
use App\Models\SupplierContactDetails;
use App\Repositories\SupplierContactDetailsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SupplierContactDetailsResource;
use Response;

/**
 * Class SupplierContactDetailsController
 * @package App\Http\Controllers\API
 */

class SupplierContactDetailsAPIController extends AppBaseController
{
    /** @var  SupplierContactDetailsRepository */
    private $supplierContactDetailsRepository;

    public function __construct(SupplierContactDetailsRepository $supplierContactDetailsRepo)
    {
        $this->supplierContactDetailsRepository = $supplierContactDetailsRepo;
    }

    /**
     * Display a listing of the SupplierContactDetails.
     * GET|HEAD /supplierContactDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $supplierContactDetails = $this->supplierContactDetailsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SupplierContactDetailsResource::collection($supplierContactDetails), 'Supplier Contact Details retrieved successfully');
    }

    /**
     * Store a newly created SupplierContactDetails in storage.
     * POST /supplierContactDetails
     *
     * @param CreateSupplierContactDetailsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSupplierContactDetailsAPIRequest $request)
    {
        $input = $request->all();

        $supplierContactDetails = $this->supplierContactDetailsRepository->create($input);

        return $this->sendResponse(new SupplierContactDetailsResource($supplierContactDetails), 'Supplier Contact Details saved successfully');
    }

    /**
     * Display the specified SupplierContactDetails.
     * GET|HEAD /supplierContactDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SupplierContactDetails $supplierContactDetails */
        $supplierContactDetails = $this->supplierContactDetailsRepository->find($id);

        if (empty($supplierContactDetails)) {
            return $this->sendError('Supplier Contact Details not found');
        }

        return $this->sendResponse(new SupplierContactDetailsResource($supplierContactDetails), 'Supplier Contact Details retrieved successfully');
    }

    /**
     * Update the specified SupplierContactDetails in storage.
     * PUT/PATCH /supplierContactDetails/{id}
     *
     * @param int $id
     * @param UpdateSupplierContactDetailsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupplierContactDetailsAPIRequest $request)
    {
        $input = $request->all();

        /** @var SupplierContactDetails $supplierContactDetails */
        $supplierContactDetails = $this->supplierContactDetailsRepository->find($id);

        if (empty($supplierContactDetails)) {
            return $this->sendError('Supplier Contact Details not found');
        }

        $supplierContactDetails = $this->supplierContactDetailsRepository->update($input, $id);

        return $this->sendResponse(new SupplierContactDetailsResource($supplierContactDetails), 'SupplierContactDetails updated successfully');
    }

    /**
     * Remove the specified SupplierContactDetails from storage.
     * DELETE /supplierContactDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SupplierContactDetails $supplierContactDetails */
        $supplierContactDetails = $this->supplierContactDetailsRepository->find($id);

        if (empty($supplierContactDetails)) {
            return $this->sendError('Supplier Contact Details not found');
        }

        $supplierContactDetails->delete();

        return $this->sendSuccess('Supplier Contact Details deleted successfully');
    }
}
