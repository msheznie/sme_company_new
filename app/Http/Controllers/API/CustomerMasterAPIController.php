<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCustomerMasterAPIRequest;
use App\Http\Requests\API\UpdateCustomerMasterAPIRequest;
use App\Models\CustomerMaster;
use App\Repositories\CustomerMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CustomerMasterResource;
use Response;

/**
 * Class CustomerMasterController
 * @package App\Http\Controllers\API
 */

class CustomerMasterAPIController extends AppBaseController
{
    /** @var  CustomerMasterRepository */
    private $customerMasterRepository;

    public function __construct(CustomerMasterRepository $customerMasterRepo)
    {
        $this->customerMasterRepository = $customerMasterRepo;
    }

    /**
     * Display a listing of the CustomerMaster.
     * GET|HEAD /customerMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $customerMasters = $this->customerMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CustomerMasterResource::collection($customerMasters), 'Customer Masters retrieved successfully');
    }

    /**
     * Store a newly created CustomerMaster in storage.
     * POST /customerMasters
     *
     * @param CreateCustomerMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCustomerMasterAPIRequest $request)
    {
        $input = $request->all();

        $customerMaster = $this->customerMasterRepository->create($input);

        return $this->sendResponse(new CustomerMasterResource($customerMaster), 'Customer Master saved successfully');
    }

    /**
     * Display the specified CustomerMaster.
     * GET|HEAD /customerMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CustomerMaster $customerMaster */
        $customerMaster = $this->customerMasterRepository->find($id);

        if (empty($customerMaster)) {
            return $this->sendError('Customer Master not found');
        }

        return $this->sendResponse(new CustomerMasterResource($customerMaster), 'Customer Master retrieved successfully');
    }

    /**
     * Update the specified CustomerMaster in storage.
     * PUT/PATCH /customerMasters/{id}
     *
     * @param int $id
     * @param UpdateCustomerMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCustomerMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CustomerMaster $customerMaster */
        $customerMaster = $this->customerMasterRepository->find($id);

        if (empty($customerMaster)) {
            return $this->sendError('Customer Master not found');
        }

        $customerMaster = $this->customerMasterRepository->update($input, $id);

        return $this->sendResponse(new CustomerMasterResource($customerMaster), 'CustomerMaster updated successfully');
    }

    /**
     * Remove the specified CustomerMaster from storage.
     * DELETE /customerMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CustomerMaster $customerMaster */
        $customerMaster = $this->customerMasterRepository->find($id);

        if (empty($customerMaster)) {
            return $this->sendError('Customer Master not found');
        }

        $customerMaster->delete();

        return $this->sendSuccess('Customer Master deleted successfully');
    }
}
