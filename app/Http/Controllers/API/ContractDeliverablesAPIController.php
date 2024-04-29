<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractDeliverablesAPIRequest;
use App\Http\Requests\API\UpdateContractDeliverablesAPIRequest;
use App\Models\ContractDeliverables;
use App\Models\ContractMaster;
use App\Repositories\ContractDeliverablesRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractDeliverablesResource;
use Response;

/**
 * Class ContractDeliverablesController
 * @package App\Http\Controllers\API
 */

class ContractDeliverablesAPIController extends AppBaseController
{
    /** @var  ContractDeliverablesRepository */
    private $contractDeliverablesRepository;

    public function __construct(ContractDeliverablesRepository $contractDeliverablesRepo)
    {
        $this->contractDeliverablesRepository = $contractDeliverablesRepo;
    }

    /**
     * Display a listing of the ContractDeliverables.
     * GET|HEAD /contractDeliverables
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractDeliverables = $this->contractDeliverablesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractDeliverablesResource::collection($contractDeliverables), 'Contract Deliverables retrieved successfully');
    }

    /**
     * Store a newly created ContractDeliverables in storage.
     * POST /contractDeliverables
     *
     * @param CreateContractDeliverablesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractDeliverablesAPIRequest $request)
    {
        $input = $request->all();

        $contractDeliverables = $this->contractDeliverablesRepository->create($input);

        return $this->sendResponse(new ContractDeliverablesResource($contractDeliverables), 'Contract Deliverables saved successfully');
    }

    /**
     * Display the specified ContractDeliverables.
     * GET|HEAD /contractDeliverables/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractDeliverables $contractDeliverables */
        $contractDeliverables = $this->contractDeliverablesRepository->find($id);

        if (empty($contractDeliverables)) {
            return $this->sendError('Contract Deliverables not found');
        }

        return $this->sendResponse(new ContractDeliverablesResource($contractDeliverables), 'Contract Deliverables retrieved successfully');
    }

    /**
     * Update the specified ContractDeliverables in storage.
     * PUT/PATCH /contractDeliverables/{id}
     *
     * @param int $id
     * @param UpdateContractDeliverablesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractDeliverablesAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractDeliverables $contractDeliverables */
        $contractDeliverables = $this->contractDeliverablesRepository->find($id);

        if (empty($contractDeliverables)) {
            return $this->sendError('Contract Deliverables not found');
        }

        $contractDeliverables = $this->contractDeliverablesRepository->update($input, $id);

        return $this->sendResponse(new ContractDeliverablesResource($contractDeliverables), 'ContractDeliverables updated successfully');
    }

    /**
     * Remove the specified ContractDeliverables from storage.
     * DELETE /contractDeliverables/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractDeliverables $contractDeliverables */
        $contractDeliverables = $this->contractDeliverablesRepository->find($id);

        if (empty($contractDeliverables)) {
            return $this->sendError('Contract Deliverables not found');
        }

        $contractDeliverables->delete();

        return $this->sendSuccess('Contract Deliverables deleted successfully');
    }

    public function getContractDeliverables(Request $request) {
        $contractUuid = $request->input('contractUuid');
        $companySystemID = $request->input('companySystemID');

        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)->first();
        if(empty($contractMaster)) {
            return $this->sendError('Contract Master not found');
        }
        $response['contract_deliverables'] = $this->contractDeliverablesRepository
            ->getDeliverables($contractMaster['id'], $companySystemID);
        $response['contract_milestones'] = ContractManagementUtils::getContractMilestones($contractMaster['id'],
            $companySystemID);
        return $this->sendResponse($response, 'Contract Deliverables form data retrieved successfully');
    }
}
