<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractStatusHistoryAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractStatusHistoryAmdAPIRequest;
use App\Models\CMContractStatusHistoryAmd;
use App\Repositories\CMContractStatusHistoryAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractStatusHistoryAmdResource;
use Response;

/**
 * Class CMContractStatusHistoryAmdController
 * @package App\Http\Controllers\API
 */

class CMContractStatusHistoryAmdAPIController extends AppBaseController
{
    /** @var  CMContractStatusHistoryAmdRepository */
    private $cMContractStatusHistoryAmdRepository;

    public function __construct(CMContractStatusHistoryAmdRepository $cMContractStatusHistoryAmdRepo)
    {
        $this->cMContractStatusHistoryAmdRepository = $cMContractStatusHistoryAmdRepo;
    }

    /**
     * Display a listing of the CMContractStatusHistoryAmd.
     * GET|HEAD /cMContractStatusHistoryAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractStatusHistoryAmds = $this->cMContractStatusHistoryAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractStatusHistoryAmdResource::collection($cMContractStatusHistoryAmds), 'C M Contract Status History Amds retrieved successfully');
    }

    /**
     * Store a newly created CMContractStatusHistoryAmd in storage.
     * POST /cMContractStatusHistoryAmds
     *
     * @param CreateCMContractStatusHistoryAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractStatusHistoryAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepository->create($input);

        return $this->sendResponse(new CMContractStatusHistoryAmdResource($cMContractStatusHistoryAmd), 'C M Contract Status History Amd saved successfully');
    }

    /**
     * Display the specified CMContractStatusHistoryAmd.
     * GET|HEAD /cMContractStatusHistoryAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractStatusHistoryAmd $cMContractStatusHistoryAmd */
        $cMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepository->find($id);

        if (empty($cMContractStatusHistoryAmd)) {
            return $this->sendError('C M Contract Status History Amd not found');
        }

        return $this->sendResponse(new CMContractStatusHistoryAmdResource($cMContractStatusHistoryAmd), 'C M Contract Status History Amd retrieved successfully');
    }

    /**
     * Update the specified CMContractStatusHistoryAmd in storage.
     * PUT/PATCH /cMContractStatusHistoryAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractStatusHistoryAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractStatusHistoryAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractStatusHistoryAmd $cMContractStatusHistoryAmd */
        $cMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepository->find($id);

        if (empty($cMContractStatusHistoryAmd)) {
            return $this->sendError('C M Contract Status History Amd not found');
        }

        $cMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepository->update($input, $id);

        return $this->sendResponse(new CMContractStatusHistoryAmdResource($cMContractStatusHistoryAmd), 'CMContractStatusHistoryAmd updated successfully');
    }

    /**
     * Remove the specified CMContractStatusHistoryAmd from storage.
     * DELETE /cMContractStatusHistoryAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractStatusHistoryAmd $cMContractStatusHistoryAmd */
        $cMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepository->find($id);

        if (empty($cMContractStatusHistoryAmd)) {
            return $this->sendError('C M Contract Status History Amd not found');
        }

        $cMContractStatusHistoryAmd->delete();

        return $this->sendSuccess('C M Contract Status History Amd deleted successfully');
    }
}
