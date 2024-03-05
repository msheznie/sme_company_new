<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractTypeSectionsAPIRequest;
use App\Http\Requests\API\UpdateCMContractTypeSectionsAPIRequest;
use App\Models\CMContractTypeSections;
use App\Repositories\CMContractTypeSectionsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractTypeSectionsResource;
use Response;

/**
 * Class CMContractTypeSectionsController
 * @package App\Http\Controllers\API
 */

class CMContractTypeSectionsAPIController extends AppBaseController
{
    /** @var  CMContractTypeSectionsRepository */
    private $cMContractTypeSectionsRepository;

    public function __construct(CMContractTypeSectionsRepository $cMContractTypeSectionsRepo)
    {
        $this->cMContractTypeSectionsRepository = $cMContractTypeSectionsRepo;
    }

    /**
     * Display a listing of the CMContractTypeSections.
     * GET|HEAD /cMContractTypeSections
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractTypeSections = $this->cMContractTypeSectionsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractTypeSectionsResource::collection($cMContractTypeSections), 'Contract Type Sections retrieved successfully');
    }

    /**
     * Store a newly created CMContractTypeSections in storage.
     * POST /cMContractTypeSections
     *
     * @param CreateCMContractTypeSectionsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractTypeSectionsAPIRequest $request)
    {
        $input = $request->all();

        $cMContractTypeSections = $this->cMContractTypeSectionsRepository->create($input);

        return $this->sendResponse(new CMContractTypeSectionsResource($cMContractTypeSections), 'Contract Type Sections saved successfully');
    }

    /**
     * Display the specified CMContractTypeSections.
     * GET|HEAD /cMContractTypeSections/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractTypeSections $cMContractTypeSections */
        $cMContractTypeSections = $this->cMContractTypeSectionsRepository->find($id);

        if (empty($cMContractTypeSections)) {
            return $this->sendError('Contract Type Sections not found');
        }

        return $this->sendResponse(new CMContractTypeSectionsResource($cMContractTypeSections), 'Contract Type Sections retrieved successfully');
    }

    /**
     * Update the specified CMContractTypeSections in storage.
     * PUT/PATCH /cMContractTypeSections/{id}
     *
     * @param int $id
     * @param UpdateCMContractTypeSectionsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractTypeSectionsAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractTypeSections $cMContractTypeSections */
        $cMContractTypeSections = $this->cMContractTypeSectionsRepository->find($id);

        if (empty($cMContractTypeSections)) {
            return $this->sendError('Contract Type Sections not found');
        }

        $cMContractTypeSections = $this->cMContractTypeSectionsRepository->update($input, $id);

        return $this->sendResponse(new CMContractTypeSectionsResource($cMContractTypeSections), 'CMContractTypeSections updated successfully');
    }

    /**
     * Remove the specified CMContractTypeSections from storage.
     * DELETE /cMContractTypeSections/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractTypeSections $cMContractTypeSections */
        $cMContractTypeSections = $this->cMContractTypeSectionsRepository->find($id);

        if (empty($cMContractTypeSections)) {
            return $this->sendError('Contract Type Sections not found');
        }

        $cMContractTypeSections->delete();

        return $this->sendSuccess('Contract Type Sections deleted successfully');
    }
}
