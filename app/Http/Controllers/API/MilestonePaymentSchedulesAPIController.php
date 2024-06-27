<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMilestonePaymentSchedulesAPIRequest;
use App\Http\Requests\API\UpdateMilestonePaymentSchedulesAPIRequest;
use App\Models\MilestonePaymentSchedules;
use App\Repositories\MilestonePaymentSchedulesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MilestonePaymentSchedulesResource;
use Response;

/**
 * Class MilestonePaymentSchedulesController
 * @package App\Http\Controllers\API
 */

class MilestonePaymentSchedulesAPIController extends AppBaseController
{
    /** @var  MilestonePaymentSchedulesRepository */
    private $milestonePaymentSchedulesRepository;

    public function __construct(MilestonePaymentSchedulesRepository $milestonePaymentSchedulesRepo)
    {
        $this->milestonePaymentSchedulesRepository = $milestonePaymentSchedulesRepo;
    }

    /**
     * Display a listing of the MilestonePaymentSchedules.
     * GET|HEAD /milestonePaymentSchedules
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(MilestonePaymentSchedulesResource::collection($milestonePaymentSchedules),
            'Milestone Payment Schedules retrieved successfully');
    }

    /**
     * Store a newly created MilestonePaymentSchedules in storage.
     * POST /milestonePaymentSchedules
     *
     * @param CreateMilestonePaymentSchedulesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMilestonePaymentSchedulesAPIRequest $request)
    {
        $input = $request->all();

        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->create($input);

        return $this->sendResponse(new MilestonePaymentSchedulesResource($milestonePaymentSchedules),
            'Milestone Payment Schedules saved successfully');
    }

    /**
     * Display the specified MilestonePaymentSchedules.
     * GET|HEAD /milestonePaymentSchedules/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MilestonePaymentSchedules $milestonePaymentSchedules */
        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->find($id);

        if (empty($milestonePaymentSchedules))
        {
            return $this->sendError(trans('common.milestone_payment_schedule_not_found'));
        }

        return $this->sendResponse(new MilestonePaymentSchedulesResource($milestonePaymentSchedules),
            'Milestone Payment Schedules retrieved successfully');
    }

    /**
     * Update the specified MilestonePaymentSchedules in storage.
     * PUT/PATCH /milestonePaymentSchedules/{id}
     *
     * @param int $id
     * @param UpdateMilestonePaymentSchedulesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMilestonePaymentSchedulesAPIRequest $request)
    {
        $input = $request->all();

        /** @var MilestonePaymentSchedules $milestonePaymentSchedules */
        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->find($id);

        if (empty($milestonePaymentSchedules))
        {
            return $this->sendError('Milestone Payment Schedules not found');
        }

        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->update($input, $id);

        return $this->sendResponse(new MilestonePaymentSchedulesResource($milestonePaymentSchedules),
            'MilestonePaymentSchedules updated successfully');
    }

    /**
     * Remove the specified MilestonePaymentSchedules from storage.
     * DELETE /milestonePaymentSchedules/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MilestonePaymentSchedules $milestonePaymentSchedules */
        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->find($id);

        if (empty($milestonePaymentSchedules))
        {
            return $this->sendError('Milestone Payment Schedules not found');
        }

        $milestonePaymentSchedules->delete();

        return $this->sendSuccess('Milestone Payment Schedules deleted successfully');
    }
}
