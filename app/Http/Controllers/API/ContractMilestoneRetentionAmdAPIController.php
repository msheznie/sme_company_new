<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractMilestoneRetentionAmdAPIRequest;
use App\Http\Requests\API\UpdateContractMilestoneRetentionAmdAPIRequest;
use App\Models\ContractMilestoneRetentionAmd;
use App\Repositories\ContractMilestoneRetentionAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMilestoneRetentionAmdResource;
use Response;

/**
 * Class ContractMilestoneRetentionAmdController
 * @package App\Http\Controllers\API
 */

class ContractMilestoneRetentionAmdAPIController extends AppBaseController
{
    /** @var  ContractMilestoneRetentionAmdRepository */
    private $contractMilestoneRetentionAmdRepository;

    public function __construct(ContractMilestoneRetentionAmdRepository $contractMilestoneRetentionAmdRepo)
    {
        $this->contractMilestoneRetentionAmdRepository = $contractMilestoneRetentionAmdRepo;
    }

    /**
     * Display a listing of the ContractMilestoneRetentionAmd.
     * GET|HEAD /contractMilestoneRetentionAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMilestoneRetentionAmds = $this->contractMilestoneRetentionAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMilestoneRetentionAmdResource::collection($contractMilestoneRetentionAmds), 'Contract Milestone Retention Amds retrieved successfully');
    }

    /**
     * Store a newly created ContractMilestoneRetentionAmd in storage.
     * POST /contractMilestoneRetentionAmds
     *
     * @param CreateContractMilestoneRetentionAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractMilestoneRetentionAmdAPIRequest $request)
    {
        $input = $request->all();

        $contractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepository->create($input);

        return $this->sendResponse(new ContractMilestoneRetentionAmdResource($contractMilestoneRetentionAmd), 'Contract Milestone Retention Amd saved successfully');
    }

    /**
     * Display the specified ContractMilestoneRetentionAmd.
     * GET|HEAD /contractMilestoneRetentionAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractMilestoneRetentionAmd $contractMilestoneRetentionAmd */
        $contractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepository->find($id);

        if (empty($contractMilestoneRetentionAmd)) {
            return $this->sendError('Contract Milestone Retention Amd not found');
        }

        return $this->sendResponse(new ContractMilestoneRetentionAmdResource($contractMilestoneRetentionAmd), 'Contract Milestone Retention Amd retrieved successfully');
    }

    /**
     * Update the specified ContractMilestoneRetentionAmd in storage.
     * PUT/PATCH /contractMilestoneRetentionAmds/{id}
     *
     * @param int $id
     * @param UpdateContractMilestoneRetentionAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMilestoneRetentionAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractMilestoneRetentionAmd $contractMilestoneRetentionAmd */
        $contractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepository->find($id);

        if (empty($contractMilestoneRetentionAmd)) {
            return $this->sendError('Contract Milestone Retention Amd not found');
        }

        $contractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepository->update($input, $id);

        return $this->sendResponse(new ContractMilestoneRetentionAmdResource($contractMilestoneRetentionAmd), 'ContractMilestoneRetentionAmd updated successfully');
    }

    /**
     * Remove the specified ContractMilestoneRetentionAmd from storage.
     * DELETE /contractMilestoneRetentionAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractMilestoneRetentionAmd $contractMilestoneRetentionAmd */
        $contractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepository->find($id);

        if (empty($contractMilestoneRetentionAmd)) {
            return $this->sendError('Contract Milestone Retention Amd not found');
        }

        $contractMilestoneRetentionAmd->delete();

        return $this->sendSuccess('Contract Milestone Retention Amd deleted successfully');
    }
}
