<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractDocumentAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractDocumentAmdAPIRequest;
use App\Models\CMContractDocumentAmd;
use App\Repositories\CMContractDocumentAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractDocumentAmdResource;
use Response;

/**
 * Class CMContractDocumentAmdController
 * @package App\Http\Controllers\API
 */

class CMContractDocumentAmdAPIController extends AppBaseController
{
    /** @var  CMContractDocumentAmdRepository */
    private $cMContractDocumentAmdRepository;

    public function __construct(CMContractDocumentAmdRepository $cMContractDocumentAmdRepo)
    {
        $this->cMContractDocumentAmdRepository = $cMContractDocumentAmdRepo;
    }

    /**
     * Display a listing of the CMContractDocumentAmd.
     * GET|HEAD /cMContractDocumentAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractDocumentAmds = $this->cMContractDocumentAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractDocumentAmdResource::collection($cMContractDocumentAmds), 'C M Contract Document Amds retrieved successfully');
    }

    /**
     * Store a newly created CMContractDocumentAmd in storage.
     * POST /cMContractDocumentAmds
     *
     * @param CreateCMContractDocumentAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractDocumentAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractDocumentAmd = $this->cMContractDocumentAmdRepository->create($input);

        return $this->sendResponse(new CMContractDocumentAmdResource($cMContractDocumentAmd), 'C M Contract Document Amd saved successfully');
    }

    /**
     * Display the specified CMContractDocumentAmd.
     * GET|HEAD /cMContractDocumentAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractDocumentAmd $cMContractDocumentAmd */
        $cMContractDocumentAmd = $this->cMContractDocumentAmdRepository->find($id);

        if (empty($cMContractDocumentAmd)) {
            return $this->sendError('C M Contract Document Amd not found');
        }

        return $this->sendResponse(new CMContractDocumentAmdResource($cMContractDocumentAmd), 'C M Contract Document Amd retrieved successfully');
    }

    /**
     * Update the specified CMContractDocumentAmd in storage.
     * PUT/PATCH /cMContractDocumentAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractDocumentAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractDocumentAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractDocumentAmd $cMContractDocumentAmd */
        $cMContractDocumentAmd = $this->cMContractDocumentAmdRepository->find($id);

        if (empty($cMContractDocumentAmd)) {
            return $this->sendError('C M Contract Document Amd not found');
        }

        $cMContractDocumentAmd = $this->cMContractDocumentAmdRepository->update($input, $id);

        return $this->sendResponse(new CMContractDocumentAmdResource($cMContractDocumentAmd), 'CMContractDocumentAmd updated successfully');
    }

    /**
     * Remove the specified CMContractDocumentAmd from storage.
     * DELETE /cMContractDocumentAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractDocumentAmd $cMContractDocumentAmd */
        $cMContractDocumentAmd = $this->cMContractDocumentAmdRepository->find($id);

        if (empty($cMContractDocumentAmd)) {
            return $this->sendError('C M Contract Document Amd not found');
        }

        $cMContractDocumentAmd->delete();

        return $this->sendSuccess('C M Contract Document Amd deleted successfully');
    }
}
