<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpDocumentAttachmentsAmdAPIRequest;
use App\Http\Requests\API\UpdateErpDocumentAttachmentsAmdAPIRequest;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Repositories\ErpDocumentAttachmentsAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpDocumentAttachmentsAmdResource;
use Response;

/**
 * Class ErpDocumentAttachmentsAmdController
 * @package App\Http\Controllers\API
 */

class ErpDocumentAttachmentsAmdAPIController extends AppBaseController
{
    /** @var  ErpDocumentAttachmentsAmdRepository */
    private $erpDocumentAttachmentsAmdRepository;

    public function __construct(ErpDocumentAttachmentsAmdRepository $erpDocumentAttachmentsAmdRepo)
    {
        $this->erpDocumentAttachmentsAmdRepository = $erpDocumentAttachmentsAmdRepo;
    }

    /**
     * Display a listing of the ErpDocumentAttachmentsAmd.
     * GET|HEAD /erpDocumentAttachmentsAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpDocumentAttachmentsAmds = $this->erpDocumentAttachmentsAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpDocumentAttachmentsAmdResource::collection($erpDocumentAttachmentsAmds), 'Erp Document Attachments Amds retrieved successfully');
    }

    /**
     * Store a newly created ErpDocumentAttachmentsAmd in storage.
     * POST /erpDocumentAttachmentsAmds
     *
     * @param CreateErpDocumentAttachmentsAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpDocumentAttachmentsAmdAPIRequest $request)
    {
        $input = $request->all();

        $erpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepository->create($input);

        return $this->sendResponse(new ErpDocumentAttachmentsAmdResource($erpDocumentAttachmentsAmd), 'Erp Document Attachments Amd saved successfully');
    }

    /**
     * Display the specified ErpDocumentAttachmentsAmd.
     * GET|HEAD /erpDocumentAttachmentsAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpDocumentAttachmentsAmd $erpDocumentAttachmentsAmd */
        $erpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepository->find($id);

        if (empty($erpDocumentAttachmentsAmd)) {
            return $this->sendError('Erp Document Attachments Amd not found');
        }

        return $this->sendResponse(new ErpDocumentAttachmentsAmdResource($erpDocumentAttachmentsAmd), 'Erp Document Attachments Amd retrieved successfully');
    }

    /**
     * Update the specified ErpDocumentAttachmentsAmd in storage.
     * PUT/PATCH /erpDocumentAttachmentsAmds/{id}
     *
     * @param int $id
     * @param UpdateErpDocumentAttachmentsAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpDocumentAttachmentsAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpDocumentAttachmentsAmd $erpDocumentAttachmentsAmd */
        $erpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepository->find($id);

        if (empty($erpDocumentAttachmentsAmd)) {
            return $this->sendError('Erp Document Attachments Amd not found');
        }

        $erpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepository->update($input, $id);

        return $this->sendResponse(new ErpDocumentAttachmentsAmdResource($erpDocumentAttachmentsAmd), 'ErpDocumentAttachmentsAmd updated successfully');
    }

    /**
     * Remove the specified ErpDocumentAttachmentsAmd from storage.
     * DELETE /erpDocumentAttachmentsAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpDocumentAttachmentsAmd $erpDocumentAttachmentsAmd */
        $erpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepository->find($id);

        if (empty($erpDocumentAttachmentsAmd)) {
            return $this->sendError('Erp Document Attachments Amd not found');
        }

        $erpDocumentAttachmentsAmd->delete();

        return $this->sendSuccess('Erp Document Attachments Amd deleted successfully');
    }
}
