<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePaySupplierInvoiceDetailAPIRequest;
use App\Http\Requests\API\UpdatePaySupplierInvoiceDetailAPIRequest;
use App\Models\PaySupplierInvoiceDetail;
use App\Repositories\PaySupplierInvoiceDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PaySupplierInvoiceDetailResource;
use Response;

/**
 * Class PaySupplierInvoiceDetailController
 * @package App\Http\Controllers\API
 */

class PaySupplierInvoiceDetailAPIController extends AppBaseController
{
    /** @var  PaySupplierInvoiceDetailRepository */
    private $paySupplierInvoiceDetailRepository;
    protected $message = 'Pay Supplier Invoice Detail not found';

    public function __construct(PaySupplierInvoiceDetailRepository $paySupplierInvoiceDetailRepo)
    {
        $this->paySupplierInvoiceDetailRepository = $paySupplierInvoiceDetailRepo;
    }

    /**
     * Display a listing of the PaySupplierInvoiceDetail.
     * GET|HEAD /paySupplierInvoiceDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $paySupplierInvoiceDetails = $this->paySupplierInvoiceDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PaySupplierInvoiceDetailResource::collection($paySupplierInvoiceDetails),
            'Pay Supplier Invoice Details retrieved successfully');
    }

    /**
     * Store a newly created PaySupplierInvoiceDetail in storage.
     * POST /paySupplierInvoiceDetails
     *
     * @param CreatePaySupplierInvoiceDetailAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePaySupplierInvoiceDetailAPIRequest $request)
    {
        $input = $request->all();

        $paySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepository->create($input);

        return $this->sendResponse(new PaySupplierInvoiceDetailResource($paySupplierInvoiceDetail),
            'Pay Supplier Invoice Detail saved successfully');
    }

    /**
     * Display the specified PaySupplierInvoiceDetail.
     * GET|HEAD /paySupplierInvoiceDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PaySupplierInvoiceDetail $paySupplierInvoiceDetail */
        $paySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepository->find($id);

        if (empty($paySupplierInvoiceDetail))
        {
            return $this->sendError($this->message);
        }

        return $this->sendResponse(new PaySupplierInvoiceDetailResource($paySupplierInvoiceDetail),
            'Pay Supplier Invoice Detail retrieved successfully');
    }

    /**
     * Update the specified PaySupplierInvoiceDetail in storage.
     * PUT/PATCH /paySupplierInvoiceDetails/{id}
     *
     * @param int $id
     * @param UpdatePaySupplierInvoiceDetailAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaySupplierInvoiceDetailAPIRequest $request)
    {
        $input = $request->all();

        /** @var PaySupplierInvoiceDetail $paySupplierInvoiceDetail */
        $paySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepository->find($id);

        if (empty($paySupplierInvoiceDetail))
        {
            return $this->sendError($this->message);
        }

        $paySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepository->update($input, $id);

        return $this->sendResponse(new PaySupplierInvoiceDetailResource($paySupplierInvoiceDetail),
            'PaySupplierInvoiceDetail updated successfully');
    }

    /**
     * Remove the specified PaySupplierInvoiceDetail from storage.
     * DELETE /paySupplierInvoiceDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PaySupplierInvoiceDetail $paySupplierInvoiceDetail */
        $paySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepository->find($id);

        if (empty($paySupplierInvoiceDetail))
        {
            return $this->sendError($this->message);
        }

        $paySupplierInvoiceDetail->delete();

        return $this->sendSuccess('Pay Supplier Invoice Detail deleted successfully');
    }
}
