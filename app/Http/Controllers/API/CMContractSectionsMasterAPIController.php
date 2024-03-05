<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractSectionsMasterAPIRequest;
use App\Http\Requests\API\UpdateCMContractSectionsMasterAPIRequest;
use App\Models\CMContractSectionsMaster;
use App\Repositories\CMContractSectionsMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractSectionsMasterResource;
use Response;

/**
 * Class CMContractSectionsMasterController
 * @package App\Http\Controllers\API
 */

class CMContractSectionsMasterAPIController extends AppBaseController
{
    /** @var  CMContractSectionsMasterRepository */
    private $cMContractSectionsMasterRepository;

    public function __construct(CMContractSectionsMasterRepository $cMContractSectionsMasterRepo)
    {
        $this->cMContractSectionsMasterRepository = $cMContractSectionsMasterRepo;
    }

    /**
     * Display a listing of the CMContractSectionsMaster.
     * GET|HEAD /cMContractSectionsMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractSectionsMasters = $this->cMContractSectionsMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractSectionsMasterResource::collection($cMContractSectionsMasters), 'C M Contract Sections Masters retrieved successfully');
    }

    /**
     * Store a newly created CMContractSectionsMaster in storage.
     * POST /cMContractSectionsMasters
     *
     * @param CreateCMContractSectionsMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractSectionsMasterAPIRequest $request)
    {
        $input = $request->all();

        $cMContractSectionsMaster = $this->cMContractSectionsMasterRepository->create($input);

        return $this->sendResponse(new CMContractSectionsMasterResource($cMContractSectionsMaster), 'C M Contract Sections Master saved successfully');
    }

    /**
     * Display the specified CMContractSectionsMaster.
     * GET|HEAD /cMContractSectionsMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractSectionsMaster $cMContractSectionsMaster */
        $cMContractSectionsMaster = $this->cMContractSectionsMasterRepository->find($id);

        if (empty($cMContractSectionsMaster)) {
            return $this->sendError('C M Contract Sections Master not found');
        }

        return $this->sendResponse(new CMContractSectionsMasterResource($cMContractSectionsMaster), 'C M Contract Sections Master retrieved successfully');
    }

    /**
     * Update the specified CMContractSectionsMaster in storage.
     * PUT/PATCH /cMContractSectionsMasters/{id}
     *
     * @param int $id
     * @param UpdateCMContractSectionsMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractSectionsMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractSectionsMaster $cMContractSectionsMaster */
        $cMContractSectionsMaster = $this->cMContractSectionsMasterRepository->find($id);

        if (empty($cMContractSectionsMaster)) {
            return $this->sendError('C M Contract Sections Master not found');
        }

        $cMContractSectionsMaster = $this->cMContractSectionsMasterRepository->update($input, $id);

        return $this->sendResponse(new CMContractSectionsMasterResource($cMContractSectionsMaster), 'CMContractSectionsMaster updated successfully');
    }

    /**
     * Remove the specified CMContractSectionsMaster from storage.
     * DELETE /cMContractSectionsMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractSectionsMaster $cMContractSectionsMaster */
        $cMContractSectionsMaster = $this->cMContractSectionsMasterRepository->find($id);

        if (empty($cMContractSectionsMaster)) {
            return $this->sendError('C M Contract Sections Master not found');
        }

        $cMContractSectionsMaster->delete();

        return $this->sendSuccess('C M Contract Sections Master deleted successfully');
    }
}
