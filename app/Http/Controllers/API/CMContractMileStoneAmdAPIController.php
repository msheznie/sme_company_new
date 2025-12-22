<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractMileStoneAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractMileStoneAmdAPIRequest;
use App\Models\CMContractMileStoneAmd;
use App\Repositories\CMContractMileStoneAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractMileStoneAmdResource;
use Response;

/**
 * Class CMContractMileStoneAmdController
 * @package App\Http\Controllers\API
 */

class CMContractMileStoneAmdAPIController extends AppBaseController
{
    /** @var  CMContractMileStoneAmdRepository */
    private $cMContractMileStoneAmdRepository;

    public function __construct(CMContractMileStoneAmdRepository $cMContractMileStoneAmdRepo)
    {
        $this->cMContractMileStoneAmdRepository = $cMContractMileStoneAmdRepo;
    }

    /**
     * Display a listing of the CMContractMileStoneAmd.
     * GET|HEAD /cMContractMileStoneAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractMileStoneAmds = $this->cMContractMileStoneAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractMileStoneAmdResource::collection($cMContractMileStoneAmds), 'C M Contract Mile Stone Amds retrieved successfully');
    }

    /**
     * Store a newly created CMContractMileStoneAmd in storage.
     * POST /cMContractMileStoneAmds
     *
     * @param CreateCMContractMileStoneAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractMileStoneAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractMileStoneAmd = $this->cMContractMileStoneAmdRepository->create($input);

        return $this->sendResponse(new CMContractMileStoneAmdResource($cMContractMileStoneAmd), 'C M Contract Mile Stone Amd saved successfully');
    }

    /**
     * Display the specified CMContractMileStoneAmd.
     * GET|HEAD /cMContractMileStoneAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractMileStoneAmd $cMContractMileStoneAmd */
        $cMContractMileStoneAmd = $this->cMContractMileStoneAmdRepository->find($id);

        if (empty($cMContractMileStoneAmd)) {
            return $this->sendError('C M Contract Mile Stone Amd not found');
        }

        return $this->sendResponse(new CMContractMileStoneAmdResource($cMContractMileStoneAmd), 'C M Contract Mile Stone Amd retrieved successfully');
    }

    /**
     * Update the specified CMContractMileStoneAmd in storage.
     * PUT/PATCH /cMContractMileStoneAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractMileStoneAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractMileStoneAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractMileStoneAmd $cMContractMileStoneAmd */
        $cMContractMileStoneAmd = $this->cMContractMileStoneAmdRepository->find($id);

        if (empty($cMContractMileStoneAmd)) {
            return $this->sendError('C M Contract Mile Stone Amd not found');
        }

        $cMContractMileStoneAmd = $this->cMContractMileStoneAmdRepository->update($input, $id);

        return $this->sendResponse(new CMContractMileStoneAmdResource($cMContractMileStoneAmd), 'CMContractMileStoneAmd updated successfully');
    }

    /**
     * Remove the specified CMContractMileStoneAmd from storage.
     * DELETE /cMContractMileStoneAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractMileStoneAmd $cMContractMileStoneAmd */
        $cMContractMileStoneAmd = $this->cMContractMileStoneAmdRepository->find($id);

        if (empty($cMContractMileStoneAmd)) {
            return $this->sendError('C M Contract Mile Stone Amd not found');
        }

        $cMContractMileStoneAmd->delete();

        return $this->sendSuccess('C M Contract Mile Stone Amd deleted successfully');
    }

    public function getMilestoneAmend(Request $request)
    {
        try
        {
            $input = $request->all();
            return $this->cMContractMileStoneAmdRepository->getContractMilestoneData($input);
        } catch (\Exception $e)
        {
            return $this->sendError ('Something went wrong' . $e->getMessage(), 500);
        }
    }
}
