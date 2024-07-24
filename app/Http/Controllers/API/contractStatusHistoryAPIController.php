<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatecontractStatusHistoryAPIRequest;
use App\Http\Requests\API\UpdatecontractStatusHistoryAPIRequest;
use App\Models\contractStatusHistory;
use App\Repositories\contractStatusHistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\contractStatusHistoryResource;
use Response;

/**
 * Class contractStatusHistoryController
 * @package App\Http\Controllers\API
 */

class contractStatusHistoryAPIController extends AppBaseController
{
    /** @var  contractStatusHistoryRepository */
    private $contractStatusHistoryRepository;

    public function __construct(contractStatusHistoryRepository $contractStatusHistoryRepo)
    {
        $this->contractStatusHistoryRepository = $contractStatusHistoryRepo;
    }

    /**
     * Display a listing of the contractStatusHistory.
     * GET|HEAD /contractStatusHistories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractStatusHistories = $this->contractStatusHistoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse
        (
            contractStatusHistoryResource::collection
            ($contractStatusHistories), 'Contract Status Histories retrieved successfully'
        );
    }

    /**
     * Store a newly created contractStatusHistory in storage.
     * POST /contractStatusHistories
     *
     * @param CreatecontractStatusHistoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatecontractStatusHistoryAPIRequest $request)
    {
        $input = $request->all();

        $contractStatusHistory = $this->contractStatusHistoryRepository->create($input);

        return $this->sendResponse
        (new contractStatusHistoryResource($contractStatusHistory), 'Contract Status History saved successfully');
    }

    /**
     * Display the specified contractStatusHistory.
     * GET|HEAD /contractStatusHistories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var contractStatusHistory $contractStatusHistory */
        $contractStatusHistory = $this->contractStatusHistoryRepository->find($id);

        if (empty($contractStatusHistory))
        {
            return $this->sendError('Contract Status History not found');
        }

        return $this->sendResponse(
            new contractStatusHistoryResource($contractStatusHistory), 'Contract Status History retrieved successfully'
        );
    }

    /**
     * Update the specified contractStatusHistory in storage.
     * PUT/PATCH /contractStatusHistories/{id}
     *
     * @param int $id
     * @param UpdatecontractStatusHistoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatecontractStatusHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var contractStatusHistory $contractStatusHistory */
        $contractStatusHistory = $this->contractStatusHistoryRepository->find($id);

        if (empty($contractStatusHistory))
        {
            return $this->sendError('Contract Status History not found');
        }

        $contractStatusHistory = $this->contractStatusHistoryRepository->update($input, $id);

        return $this->sendResponse
        (
            new contractStatusHistoryResource($contractStatusHistory), 'contractStatusHistory updated successfully'
        );
    }

    /**
     * Remove the specified contractStatusHistory from storage.
     * DELETE /contractStatusHistories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var contractStatusHistory $contractStatusHistory */
        $contractStatusHistory = $this->contractStatusHistoryRepository->find($id);

        if (empty($contractStatusHistory))
        {
            return $this->sendError('Contract Status History not found');
        }

        $contractStatusHistory->delete();

        return $this->sendSuccess('Contract Status History deleted successfully');
    }

    public function getContractListStatus(Request $request)
    {
        try
        {
            return $this->contractStatusHistoryRepository->getContractListStatus($request);
        } catch (\Exception $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
