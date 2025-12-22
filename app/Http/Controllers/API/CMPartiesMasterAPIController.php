<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMPartiesMasterAPIRequest;
use App\Http\Requests\API\UpdateCMPartiesMasterAPIRequest;
use App\Models\CMPartiesMaster;
use App\Repositories\CMPartiesMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMPartiesMasterResource;
use Response;

/**
 * Class CMPartiesMasterController
 * @package App\Http\Controllers\API
 */

class CMPartiesMasterAPIController extends AppBaseController
{
    /** @var  CMPartiesMasterRepository */
    private $cMPartiesMasterRepository;

    public function __construct(CMPartiesMasterRepository $cMPartiesMasterRepo)
    {
        $this->cMPartiesMasterRepository = $cMPartiesMasterRepo;
    }

    /**
     * Display a listing of the CMPartiesMaster.
     * GET|HEAD /cMPartiesMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMPartiesMasters = $this->cMPartiesMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMPartiesMasterResource::collection($cMPartiesMasters), 'Parties Masters retrieved successfully');
    }

    /**
     * Store a newly created CMPartiesMaster in storage.
     * POST /cMPartiesMasters
     *
     * @param CreateCMPartiesMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMPartiesMasterAPIRequest $request)
    {
        $input = $request->all();

        $cMPartiesMaster = $this->cMPartiesMasterRepository->create($input);

        return $this->sendResponse(new CMPartiesMasterResource($cMPartiesMaster), 'Parties Master saved successfully');
    }

    /**
     * Display the specified CMPartiesMaster.
     * GET|HEAD /cMPartiesMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMPartiesMaster $cMPartiesMaster */
        $cMPartiesMaster = $this->cMPartiesMasterRepository->find($id);

        if (empty($cMPartiesMaster)) {
            return $this->sendError('Parties Master not found');
        }

        return $this->sendResponse(new CMPartiesMasterResource($cMPartiesMaster), 'Parties Master retrieved successfully');
    }

    /**
     * Update the specified CMPartiesMaster in storage.
     * PUT/PATCH /cMPartiesMasters/{id}
     *
     * @param int $id
     * @param UpdateCMPartiesMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMPartiesMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMPartiesMaster $cMPartiesMaster */
        $cMPartiesMaster = $this->cMPartiesMasterRepository->find($id);

        if (empty($cMPartiesMaster)) {
            return $this->sendError('Parties Master not found');
        }

        $cMPartiesMaster = $this->cMPartiesMasterRepository->update($input, $id);

        return $this->sendResponse(new CMPartiesMasterResource($cMPartiesMaster), 'CMPartiesMaster updated successfully');
    }

    /**
     * Remove the specified CMPartiesMaster from storage.
     * DELETE /cMPartiesMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMPartiesMaster $cMPartiesMaster */
        $cMPartiesMaster = $this->cMPartiesMasterRepository->find($id);

        if (empty($cMPartiesMaster)) {
            return $this->sendError('Parties Master not found');
        }

        $cMPartiesMaster->delete();

        return $this->sendSuccess('Parties Master deleted successfully');
    }
}
