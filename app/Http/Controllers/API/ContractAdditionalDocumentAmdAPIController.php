<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractAdditionalDocumentAmdAPIRequest;
use App\Http\Requests\API\UpdateContractAdditionalDocumentAmdAPIRequest;
use App\Models\ContractAdditionalDocumentAmd;
use App\Repositories\ContractAdditionalDocumentAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractAdditionalDocumentAmdResource;
use Response;

/**
 * Class ContractAdditionalDocumentAmdController
 * @package App\Http\Controllers\API
 */

class ContractAdditionalDocumentAmdAPIController extends AppBaseController
{
    /** @var  ContractAdditionalDocumentAmdRepository */
    private $contractAdditionalDocumentAmdRepository;

    public function __construct(ContractAdditionalDocumentAmdRepository $contractAdditionalDocumentAmdRepo)
    {
        $this->contractAdditionalDocumentAmdRepository = $contractAdditionalDocumentAmdRepo;
    }

    /**
     * Display a listing of the ContractAdditionalDocumentAmd.
     * GET|HEAD /contractAdditionalDocumentAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractAdditionalDocumentAmds = $this->contractAdditionalDocumentAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractAdditionalDocumentAmdResource::collection($contractAdditionalDocumentAmds), 'Contract Additional Document Amds retrieved successfully');
    }

    /**
     * Store a newly created ContractAdditionalDocumentAmd in storage.
     * POST /contractAdditionalDocumentAmds
     *
     * @param CreateContractAdditionalDocumentAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractAdditionalDocumentAmdAPIRequest $request)
    {
        $input = $request->all();

        $contractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepository->create($input);

        return $this->sendResponse(new ContractAdditionalDocumentAmdResource($contractAdditionalDocumentAmd), 'Contract Additional Document Amd saved successfully');
    }

    /**
     * Display the specified ContractAdditionalDocumentAmd.
     * GET|HEAD /contractAdditionalDocumentAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractAdditionalDocumentAmd $contractAdditionalDocumentAmd */
        $contractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepository->find($id);

        if (empty($contractAdditionalDocumentAmd)) {
            return $this->sendError('Contract Additional Document Amd not found');
        }

        return $this->sendResponse(new ContractAdditionalDocumentAmdResource($contractAdditionalDocumentAmd), 'Contract Additional Document Amd retrieved successfully');
    }

    /**
     * Update the specified ContractAdditionalDocumentAmd in storage.
     * PUT/PATCH /contractAdditionalDocumentAmds/{id}
     *
     * @param int $id
     * @param UpdateContractAdditionalDocumentAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractAdditionalDocumentAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractAdditionalDocumentAmd $contractAdditionalDocumentAmd */
        $contractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepository->find($id);

        if (empty($contractAdditionalDocumentAmd)) {
            return $this->sendError('Contract Additional Document Amd not found');
        }

        $contractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepository->update($input, $id);

        return $this->sendResponse(new ContractAdditionalDocumentAmdResource($contractAdditionalDocumentAmd), 'ContractAdditionalDocumentAmd updated successfully');
    }

    /**
     * Remove the specified ContractAdditionalDocumentAmd from storage.
     * DELETE /contractAdditionalDocumentAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractAdditionalDocumentAmd $contractAdditionalDocumentAmd */
        $contractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepository->find($id);

        if (empty($contractAdditionalDocumentAmd)) {
            return $this->sendError('Contract Additional Document Amd not found');
        }

        $contractAdditionalDocumentAmd->delete();

        return $this->sendSuccess('Contract Additional Document Amd deleted successfully');
    }
}
