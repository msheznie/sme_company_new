<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractsMasterAPIRequest;
use App\Http\Requests\API\UpdateCMContractsMasterAPIRequest;
use App\Models\CMContractsMaster;
use App\Repositories\CMContractsMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractsMasterResource;
use Response;

/**
 * Class CMContractsMasterController
 * @package App\Http\Controllers\API
 */

class CMContractsMasterAPIController extends AppBaseController
{
    /** @var  CMContractsMasterRepository */
    private $cMContractsMasterRepository;

    public function __construct(CMContractsMasterRepository $cMContractsMasterRepo)
    {
        $this->cMContractsMasterRepository = $cMContractsMasterRepo;
    }

    /**
     * Display a listing of the CMContractsMaster.
     * GET|HEAD /cMContractsMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractsMasters = $this->cMContractsMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractsMasterResource::collection($cMContractsMasters), 'Contracts Masters retrieved successfully');
    }

    /**
     * Store a newly created CMContractsMaster in storage.
     * POST /cMContractsMasters
     *
     * @param CreateCMContractsMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractsMasterAPIRequest $request)
    {
        $input = $request->all();

        $cMContractsMaster = $this->cMContractsMasterRepository->create($input);

        return $this->sendResponse(new CMContractsMasterResource($cMContractsMaster), 'Contracts Master saved successfully');
    }

    /**
     * Display the specified CMContractsMaster.
     * GET|HEAD /cMContractsMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractsMaster $cMContractsMaster */
        $cMContractsMaster = $this->cMContractsMasterRepository->find($id);

        if (empty($cMContractsMaster)) {
            return $this->sendError('Contracts Master not found');
        }

        return $this->sendResponse(new CMContractsMasterResource($cMContractsMaster), 'Contracts Master retrieved successfully');
    }

    /**
     * Update the specified CMContractsMaster in storage.
     * PUT/PATCH /cMContractsMasters/{id}
     *
     * @param int $id
     * @param UpdateCMContractsMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractsMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractsMaster $cMContractsMaster */
        $cMContractsMaster = $this->cMContractsMasterRepository->find($id);

        if (empty($cMContractsMaster)) {
            return $this->sendError('Contracts Master not found');
        }

        $cMContractsMaster = $this->cMContractsMasterRepository->update($input, $id);

        return $this->sendResponse(new CMContractsMasterResource($cMContractsMaster), 'CMContractsMaster updated successfully');
    }

    /**
     * Remove the specified CMContractsMaster from storage.
     * DELETE /cMContractsMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractsMaster $cMContractsMaster */
        $cMContractsMaster = $this->cMContractsMasterRepository->find($id);

        if (empty($cMContractsMaster)) {
            return $this->sendError('Contracts Master not found');
        }

        $cMContractsMaster->delete();

        return $this->sendSuccess('Contracts Master deleted successfully');
    }
}
