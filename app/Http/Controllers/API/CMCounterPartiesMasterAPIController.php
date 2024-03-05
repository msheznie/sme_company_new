<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMCounterPartiesMasterAPIRequest;
use App\Http\Requests\API\UpdateCMCounterPartiesMasterAPIRequest;
use App\Models\CMCounterPartiesMaster;
use App\Repositories\CMCounterPartiesMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMCounterPartiesMasterResource;
use Response;

/**
 * Class CMCounterPartiesMasterController
 * @package App\Http\Controllers\API
 */

class CMCounterPartiesMasterAPIController extends AppBaseController
{
    /** @var  CMCounterPartiesMasterRepository */
    private $cMCounterPartiesMasterRepository;

    public function __construct(CMCounterPartiesMasterRepository $cMCounterPartiesMasterRepo)
    {
        $this->cMCounterPartiesMasterRepository = $cMCounterPartiesMasterRepo;
    }

    /**
     * Display a listing of the CMCounterPartiesMaster.
     * GET|HEAD /cMCounterPartiesMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMCounterPartiesMasters = $this->cMCounterPartiesMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMCounterPartiesMasterResource::collection($cMCounterPartiesMasters), 'Counter Parties Masters retrieved successfully');
    }

    /**
     * Store a newly created CMCounterPartiesMaster in storage.
     * POST /cMCounterPartiesMasters
     *
     * @param CreateCMCounterPartiesMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMCounterPartiesMasterAPIRequest $request)
    {
        $input = $request->all();

        $cMCounterPartiesMaster = $this->cMCounterPartiesMasterRepository->create($input);

        return $this->sendResponse(new CMCounterPartiesMasterResource($cMCounterPartiesMaster), 'Counter Parties Master saved successfully');
    }

    /**
     * Display the specified CMCounterPartiesMaster.
     * GET|HEAD /cMCounterPartiesMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMCounterPartiesMaster $cMCounterPartiesMaster */
        $cMCounterPartiesMaster = $this->cMCounterPartiesMasterRepository->find($id);

        if (empty($cMCounterPartiesMaster)) {
            return $this->sendError('Counter Parties Master not found');
        }

        return $this->sendResponse(new CMCounterPartiesMasterResource($cMCounterPartiesMaster), 'Counter Parties Master retrieved successfully');
    }

    /**
     * Update the specified CMCounterPartiesMaster in storage.
     * PUT/PATCH /cMCounterPartiesMasters/{id}
     *
     * @param int $id
     * @param UpdateCMCounterPartiesMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMCounterPartiesMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMCounterPartiesMaster $cMCounterPartiesMaster */
        $cMCounterPartiesMaster = $this->cMCounterPartiesMasterRepository->find($id);

        if (empty($cMCounterPartiesMaster)) {
            return $this->sendError('Counter Parties Master not found');
        }

        $cMCounterPartiesMaster = $this->cMCounterPartiesMasterRepository->update($input, $id);

        return $this->sendResponse(new CMCounterPartiesMasterResource($cMCounterPartiesMaster), 'CMCounterPartiesMaster updated successfully');
    }

    /**
     * Remove the specified CMCounterPartiesMaster from storage.
     * DELETE /cMCounterPartiesMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMCounterPartiesMaster $cMCounterPartiesMaster */
        $cMCounterPartiesMaster = $this->cMCounterPartiesMasterRepository->find($id);

        if (empty($cMCounterPartiesMaster)) {
            return $this->sendError('Counter Parties Master not found');
        }

        $cMCounterPartiesMaster->delete();

        return $this->sendSuccess('Counter Parties Master deleted successfully');
    }
}
