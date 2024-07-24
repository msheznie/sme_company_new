<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractSectionDetailAPIRequest;
use App\Http\Requests\API\UpdateContractSectionDetailAPIRequest;
use App\Models\ContractSectionDetail;
use App\Repositories\ContractSectionDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractSectionDetailResource;
use Response;

/**
 * Class ContractSectionDetailController
 * @package App\Http\Controllers\API
 */

class ContractSectionDetailAPIController extends AppBaseController
{
    /** @var  ContractSectionDetailRepository */
    private $contractSectionDetailRepository;

    public function __construct(ContractSectionDetailRepository $contractSectionDetailRepo)
    {
        $this->contractSectionDetailRepository = $contractSectionDetailRepo;
    }

    /**
     * Display a listing of the ContractSectionDetail.
     * GET|HEAD /contractSectionDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractSectionDetails = $this->contractSectionDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractSectionDetailResource::collection($contractSectionDetails), 'Contract Section Details retrieved successfully');
    }

    /**
     * Store a newly created ContractSectionDetail in storage.
     * POST /contractSectionDetails
     *
     * @param CreateContractSectionDetailAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractSectionDetailAPIRequest $request)
    {
        $input = $request->all();

        $contractSectionDetail = $this->contractSectionDetailRepository->create($input);

        return $this->sendResponse(new ContractSectionDetailResource($contractSectionDetail), 'Contract Section Detail saved successfully');
    }

    /**
     * Display the specified ContractSectionDetail.
     * GET|HEAD /contractSectionDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractSectionDetail $contractSectionDetail */
        $contractSectionDetail = $this->contractSectionDetailRepository->find($id);

        if (empty($contractSectionDetail)) {
            return $this->sendError('Contract Section Detail not found');
        }

        return $this->sendResponse(new ContractSectionDetailResource($contractSectionDetail), 'Contract Section Detail retrieved successfully');
    }

    /**
     * Update the specified ContractSectionDetail in storage.
     * PUT/PATCH /contractSectionDetails/{id}
     *
     * @param int $id
     * @param UpdateContractSectionDetailAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractSectionDetailAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractSectionDetail $contractSectionDetail */
        $contractSectionDetail = $this->contractSectionDetailRepository->find($id);

        if (empty($contractSectionDetail)) {
            return $this->sendError('Contract Section Detail not found');
        }

        $contractSectionDetail = $this->contractSectionDetailRepository->update($input, $id);

        return $this->sendResponse(new ContractSectionDetailResource($contractSectionDetail), 'ContractSectionDetail updated successfully');
    }

    /**
     * Remove the specified ContractSectionDetail from storage.
     * DELETE /contractSectionDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractSectionDetail $contractSectionDetail */
        $contractSectionDetail = $this->contractSectionDetailRepository->find($id);

        if (empty($contractSectionDetail)) {
            return $this->sendError('Contract Section Detail not found');
        }

        $contractSectionDetail->delete();

        return $this->sendSuccess('Contract Section Detail deleted successfully');
    }
}
