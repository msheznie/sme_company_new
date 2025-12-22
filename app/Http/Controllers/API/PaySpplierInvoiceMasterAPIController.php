<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePaySpplierInvoiceMasterAPIRequest;
use App\Http\Requests\API\UpdatePaySpplierInvoiceMasterAPIRequest;
use App\Models\PaySpplierInvoiceMaster;
use App\Repositories\PaySpplierInvoiceMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PaySpplierInvoiceMasterResource;
use Response;

/**
 * Class PaySpplierInvoiceMasterController
 * @package App\Http\Controllers\API
 */

class PaySpplierInvoiceMasterAPIController extends AppBaseController
{
    /** @var  PaySpplierInvoiceMasterRepository */
    private $paySpplierInvoiceMasterRepository;
    protected $errorNotFound = 'Pay Supplier Invoice Master not found';

    public function __construct(PaySpplierInvoiceMasterRepository $paySpplierInvoiceMasterRepo)
    {
        $this->paySpplierInvoiceMasterRepository = $paySpplierInvoiceMasterRepo;
    }

    /**
     * Display a listing of the PaySpplierInvoiceMaster.
     * GET|HEAD /paySpplierInvoiceMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $paySpplierInvoiceMasters = $this->paySpplierInvoiceMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PaySpplierInvoiceMasterResource::collection($paySpplierInvoiceMasters),
            'Pay Spplier Invoice Masters retrieved successfully');
    }

    /**
     * Store a newly created PaySpplierInvoiceMaster in storage.
     * POST /paySpplierInvoiceMasters
     *
     * @param CreatePaySpplierInvoiceMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePaySpplierInvoiceMasterAPIRequest $request)
    {
        $input = $request->all();

        $paySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepository->create($input);

        return $this->sendResponse(new PaySpplierInvoiceMasterResource($paySpplierInvoiceMaster),
            'Pay Spplier Invoice Master saved successfully');
    }

    /**
     * Display the specified PaySpplierInvoiceMaster.
     * GET|HEAD /paySpplierInvoiceMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PaySpplierInvoiceMaster $paySpplierInvoiceMaster */
        $paySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepository->find($id);

        if (empty($paySpplierInvoiceMaster))
        {
            return $this->sendError($this->errorNotFound);
        }

        return $this->sendResponse(new PaySpplierInvoiceMasterResource($paySpplierInvoiceMaster),
            'Pay Spplier Invoice Master retrieved successfully');
    }

    /**
     * Update the specified PaySpplierInvoiceMaster in storage.
     * PUT/PATCH /paySpplierInvoiceMasters/{id}
     *
     * @param int $id
     * @param UpdatePaySpplierInvoiceMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaySpplierInvoiceMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var PaySpplierInvoiceMaster $paySpplierInvoiceMaster */
        $paySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepository->find($id);

        if (empty($paySpplierInvoiceMaster))
        {
            return $this->sendError($this->errorNotFound);
        }

        $paySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepository->update($input, $id);

        return $this->sendResponse(new PaySpplierInvoiceMasterResource($paySpplierInvoiceMaster),
            'PaySpplierInvoiceMaster updated successfully');
    }

    /**
     * Remove the specified PaySpplierInvoiceMaster from storage.
     * DELETE /paySpplierInvoiceMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PaySpplierInvoiceMaster $paySpplierInvoiceMaster */
        $paySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepository->find($id);

        if (empty($paySpplierInvoiceMaster))
        {
            return $this->sendError($this->errorNotFound);
        }

        $paySpplierInvoiceMaster->delete();

        return $this->sendSuccess('Pay Spplier Invoice Master deleted successfully');
    }
}
