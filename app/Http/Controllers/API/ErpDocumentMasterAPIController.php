<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpDocumentMasterAPIRequest;
use App\Http\Requests\API\UpdateErpDocumentMasterAPIRequest;
use App\Models\ErpDocumentMaster;
use App\Repositories\ErpDocumentMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpDocumentMasterResource;
use Response;

/**
 * Class ErpDocumentMasterController
 * @package App\Http\Controllers\API
 */

class ErpDocumentMasterAPIController extends AppBaseController
{
    /** @var  ErpDocumentMasterRepository */
    private $erpDocumentMasterRepository;

    public function __construct(ErpDocumentMasterRepository $erpDocumentMasterRepo)
    {
        $this->erpDocumentMasterRepository = $erpDocumentMasterRepo;
    }

    /**
     * Display a listing of the ErpDocumentMaster.
     * GET|HEAD /erpDocumentMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpDocumentMasters = $this->erpDocumentMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpDocumentMasterResource::collection($erpDocumentMasters), 'Erp Document Masters retrieved successfully');
    }

    /**
     * Store a newly created ErpDocumentMaster in storage.
     * POST /erpDocumentMasters
     *
     * @param CreateErpDocumentMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpDocumentMasterAPIRequest $request)
    {
        $input = $request->all();

        $erpDocumentMaster = $this->erpDocumentMasterRepository->create($input);

        return $this->sendResponse(new ErpDocumentMasterResource($erpDocumentMaster), 'Erp Document Master saved successfully');
    }

    /**
     * Display the specified ErpDocumentMaster.
     * GET|HEAD /erpDocumentMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpDocumentMaster $erpDocumentMaster */
        $erpDocumentMaster = $this->erpDocumentMasterRepository->find($id);

        if (empty($erpDocumentMaster)) {
            return $this->sendError('Erp Document Master not found');
        }

        return $this->sendResponse(new ErpDocumentMasterResource($erpDocumentMaster), 'Erp Document Master retrieved successfully');
    }

    /**
     * Update the specified ErpDocumentMaster in storage.
     * PUT/PATCH /erpDocumentMasters/{id}
     *
     * @param int $id
     * @param UpdateErpDocumentMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpDocumentMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpDocumentMaster $erpDocumentMaster */
        $erpDocumentMaster = $this->erpDocumentMasterRepository->find($id);

        if (empty($erpDocumentMaster)) {
            return $this->sendError('Erp Document Master not found');
        }

        $erpDocumentMaster = $this->erpDocumentMasterRepository->update($input, $id);

        return $this->sendResponse(new ErpDocumentMasterResource($erpDocumentMaster), 'ErpDocumentMaster updated successfully');
    }

    /**
     * Remove the specified ErpDocumentMaster from storage.
     * DELETE /erpDocumentMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpDocumentMaster $erpDocumentMaster */
        $erpDocumentMaster = $this->erpDocumentMasterRepository->find($id);

        if (empty($erpDocumentMaster)) {
            return $this->sendError('Erp Document Master not found');
        }

        $erpDocumentMaster->delete();

        return $this->sendSuccess('Erp Document Master deleted successfully');
    }
}
