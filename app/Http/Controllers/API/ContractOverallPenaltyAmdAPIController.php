<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractOverallPenaltyAmdAPIRequest;
use App\Http\Requests\API\UpdateContractOverallPenaltyAmdAPIRequest;
use App\Models\ContractOverallPenaltyAmd;
use App\Repositories\ContractOverallPenaltyAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractOverallPenaltyAmdResource;
use Response;

/**
 * Class ContractOverallPenaltyAmdController
 * @package App\Http\Controllers\API
 */

class ContractOverallPenaltyAmdAPIController extends AppBaseController
{
    /** @var  ContractOverallPenaltyAmdRepository */
    private $contractOverallPenaltyAmdRepository;

    public function __construct(ContractOverallPenaltyAmdRepository $contractOverallPenaltyAmdRepo)
    {
        $this->contractOverallPenaltyAmdRepository = $contractOverallPenaltyAmdRepo;
    }

    /**
     * Display a listing of the ContractOverallPenaltyAmd.
     * GET|HEAD /contractOverallPenaltyAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractOverallPenaltyAmds = $this->contractOverallPenaltyAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractOverallPenaltyAmdResource::collection($contractOverallPenaltyAmds),
            'Contract Overall Penalty Amds retrieved successfully');
    }

    /**
     * Store a newly created ContractOverallPenaltyAmd in storage.
     * POST /contractOverallPenaltyAmds
     *
     * @param CreateContractOverallPenaltyAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractOverallPenaltyAmdAPIRequest $request)
    {
        $input = $request->all();

        $contractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepository->create($input);

        return $this->sendResponse(new ContractOverallPenaltyAmdResource($contractOverallPenaltyAmd),
            'Contract Overall Penalty Amd saved successfully');
    }

    /**
     * Display the specified ContractOverallPenaltyAmd.
     * GET|HEAD /contractOverallPenaltyAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractOverallPenaltyAmd $contractOverallPenaltyAmd */
        $contractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepository->find($id);

        if (empty($contractOverallPenaltyAmd))
        {
            return $this->sendError('Contract Overall Penalty Amd not found');
        }

        return $this->sendResponse(new ContractOverallPenaltyAmdResource($contractOverallPenaltyAmd),
            'Contract Overall Penalty Amd retrieved successfully');
    }

    /**
     * Update the specified ContractOverallPenaltyAmd in storage.
     * PUT/PATCH /contractOverallPenaltyAmds/{id}
     *
     * @param int $id
     * @param UpdateContractOverallPenaltyAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractOverallPenaltyAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractOverallPenaltyAmd $contractOverallPenaltyAmd */
        $contractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepository->find($id);

        if (empty($contractOverallPenaltyAmd))
        {
            return $this->sendError('Contract Overall Penalty Amd not found');
        }

        $contractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepository->update($input, $id);

        return $this->sendResponse(new ContractOverallPenaltyAmdResource($contractOverallPenaltyAmd),
            'ContractOverallPenaltyAmd updated successfully');
    }

    /**
     * Remove the specified ContractOverallPenaltyAmd from storage.
     * DELETE /contractOverallPenaltyAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractOverallPenaltyAmd $contractOverallPenaltyAmd */
        $contractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepository->find($id);

        if (empty($contractOverallPenaltyAmd))
        {
            return $this->sendError('Contract Overall Penalty Amd not found');
        }

        $contractOverallPenaltyAmd->delete();

        return $this->sendSuccess('Contract Overall Penalty Amd deleted successfully');
    }
}
