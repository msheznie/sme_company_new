<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractDeliverableAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractDeliverableAmdAPIRequest;
use App\Models\CMContractDeliverableAmd;
use App\Repositories\CMContractDeliverableAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractDeliverableAmdResource;
use Response;

/**
 * Class CMContractDeliverableAmdController
 * @package App\Http\Controllers\API
 */

class CMContractDeliverableAmdAPIController extends AppBaseController
{
    /** @var  CMContractDeliverableAmdRepository */
    private $cMContractDeliverableAmdRepository;

    public function __construct(CMContractDeliverableAmdRepository $cMContractDeliverableAmdRepo)
    {
        $this->cMContractDeliverableAmdRepository = $cMContractDeliverableAmdRepo;
    }

    /**
     * Display a listing of the CMContractDeliverableAmd.
     * GET|HEAD /cMContractDeliverableAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractDeliverableAmds = $this->cMContractDeliverableAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractDeliverableAmdResource::collection($cMContractDeliverableAmds), 'C M Contract Deliverable Amds retrieved successfully');
    }

    /**
     * Store a newly created CMContractDeliverableAmd in storage.
     * POST /cMContractDeliverableAmds
     *
     * @param CreateCMContractDeliverableAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractDeliverableAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractDeliverableAmd = $this->cMContractDeliverableAmdRepository->create($input);

        return $this->sendResponse(new CMContractDeliverableAmdResource($cMContractDeliverableAmd), 'C M Contract Deliverable Amd saved successfully');
    }

    /**
     * Display the specified CMContractDeliverableAmd.
     * GET|HEAD /cMContractDeliverableAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractDeliverableAmd $cMContractDeliverableAmd */
        $cMContractDeliverableAmd = $this->cMContractDeliverableAmdRepository->find($id);

        if (empty($cMContractDeliverableAmd)) {
            return $this->sendError('C M Contract Deliverable Amd not found');
        }

        return $this->sendResponse(new CMContractDeliverableAmdResource($cMContractDeliverableAmd), 'C M Contract Deliverable Amd retrieved successfully');
    }

    /**
     * Update the specified CMContractDeliverableAmd in storage.
     * PUT/PATCH /cMContractDeliverableAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractDeliverableAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractDeliverableAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractDeliverableAmd $cMContractDeliverableAmd */
        $cMContractDeliverableAmd = $this->cMContractDeliverableAmdRepository->find($id);

        if (empty($cMContractDeliverableAmd)) {
            return $this->sendError('C M Contract Deliverable Amd not found');
        }

        $cMContractDeliverableAmd = $this->cMContractDeliverableAmdRepository->update($input, $id);

        return $this->sendResponse(new CMContractDeliverableAmdResource($cMContractDeliverableAmd), 'CMContractDeliverableAmd updated successfully');
    }

    /**
     * Remove the specified CMContractDeliverableAmd from storage.
     * DELETE /cMContractDeliverableAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractDeliverableAmd $cMContractDeliverableAmd */
        $cMContractDeliverableAmd = $this->cMContractDeliverableAmdRepository->find($id);

        if (empty($cMContractDeliverableAmd)) {
            return $this->sendError('C M Contract Deliverable Amd not found');
        }

        $cMContractDeliverableAmd->delete();

        return $this->sendSuccess('C M Contract Deliverable Amd deleted successfully');
    }
}
