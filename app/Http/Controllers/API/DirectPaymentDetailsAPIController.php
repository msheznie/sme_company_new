<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDirectPaymentDetailsAPIRequest;
use App\Http\Requests\API\UpdateDirectPaymentDetailsAPIRequest;
use App\Models\DirectPaymentDetails;
use App\Repositories\DirectPaymentDetailsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DirectPaymentDetailsResource;
use Response;

/**
 * Class DirectPaymentDetailsController
 * @package App\Http\Controllers\API
 */

class DirectPaymentDetailsAPIController extends AppBaseController
{
    /** @var  DirectPaymentDetailsRepository */
    private $directPaymentDetailsRepository;
    protected $errorNotFound = 'Direct Payment Details not found';

    public function __construct(DirectPaymentDetailsRepository $directPaymentDetailsRepo)
    {
        $this->directPaymentDetailsRepository = $directPaymentDetailsRepo;
    }

    /**
     * Display a listing of the DirectPaymentDetails.
     * GET|HEAD /directPaymentDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $directPaymentDetails = $this->directPaymentDetailsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(DirectPaymentDetailsResource::collection($directPaymentDetails),
            'Direct Payment Details retrieved successfully');
    }

    /**
     * Store a newly created DirectPaymentDetails in storage.
     * POST /directPaymentDetails
     *
     * @param CreateDirectPaymentDetailsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDirectPaymentDetailsAPIRequest $request)
    {
        $input = $request->all();

        $directPaymentDetails = $this->directPaymentDetailsRepository->create($input);

        return $this->sendResponse(new DirectPaymentDetailsResource($directPaymentDetails),
            'Direct Payment Details saved successfully');
    }

    /**
     * Display the specified DirectPaymentDetails.
     * GET|HEAD /directPaymentDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DirectPaymentDetails $directPaymentDetails */
        $directPaymentDetails = $this->directPaymentDetailsRepository->find($id);

        if (empty($directPaymentDetails))
        {
            return $this->sendError($this->errorNotFound);
        }

        return $this->sendResponse(new DirectPaymentDetailsResource($directPaymentDetails),
            'Direct Payment Details retrieved successfully');
    }

    /**
     * Update the specified DirectPaymentDetails in storage.
     * PUT/PATCH /directPaymentDetails/{id}
     *
     * @param int $id
     * @param UpdateDirectPaymentDetailsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDirectPaymentDetailsAPIRequest $request)
    {
        $input = $request->all();

        /** @var DirectPaymentDetails $directPaymentDetails */
        $directPaymentDetails = $this->directPaymentDetailsRepository->find($id);

        if (empty($directPaymentDetails))
        {
            return $this->sendError($this->errorNotFound);
        }

        $directPaymentDetails = $this->directPaymentDetailsRepository->update($input, $id);

        return $this->sendResponse(new DirectPaymentDetailsResource($directPaymentDetails),
            'DirectPaymentDetails updated successfully');
    }

    /**
     * Remove the specified DirectPaymentDetails from storage.
     * DELETE /directPaymentDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DirectPaymentDetails $directPaymentDetails */
        $directPaymentDetails = $this->directPaymentDetailsRepository->find($id);

        if (empty($directPaymentDetails))
        {
            return $this->sendError($this->errorNotFound);
        }

        $directPaymentDetails->delete();

        return $this->sendSuccess('Direct Payment Details deleted successfully');
    }
}
