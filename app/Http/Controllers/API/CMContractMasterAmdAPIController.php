<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractMasterAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractMasterAmdAPIRequest;
use App\Models\CMContractMasterAmd;
use App\Repositories\CMContractMasterAmdRepository;
use App\Repositories\ContractMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractMasterAmdResource;
use Response;

/**
 * Class CMContractMasterAmdController
 * @package App\Http\Controllers\API
 */

class CMContractMasterAmdAPIController extends AppBaseController
{
    /** @var  CMContractMasterAmdRepository */
    private $cMContractMasterAmdRepository;
    private $contractMasterRepo;

    public function __construct
    (
        CMContractMasterAmdRepository $cMContractMasterAmdRepo, ContractMasterRepository $contractMasterRepo
    )
    {
        $this->cMContractMasterAmdRepository = $cMContractMasterAmdRepo;
        $this->contractMasterRepo = $contractMasterRepo;
    }

    /**
     * Display a listing of the CMContractMasterAmd.
     * GET|HEAD /cMContractMasterAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractMasterAmds = $this->cMContractMasterAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse
        (
            CMContractMasterAmdResource::collection($cMContractMasterAmds),
            'C M Contract Master Amds retrieved successfully'
        );
    }

    /**
     * Store a newly created CMContractMasterAmd in storage.
     * POST /cMContractMasterAmds
     *
     * @param CreateCMContractMasterAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractMasterAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractMasterAmd = $this->cMContractMasterAmdRepository->create($input);

        return $this->sendResponse
        (
            new CMContractMasterAmdResource($cMContractMasterAmd), 'C M Contract Master Amd saved successfully'
        );
    }

    /**
     * Display the specified CMContractMasterAmd.
     * GET|HEAD /cMContractMasterAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractMasterAmd $cMContractMasterAmd */
        $cMContractMasterAmd = $this->cMContractMasterAmdRepository->find($id);

        if (empty($cMContractMasterAmd))
        {
            return $this->sendError('C M Contract Master Amd not found');
        }

        return $this->sendResponse
        (
            new CMContractMasterAmdResource($cMContractMasterAmd), 'C M Contract Master Amd retrieved successfully'
        );
    }

    /**
     * Update the specified CMContractMasterAmd in storage.
     * PUT/PATCH /cMContractMasterAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractMasterAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractMasterAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractMasterAmd $cMContractMasterAmd */
        $cMContractMasterAmd = $this->cMContractMasterAmdRepository->find($id);

        if (empty($cMContractMasterAmd))
        {
            return $this->sendError('C M Contract Master Amd not found');
        }

        $cMContractMasterAmd = $this->cMContractMasterAmdRepository->update($input, $id);

        return $this->sendResponse
        (
            new CMContractMasterAmdResource($cMContractMasterAmd),
            'CMContractMasterAmd updated successfully'
        );
    }

    /**
     * Remove the specified CMContractMasterAmd from storage.
     * DELETE /cMContractMasterAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractMasterAmd $cMContractMasterAmd */
        $cMContractMasterAmd = $this->cMContractMasterAmdRepository->find($id);

        if (empty($cMContractMasterAmd))
        {
            return $this->sendError('C M Contract Master Amd not found');
        }

        $cMContractMasterAmd->delete();

        return $this->sendSuccess('C M Contract Master Amd deleted successfully');
    }

    public function getContractMasterData(Request $request)
    {
        try
        {
            return $this->cMContractMasterAmdRepository->getContractMasterData($request);
        } catch (\Exception $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
