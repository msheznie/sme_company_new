<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTimeMaterialConsumptionAmdAPIRequest;
use App\Http\Requests\API\UpdateTimeMaterialConsumptionAmdAPIRequest;
use App\Models\TimeMaterialConsumptionAmd;
use App\Repositories\TimeMaterialConsumptionAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TimeMaterialConsumptionAmdResource;
use Response;

/**
 * Class TimeMaterialConsumptionAmdController
 * @package App\Http\Controllers\API
 */

class TimeMaterialConsumptionAmdAPIController extends AppBaseController
{
    /** @var  TimeMaterialConsumptionAmdRepository */
    private $timeMaterialConsumptionAmdRepository;
    protected $errorMessage = 'Time Material Consumption Amd not found';

    public function __construct(TimeMaterialConsumptionAmdRepository $timeMaterialConsumptionAmdRepo)
    {
        $this->timeMaterialConsumptionAmdRepository = $timeMaterialConsumptionAmdRepo;
    }

    /**
     * Display a listing of the TimeMaterialConsumptionAmd.
     * GET|HEAD /timeMaterialConsumptionAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $timeMaterialConsumptionAmds = $this->timeMaterialConsumptionAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(TimeMaterialConsumptionAmdResource::collection($timeMaterialConsumptionAmds),
            'Time Material Consumption Amds retrieved successfully');
    }

    /**
     * Store a newly created TimeMaterialConsumptionAmd in storage.
     * POST /timeMaterialConsumptionAmds
     *
     * @param CreateTimeMaterialConsumptionAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTimeMaterialConsumptionAmdAPIRequest $request)
    {
        $input = $request->all();

        $timeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepository->create($input);

        return $this->sendResponse(new TimeMaterialConsumptionAmdResource($timeMaterialConsumptionAmd),
            'Time Material Consumption Amd saved successfully');
    }

    /**
     * Display the specified TimeMaterialConsumptionAmd.
     * GET|HEAD /timeMaterialConsumptionAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TimeMaterialConsumptionAmd $timeMaterialConsumptionAmd */
        $timeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepository->find($id);

        if (empty($timeMaterialConsumptionAmd))
        {
            return $this->sendError($this->errorMessage);
        }

        return $this->sendResponse(new TimeMaterialConsumptionAmdResource($timeMaterialConsumptionAmd),
            'Time Material Consumption Amd retrieved successfully');
    }

    /**
     * Update the specified TimeMaterialConsumptionAmd in storage.
     * PUT/PATCH /timeMaterialConsumptionAmds/{id}
     *
     * @param int $id
     * @param UpdateTimeMaterialConsumptionAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimeMaterialConsumptionAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var TimeMaterialConsumptionAmd $timeMaterialConsumptionAmd */
        $timeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepository->find($id);

        if (empty($timeMaterialConsumptionAmd))
        {
            return $this->sendError($this->errorMessage);
        }

        $timeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepository->update($input, $id);

        return $this->sendResponse(new TimeMaterialConsumptionAmdResource($timeMaterialConsumptionAmd),
            'TimeMaterialConsumptionAmd updated successfully');
    }

    /**
     * Remove the specified TimeMaterialConsumptionAmd from storage.
     * DELETE /timeMaterialConsumptionAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TimeMaterialConsumptionAmd $timeMaterialConsumptionAmd */
        $timeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepository->find($id);

        if (empty($timeMaterialConsumptionAmd))
        {
            return $this->sendError($this->errorMessage);
        }

        $timeMaterialConsumptionAmd->delete();

        return $this->sendSuccess('Time Material Consumption Amd deleted successfully');
    }
}
