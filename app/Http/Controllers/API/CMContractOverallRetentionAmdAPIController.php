<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractOverallRetentionAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractOverallRetentionAmdAPIRequest;
use App\Models\CMContractOverallRetentionAmd;
use App\Repositories\CMContractOverallRetentionAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractOverallRetentionAmdResource;
use Response;

/**
 * Class CMContractOverallRetentionAmdController
 * @package App\Http\Controllers\API
 */

class CMContractOverallRetentionAmdAPIController extends AppBaseController
{
    /** @var  CMContractOverallRetentionAmdRepository */
    private $cMContractOverallRetentionAmdRepository;

    public function __construct(CMContractOverallRetentionAmdRepository $cMContractOverallRetentionAmdRepo)
    {
        $this->cMContractOverallRetentionAmdRepository = $cMContractOverallRetentionAmdRepo;
    }

    /**
     * Display a listing of the CMContractOverallRetentionAmd.
     * GET|HEAD /cMContractOverallRetentionAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractOverallRetentionAmds = $this->cMContractOverallRetentionAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractOverallRetentionAmdResource::collection($cMContractOverallRetentionAmds), 'C M Contract Overall Retention Amds retrieved successfully');
    }

    /**
     * Store a newly created CMContractOverallRetentionAmd in storage.
     * POST /cMContractOverallRetentionAmds
     *
     * @param CreateCMContractOverallRetentionAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractOverallRetentionAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepository->create($input);

        return $this->sendResponse(new CMContractOverallRetentionAmdResource($cMContractOverallRetentionAmd), 'C M Contract Overall Retention Amd saved successfully');
    }

    /**
     * Display the specified CMContractOverallRetentionAmd.
     * GET|HEAD /cMContractOverallRetentionAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractOverallRetentionAmd $cMContractOverallRetentionAmd */
        $cMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepository->find($id);

        if (empty($cMContractOverallRetentionAmd)) {
            return $this->sendError('C M Contract Overall Retention Amd not found');
        }

        return $this->sendResponse(new CMContractOverallRetentionAmdResource($cMContractOverallRetentionAmd), 'C M Contract Overall Retention Amd retrieved successfully');
    }

    /**
     * Update the specified CMContractOverallRetentionAmd in storage.
     * PUT/PATCH /cMContractOverallRetentionAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractOverallRetentionAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractOverallRetentionAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractOverallRetentionAmd $cMContractOverallRetentionAmd */
        $cMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepository->find($id);

        if (empty($cMContractOverallRetentionAmd)) {
            return $this->sendError('C M Contract Overall Retention Amd not found');
        }

        $cMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepository->update($input, $id);

        return $this->sendResponse(new CMContractOverallRetentionAmdResource($cMContractOverallRetentionAmd), 'CMContractOverallRetentionAmd updated successfully');
    }

    /**
     * Remove the specified CMContractOverallRetentionAmd from storage.
     * DELETE /cMContractOverallRetentionAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractOverallRetentionAmd $cMContractOverallRetentionAmd */
        $cMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepository->find($id);

        if (empty($cMContractOverallRetentionAmd)) {
            return $this->sendError('C M Contract Overall Retention Amd not found');
        }

        $cMContractOverallRetentionAmd->delete();

        return $this->sendSuccess('C M Contract Overall Retention Amd deleted successfully');
    }
}
