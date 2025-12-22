<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractUserAssignAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractUserAssignAmdAPIRequest;
use App\Models\CMContractUserAssignAmd;
use App\Repositories\CMContractUserAssignAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractUserAssignAmdResource;
use Response;

/**
 * Class CMContractUserAssignAmdController
 * @package App\Http\Controllers\API
 */

class CMContractUserAssignAmdAPIController extends AppBaseController
{
    /** @var  CMContractUserAssignAmdRepository */
    private $cMContractUserAssignAmdRepository;

    public function __construct(CMContractUserAssignAmdRepository $cMContractUserAssignAmdRepo)
    {
        $this->cMContractUserAssignAmdRepository = $cMContractUserAssignAmdRepo;
    }

    /**
     * Display a listing of the CMContractUserAssignAmd.
     * GET|HEAD /cMContractUserAssignAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractUserAssignAmds = $this->cMContractUserAssignAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractUserAssignAmdResource::collection($cMContractUserAssignAmds), 'C M Contract User Assign Amds retrieved successfully');
    }

    /**
     * Store a newly created CMContractUserAssignAmd in storage.
     * POST /cMContractUserAssignAmds
     *
     * @param CreateCMContractUserAssignAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractUserAssignAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractUserAssignAmd = $this->cMContractUserAssignAmdRepository->create($input);

        return $this->sendResponse(new CMContractUserAssignAmdResource($cMContractUserAssignAmd), 'C M Contract User Assign Amd saved successfully');
    }

    /**
     * Display the specified CMContractUserAssignAmd.
     * GET|HEAD /cMContractUserAssignAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractUserAssignAmd $cMContractUserAssignAmd */
        $cMContractUserAssignAmd = $this->cMContractUserAssignAmdRepository->find($id);

        if (empty($cMContractUserAssignAmd)) {
            return $this->sendError('C M Contract User Assign Amd not found');
        }

        return $this->sendResponse(new CMContractUserAssignAmdResource($cMContractUserAssignAmd), 'C M Contract User Assign Amd retrieved successfully');
    }

    /**
     * Update the specified CMContractUserAssignAmd in storage.
     * PUT/PATCH /cMContractUserAssignAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractUserAssignAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractUserAssignAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractUserAssignAmd $cMContractUserAssignAmd */
        $cMContractUserAssignAmd = $this->cMContractUserAssignAmdRepository->find($id);

        if (empty($cMContractUserAssignAmd)) {
            return $this->sendError('C M Contract User Assign Amd not found');
        }

        $cMContractUserAssignAmd = $this->cMContractUserAssignAmdRepository->update($input, $id);

        return $this->sendResponse(new CMContractUserAssignAmdResource($cMContractUserAssignAmd), 'CMContractUserAssignAmd updated successfully');
    }

    /**
     * Remove the specified CMContractUserAssignAmd from storage.
     * DELETE /cMContractUserAssignAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractUserAssignAmd $cMContractUserAssignAmd */
        $cMContractUserAssignAmd = $this->cMContractUserAssignAmdRepository->find($id);

        if (empty($cMContractUserAssignAmd)) {
            return $this->sendError('C M Contract User Assign Amd not found');
        }

        $cMContractUserAssignAmd->delete();

        return $this->sendSuccess('C M Contract User Assign Amd deleted successfully');
    }
}
