<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractOverallRetentionAPIRequest;
use App\Http\Requests\API\UpdateContractOverallRetentionAPIRequest;
use App\Models\ContractOverallRetention;
use App\Repositories\ContractOverallRetentionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractOverallRetentionResource;
use Response;

/**
 * Class ContractOverallRetentionController
 * @package App\Http\Controllers\API
 */

class ContractOverallRetentionAPIController extends AppBaseController
{
    /** @var  ContractOverallRetentionRepository */
    private $contractOverallRetentionRepository;

    public function __construct(ContractOverallRetentionRepository $contractOverallRetentionRepo)
    {
        $this->contractOverallRetentionRepository = $contractOverallRetentionRepo;
    }

    /**
     * Display a listing of the ContractOverallRetention.
     * GET|HEAD /contractOverallRetentions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractOverallRetentions = $this->contractOverallRetentionRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractOverallRetentionResource::collection($contractOverallRetentions), 'Contract Overall Retentions retrieved successfully');
    }

    /**
     * Store a newly created ContractOverallRetention in storage.
     * POST /contractOverallRetentions
     *
     * @param CreateContractOverallRetentionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractOverallRetentionAPIRequest $request)
    {
        $input = $request->all();

        $contractOverallRetention = $this->contractOverallRetentionRepository->create($input);

        return $this->sendResponse(new ContractOverallRetentionResource($contractOverallRetention), 'Contract Overall Retention saved successfully');
    }

    /**
     * Display the specified ContractOverallRetention.
     * GET|HEAD /contractOverallRetentions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractOverallRetention $contractOverallRetention */
        $contractOverallRetention = $this->contractOverallRetentionRepository->find($id);

        if (empty($contractOverallRetention)) {
            return $this->sendError('Contract Overall Retention not found');
        }

        return $this->sendResponse(new ContractOverallRetentionResource($contractOverallRetention), 'Contract Overall Retention retrieved successfully');
    }

    /**
     * Update the specified ContractOverallRetention in storage.
     * PUT/PATCH /contractOverallRetentions/{id}
     *
     * @param int $id
     * @param UpdateContractOverallRetentionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractOverallRetentionAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractOverallRetention $contractOverallRetention */
        $contractOverallRetention = $this->contractOverallRetentionRepository->find($id);

        if (empty($contractOverallRetention)) {
            return $this->sendError('Contract Overall Retention not found');
        }

        $contractOverallRetention = $this->contractOverallRetentionRepository->update($input, $id);

        return $this->sendResponse(new ContractOverallRetentionResource($contractOverallRetention), 'ContractOverallRetention updated successfully');
    }

    /**
     * Remove the specified ContractOverallRetention from storage.
     * DELETE /contractOverallRetentions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractOverallRetention $contractOverallRetention */
        $contractOverallRetention = $this->contractOverallRetentionRepository->find($id);

        if (empty($contractOverallRetention)) {
            return $this->sendError('Contract Overall Retention not found');
        }

        $contractOverallRetention->delete();

        return $this->sendSuccess('Contract Overall Retention deleted successfully');
    }
}
