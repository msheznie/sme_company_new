<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCMContractBoqItemsAmdAPIRequest;
use App\Http\Requests\API\UpdateCMContractBoqItemsAmdAPIRequest;
use App\Models\CMContractBoqItemsAmd;
use App\Repositories\CMContractBoqItemsAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractBoqItemsAmdResource;
use Response;

/**
 * Class CMContractBoqItemsAmdController
 * @package App\Http\Controllers\API
 */

class CMContractBoqItemsAmdAPIController extends AppBaseController
{
    /** @var  CMContractBoqItemsAmdRepository */
    private $cMContractBoqItemsAmdRepository;

    public function __construct(CMContractBoqItemsAmdRepository $cMContractBoqItemsAmdRepo)
    {
        $this->cMContractBoqItemsAmdRepository = $cMContractBoqItemsAmdRepo;
    }

    /**
     * Display a listing of the CMContractBoqItemsAmd.
     * GET|HEAD /cMContractBoqItemsAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cMContractBoqItemsAmds = $this->cMContractBoqItemsAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CMContractBoqItemsAmdResource::collection($cMContractBoqItemsAmds), 'C M Contract Boq Items Amds retrieved successfully');
    }

    /**
     * Store a newly created CMContractBoqItemsAmd in storage.
     * POST /cMContractBoqItemsAmds
     *
     * @param CreateCMContractBoqItemsAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractBoqItemsAmdAPIRequest $request)
    {
        $input = $request->all();

        $cMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepository->create($input);

        return $this->sendResponse(new CMContractBoqItemsAmdResource($cMContractBoqItemsAmd), 'C M Contract Boq Items Amd saved successfully');
    }

    /**
     * Display the specified CMContractBoqItemsAmd.
     * GET|HEAD /cMContractBoqItemsAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractBoqItemsAmd $cMContractBoqItemsAmd */
        $cMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepository->find($id);

        if (empty($cMContractBoqItemsAmd)) {
            return $this->sendError('C M Contract Boq Items Amd not found');
        }

        return $this->sendResponse(new CMContractBoqItemsAmdResource($cMContractBoqItemsAmd), 'C M Contract Boq Items Amd retrieved successfully');
    }

    /**
     * Update the specified CMContractBoqItemsAmd in storage.
     * PUT/PATCH /cMContractBoqItemsAmds/{id}
     *
     * @param int $id
     * @param UpdateCMContractBoqItemsAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractBoqItemsAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractBoqItemsAmd $cMContractBoqItemsAmd */
        $cMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepository->find($id);

        if (empty($cMContractBoqItemsAmd)) {
            return $this->sendError('C M Contract Boq Items Amd not found');
        }

        $cMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepository->update($input, $id);

        return $this->sendResponse(new CMContractBoqItemsAmdResource($cMContractBoqItemsAmd), 'CMContractBoqItemsAmd updated successfully');
    }

    /**
     * Remove the specified CMContractBoqItemsAmd from storage.
     * DELETE /cMContractBoqItemsAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractBoqItemsAmd $cMContractBoqItemsAmd */
        $cMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepository->find($id);

        if (empty($cMContractBoqItemsAmd)) {
            return $this->sendError('C M Contract Boq Items Amd not found');
        }

        $cMContractBoqItemsAmd->delete();

        return $this->sendSuccess('C M Contract Boq Items Amd deleted successfully');
    }
}
