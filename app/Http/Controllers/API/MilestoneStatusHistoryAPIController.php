<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMilestoneStatusHistoryAPIRequest;
use App\Http\Requests\API\UpdateMilestoneStatusHistoryAPIRequest;
use App\Models\MilestoneStatusHistory;
use App\Repositories\MilestoneStatusHistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MilestoneStatusHistoryResource;
use Response;

/**
 * Class MilestoneStatusHistoryController
 * @package App\Http\Controllers\API
 */

class MilestoneStatusHistoryAPIController extends AppBaseController
{
    /** @var  MilestoneStatusHistoryRepository */
    private $milestoneStatusHistoryRepository;

    public function __construct(MilestoneStatusHistoryRepository $milestoneStatusHistoryRepo)
    {
        $this->milestoneStatusHistoryRepository = $milestoneStatusHistoryRepo;
    }

    /**
     * Display a listing of the MilestoneStatusHistory.
     * GET|HEAD /milestoneStatusHistories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $milestoneStatusHistories = $this->milestoneStatusHistoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(MilestoneStatusHistoryResource::collection($milestoneStatusHistories),
            'Milestone Status Histories retrieved successfully');
    }

    /**
     * Store a newly created MilestoneStatusHistory in storage.
     * POST /milestoneStatusHistories
     *
     * @param CreateMilestoneStatusHistoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMilestoneStatusHistoryAPIRequest $request)
    {
        $input = $request->all();

        $milestoneStatusHistory = $this->milestoneStatusHistoryRepository->create($input);

        return $this->sendResponse(new MilestoneStatusHistoryResource($milestoneStatusHistory),
            'Milestone Status History saved successfully');
    }

    /**
     * Display the specified MilestoneStatusHistory.
     * GET|HEAD /milestoneStatusHistories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MilestoneStatusHistory $milestoneStatusHistory */
        $milestoneStatusHistory = $this->milestoneStatusHistoryRepository->find($id);

        if (empty($milestoneStatusHistory)) {
            return $this->sendError('Milestone Status History not found');
        }

        return $this->sendResponse(new MilestoneStatusHistoryResource($milestoneStatusHistory),
            'Milestone Status History retrieved successfully');
    }

    /**
     * Update the specified MilestoneStatusHistory in storage.
     * PUT/PATCH /milestoneStatusHistories/{id}
     *
     * @param int $id
     * @param UpdateMilestoneStatusHistoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMilestoneStatusHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var MilestoneStatusHistory $milestoneStatusHistory */
        $milestoneStatusHistory = $this->milestoneStatusHistoryRepository->find($id);

        if (empty($milestoneStatusHistory)) {
            return $this->sendError('Milestone Status History not found');
        }

        $milestoneStatusHistory = $this->milestoneStatusHistoryRepository->update($input, $id);

        return $this->sendResponse(new MilestoneStatusHistoryResource($milestoneStatusHistory),
            'MilestoneStatusHistory updated successfully');
    }

    /**
     * Remove the specified MilestoneStatusHistory from storage.
     * DELETE /milestoneStatusHistories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MilestoneStatusHistory $milestoneStatusHistory */
        $milestoneStatusHistory = $this->milestoneStatusHistoryRepository->find($id);

        if (empty($milestoneStatusHistory)) {
            return $this->sendError('Milestone Status History not found');
        }

        $milestoneStatusHistory->delete();

        return $this->sendSuccess('Milestone Status History deleted successfully');
    }
    public function getMilestoneStatusHistory(Request $request) {
        return $this->milestoneStatusHistoryRepository->getMilestoneStatusHistory($request);

    }
}
