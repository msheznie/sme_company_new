<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMIntentsMasterAPIRequest;
use App\Http\Requests\API\UpdateCMIntentsMasterAPIRequest;
use App\Models\CMIntentsMaster;
use App\Repositories\CMIntentsMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMIntentsMasterResource;
use Response;

/**
 * Class CMIntentsMasterController
 * @package App\Http\Controllers\API
 */

class CMIntentsMasterAPIController extends AppBaseController
{
    /** @var  CMIntentsMasterRepository */
    private $cMIntentsMasterRepository;

    public function __construct(CMIntentsMasterRepository $cMIntentsMasterRepo)
    {
        $this->cMIntentsMasterRepository = $cMIntentsMasterRepo;
    }

    /**
     * Display a listing of the CMIntentsMaster.
     * GET|HEAD /cMIntentsMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMIntentsMasters = $this->cMIntentsMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMIntentsMasterResource::collection($cMIntentsMasters), 'Intents Masters retrieved successfully');
    }

    /**
     * Store a newly created CMIntentsMaster in storage.
     * POST /cMIntentsMasters
     *
     * @param CreateCMIntentsMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMIntentsMasterAPIRequest $request)
    {
        $input = $request->all();

        $cMIntentsMaster = $this->cMIntentsMasterRepository->create($input);

        return $this->sendResponse(new CMIntentsMasterResource($cMIntentsMaster), 'Intents Master saved successfully');
    }

    /**
     * Display the specified CMIntentsMaster.
     * GET|HEAD /cMIntentsMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMIntentsMaster $cMIntentsMaster */
        $cMIntentsMaster = $this->cMIntentsMasterRepository->find($id);

        if (empty($cMIntentsMaster)) {
            return $this->sendError('Intents Master not found');
        }

        return $this->sendResponse(new CMIntentsMasterResource($cMIntentsMaster), 'Intents Master retrieved successfully');
    }

    /**
     * Update the specified CMIntentsMaster in storage.
     * PUT/PATCH /cMIntentsMasters/{id}
     *
     * @param int $id
     * @param UpdateCMIntentsMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMIntentsMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMIntentsMaster $cMIntentsMaster */
        $cMIntentsMaster = $this->cMIntentsMasterRepository->find($id);

        if (empty($cMIntentsMaster)) {
            return $this->sendError('Intents Master not found');
        }

        $cMIntentsMaster = $this->cMIntentsMasterRepository->update($input, $id);

        return $this->sendResponse(new CMIntentsMasterResource($cMIntentsMaster), 'CMIntentsMaster updated successfully');
    }

    /**
     * Remove the specified CMIntentsMaster from storage.
     * DELETE /cMIntentsMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMIntentsMaster $cMIntentsMaster */
        $cMIntentsMaster = $this->cMIntentsMasterRepository->find($id);

        if (empty($cMIntentsMaster)) {
            return $this->sendError('Intents Master not found');
        }

        $cMIntentsMaster->delete();

        return $this->sendSuccess('Intents Master deleted successfully');
    }
}
