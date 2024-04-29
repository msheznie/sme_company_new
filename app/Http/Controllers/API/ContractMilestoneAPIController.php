<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractMilestoneAPIRequest;
use App\Http\Requests\API\UpdateContractMilestoneAPIRequest;
use App\Models\ContractMilestone;
use App\Repositories\ContractMilestoneRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMilestoneResource;
use Response;

/**
 * Class ContractMilestoneController
 * @package App\Http\Controllers\API
 */

class ContractMilestoneAPIController extends AppBaseController
{
    /** @var  ContractMilestoneRepository */
    private $contractMilestoneRepository;

    public function __construct(ContractMilestoneRepository $contractMilestoneRepo)
    {
        $this->contractMilestoneRepository = $contractMilestoneRepo;
    }

    /**
     * Display a listing of the ContractMilestone.
     * GET|HEAD /contractMilestones
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMilestones = $this->contractMilestoneRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMilestoneResource::collection($contractMilestones),
            'Contract Milestones retrieved successfully');
    }

    /**
     * Store a newly created ContractMilestone in storage.
     * POST /contractMilestones
     *
     * @param CreateContractMilestoneAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractMilestoneAPIRequest $request)
    {
        $contractMilestone = $this->contractMilestoneRepository->createMilestone($request);

        if (!$contractMilestone['status']) {
            return $this->sendError($contractMilestone['message']);
        } else {
            $this->sendResponse([], 'Contract Milestone created successfully.');
        }
    }

    /**
     * Display the specified ContractMilestone.
     * GET|HEAD /contractMilestones/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractMilestone $contractMilestone */
        $contractMilestone = $this->contractMilestoneRepository->find($id);

        if (empty($contractMilestone)) {
            return $this->sendError('Contract Milestone not found');
        }

        return $this->sendResponse(new ContractMilestoneResource($contractMilestone),
            'Contract Milestone retrieved successfully');
    }

    /**
     * Update the specified ContractMilestone in storage.
     * PUT/PATCH /contractMilestones/{id}
     *
     * @param int $id
     * @param UpdateContractMilestoneAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMilestoneAPIRequest $request)
    {
        $input = $request->all();
        $uuid = $input['formData']['uuid'] ?? null;

        $contractMilestone = $this->contractMilestoneRepository->findByUuid($uuid, ['id']);

        if (empty($contractMilestone)) {
            return $this->sendError('Contract Milestone not found');
        }

        $contractMilestone = $this->contractMilestoneRepository->updateMilestone($input, $contractMilestone['id']);
        if(!$contractMilestone['status']) {
            return $this->sendError($contractMilestone['message']);
        } else {
            return $this->sendResponse([],
                'Contract Milestone updated successfully');
        }
    }

    /**
     * Remove the specified ContractMilestone from storage.
     * DELETE /contractMilestones/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractMilestone $contractMilestone */
        $contractMilestone = $this->contractMilestoneRepository->find($id);

        if (empty($contractMilestone)) {
            return $this->sendError('Contract Milestone not found');
        }

        $contractMilestone->delete();

        return $this->sendSuccess('Contract Milestone deleted successfully');
    }

    public function getContractMilestones($id, Request $request){
        $contractMilestone = $this->contractMilestoneRepository->getContractMilestones($id, $request);
        if(!$contractMilestone['status']) {
            return $this->sendError($contractMilestone['message']);
        } else {
            return $this->sendResponse($contractMilestone, 'Contract Milestone retrieved successfully.');
        }
    }
}
