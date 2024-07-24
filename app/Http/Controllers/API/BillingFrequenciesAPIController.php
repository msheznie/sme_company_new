<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBillingFrequenciesAPIRequest;
use App\Http\Requests\API\UpdateBillingFrequenciesAPIRequest;
use App\Models\BillingFrequencies;
use App\Repositories\BillingFrequenciesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BillingFrequenciesResource;
use Response;

/**
 * Class BillingFrequenciesController
 * @package App\Http\Controllers\API
 */

class BillingFrequenciesAPIController extends AppBaseController
{
    /** @var  BillingFrequenciesRepository */
    private $billingFrequenciesRepository;

    public function __construct(BillingFrequenciesRepository $billingFrequenciesRepo)
    {
        $this->billingFrequenciesRepository = $billingFrequenciesRepo;
    }

    /**
     * Display a listing of the BillingFrequencies.
     * GET|HEAD /billingFrequencies
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $billingFrequencies = $this->billingFrequenciesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BillingFrequenciesResource::collection($billingFrequencies),
            'Billing Frequencies retrieved successfully');
    }

    /**
     * Store a newly created BillingFrequencies in storage.
     * POST /billingFrequencies
     *
     * @param CreateBillingFrequenciesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBillingFrequenciesAPIRequest $request)
    {
        $input = $request->all();

        $billingFrequencies = $this->billingFrequenciesRepository->create($input);

        return $this->sendResponse(new BillingFrequenciesResource($billingFrequencies),
            'Billing Frequencies saved successfully');
    }

    /**
     * Display the specified BillingFrequencies.
     * GET|HEAD /billingFrequencies/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BillingFrequencies $billingFrequencies */
        $billingFrequencies = $this->billingFrequenciesRepository->find($id);

        if (empty($billingFrequencies))
        {
            return $this->sendError(trans('common.billing_frequencies_not_found'));
        }

        return $this->sendResponse(new BillingFrequenciesResource($billingFrequencies),
            'Billing Frequencies retrieved successfully');
    }

    /**
     * Update the specified BillingFrequencies in storage.
     * PUT/PATCH /billingFrequencies/{id}
     *
     * @param int $id
     * @param UpdateBillingFrequenciesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillingFrequenciesAPIRequest $request)
    {
        $input = $request->all();

        /** @var BillingFrequencies $billingFrequencies */
        $billingFrequencies = $this->billingFrequenciesRepository->find($id);

        if (empty($billingFrequencies))
        {
            return $this->sendError('Billing Frequencies not found');
        }

        $billingFrequencies = $this->billingFrequenciesRepository->update($input, $id);

        return $this->sendResponse(new BillingFrequenciesResource($billingFrequencies),
            'BillingFrequencies updated successfully');
    }

    /**
     * Remove the specified BillingFrequencies from storage.
     * DELETE /billingFrequencies/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BillingFrequencies $billingFrequencies */
        $billingFrequencies = $this->billingFrequenciesRepository->find($id);

        if (empty($billingFrequencies))
        {
            return $this->sendError('Billing Frequencies not found');
        }

        $billingFrequencies->delete();

        return $this->sendSuccess('Billing Frequencies deleted successfully');
    }
}
