<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpDirectInvoiceDetailsAPIRequest;
use App\Http\Requests\API\UpdateErpDirectInvoiceDetailsAPIRequest;
use App\Models\ErpDirectInvoiceDetails;
use App\Repositories\ErpDirectInvoiceDetailsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpDirectInvoiceDetailsResource;
use Response;

/**
 * Class ErpDirectInvoiceDetailsController
 * @package App\Http\Controllers\API
 */

class ErpDirectInvoiceDetailsAPIController extends AppBaseController
{
    /** @var  ErpDirectInvoiceDetailsRepository */
    private $erpDirectInvoiceDetailsRepository;

    public function __construct(ErpDirectInvoiceDetailsRepository $erpDirectInvoiceDetailsRepo)
    {
        $this->erpDirectInvoiceDetailsRepository = $erpDirectInvoiceDetailsRepo;
    }

    /**
     * Display a listing of the ErpDirectInvoiceDetails.
     * GET|HEAD /erpDirectInvoiceDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpDirectInvoiceDetailsResource::collection($erpDirectInvoiceDetails), 'Erp Direct Invoice Details retrieved successfully');
    }

    /**
     * Store a newly created ErpDirectInvoiceDetails in storage.
     * POST /erpDirectInvoiceDetails
     *
     * @param CreateErpDirectInvoiceDetailsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpDirectInvoiceDetailsAPIRequest $request)
    {
        $input = $request->all();

        $erpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepository->create($input);

        return $this->sendResponse(new ErpDirectInvoiceDetailsResource($erpDirectInvoiceDetails), 'Erp Direct Invoice Details saved successfully');
    }

    /**
     * Display the specified ErpDirectInvoiceDetails.
     * GET|HEAD /erpDirectInvoiceDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpDirectInvoiceDetails $erpDirectInvoiceDetails */
        $erpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepository->find($id);

        if (empty($erpDirectInvoiceDetails)) {
            return $this->sendError('Erp Direct Invoice Details not found');
        }

        return $this->sendResponse(new ErpDirectInvoiceDetailsResource($erpDirectInvoiceDetails), 'Erp Direct Invoice Details retrieved successfully');
    }

    /**
     * Update the specified ErpDirectInvoiceDetails in storage.
     * PUT/PATCH /erpDirectInvoiceDetails/{id}
     *
     * @param int $id
     * @param UpdateErpDirectInvoiceDetailsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpDirectInvoiceDetailsAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpDirectInvoiceDetails $erpDirectInvoiceDetails */
        $erpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepository->find($id);

        if (empty($erpDirectInvoiceDetails)) {
            return $this->sendError('Erp Direct Invoice Details not found');
        }

        $erpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepository->update($input, $id);

        return $this->sendResponse(new ErpDirectInvoiceDetailsResource($erpDirectInvoiceDetails), 'ErpDirectInvoiceDetails updated successfully');
    }

    /**
     * Remove the specified ErpDirectInvoiceDetails from storage.
     * DELETE /erpDirectInvoiceDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpDirectInvoiceDetails $erpDirectInvoiceDetails */
        $erpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepository->find($id);

        if (empty($erpDirectInvoiceDetails)) {
            return $this->sendError('Erp Direct Invoice Details not found');
        }

        $erpDirectInvoiceDetails->delete();

        return $this->sendSuccess('Erp Direct Invoice Details deleted successfully');
    }
}
