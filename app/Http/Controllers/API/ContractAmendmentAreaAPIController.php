<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractAmendmentAreaAPIRequest;
use App\Http\Requests\API\UpdateContractAmendmentAreaAPIRequest;
use App\Models\ContractAmendmentArea;
use App\Repositories\ContractAmendmentAreaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractAmendmentAreaResource;
use Response;

/**
 * Class ContractAmendmentAreaController
 * @package App\Http\Controllers\API
 */

class ContractAmendmentAreaAPIController extends AppBaseController
{
    /** @var  ContractAmendmentAreaRepository */
    private $contractAmendmentAreaRepository;

    public function __construct(ContractAmendmentAreaRepository $contractAmendmentAreaRepo)
    {
        $this->contractAmendmentAreaRepository = $contractAmendmentAreaRepo;
    }

    /**
     * Display a listing of the ContractAmendmentArea.
     * GET|HEAD /contractAmendmentAreas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractAmendmentAreas = $this->contractAmendmentAreaRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractAmendmentAreaResource::collection($contractAmendmentAreas), 'Contract Amendment Areas retrieved successfully');
    }

    /**
     * Store a newly created ContractAmendmentArea in storage.
     * POST /contractAmendmentAreas
     *
     * @param CreateContractAmendmentAreaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractAmendmentAreaAPIRequest $request)
    {
        $input = $request->all();

        $contractAmendmentArea = $this->contractAmendmentAreaRepository->create($input);

        return $this->sendResponse(new ContractAmendmentAreaResource($contractAmendmentArea), 'Contract Amendment Area saved successfully');
    }

    /**
     * Display the specified ContractAmendmentArea.
     * GET|HEAD /contractAmendmentAreas/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractAmendmentArea $contractAmendmentArea */
        $contractAmendmentArea = $this->contractAmendmentAreaRepository->find($id);

        if (empty($contractAmendmentArea)) {
            return $this->sendError('Contract Amendment Area not found');
        }

        return $this->sendResponse(new ContractAmendmentAreaResource($contractAmendmentArea), 'Contract Amendment Area retrieved successfully');
    }

    /**
     * Update the specified ContractAmendmentArea in storage.
     * PUT/PATCH /contractAmendmentAreas/{id}
     *
     * @param int $id
     * @param UpdateContractAmendmentAreaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractAmendmentAreaAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractAmendmentArea $contractAmendmentArea */
        $contractAmendmentArea = $this->contractAmendmentAreaRepository->find($id);

        if (empty($contractAmendmentArea)) {
            return $this->sendError('Contract Amendment Area not found');
        }

        $contractAmendmentArea = $this->contractAmendmentAreaRepository->update($input, $id);

        return $this->sendResponse(new ContractAmendmentAreaResource($contractAmendmentArea), 'ContractAmendmentArea updated successfully');
    }

    /**
     * Remove the specified ContractAmendmentArea from storage.
     * DELETE /contractAmendmentAreas/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractAmendmentArea $contractAmendmentArea */
        $contractAmendmentArea = $this->contractAmendmentAreaRepository->find($id);

        if (empty($contractAmendmentArea)) {
            return $this->sendError('Contract Amendment Area not found');
        }

        $contractAmendmentArea->delete();

        return $this->sendSuccess('Contract Amendment Area deleted successfully');
    }

    public function getActiveAmdSections(Request $request)
    {
        try
        {
            $input = $request->all();
            return $this->contractAmendmentAreaRepository->getActiveAmdSections($input);
        } catch (\Exception $e)
        {
            return $this->sendError ('Something went wrong' . $e->getMessage(), 500);
        }
    }
}
