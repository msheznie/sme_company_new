<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGRVMasterAPIRequest;
use App\Http\Requests\API\UpdateGRVMasterAPIRequest;
use App\Models\GRVMaster;
use App\Repositories\GRVMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\GRVMasterResource;
use Response;

/**
 * Class GRVMasterController
 * @package App\Http\Controllers\API
 */

class GRVMasterAPIController extends AppBaseController
{
    /** @var  GRVMasterRepository */
    private $gRVMasterRepository;
    protected $message = 'GRV Master not found';

    public function __construct(GRVMasterRepository $gRVMasterRepo)
    {
        $this->gRVMasterRepository = $gRVMasterRepo;
    }

    /**
     * Display a listing of the GRVMaster.
     * GET|HEAD /gRVMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $gRVMasters = $this->gRVMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(GRVMasterResource::collection($gRVMasters),
            'G R V Masters retrieved successfully');
    }

    /**
     * Store a newly created GRVMaster in storage.
     * POST /gRVMasters
     *
     * @param CreateGRVMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateGRVMasterAPIRequest $request)
    {
        $input = $request->all();

        $gRVMaster = $this->gRVMasterRepository->create($input);

        return $this->sendResponse(new GRVMasterResource($gRVMaster),
            'G R V Master saved successfully');
    }

    /**
     * Display the specified GRVMaster.
     * GET|HEAD /gRVMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var GRVMaster $gRVMaster */
        $gRVMaster = $this->gRVMasterRepository->find($id);

        if (empty($gRVMaster))
        {
            return $this->sendError($this->message);
        }

        return $this->sendResponse(new GRVMasterResource($gRVMaster),
            'G R V Master retrieved successfully');
    }

    /**
     * Update the specified GRVMaster in storage.
     * PUT/PATCH /gRVMasters/{id}
     *
     * @param int $id
     * @param UpdateGRVMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGRVMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var GRVMaster $gRVMaster */
        $gRVMaster = $this->gRVMasterRepository->find($id);

        if (empty($gRVMaster))
        {
            return $this->sendError($this->message);
        }

        $gRVMaster = $this->gRVMasterRepository->update($input, $id);

        return $this->sendResponse(new GRVMasterResource($gRVMaster),
            'GRVMaster updated successfully');
    }

    /**
     * Remove the specified GRVMaster from storage.
     * DELETE /gRVMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var GRVMaster $gRVMaster */
        $gRVMaster = $this->gRVMasterRepository->find($id);

        if (empty($gRVMaster))
        {
            return $this->sendError($this->message);
        }

        $gRVMaster->delete();

        return $this->sendSuccess('G R V Master deleted successfully');
    }
}
