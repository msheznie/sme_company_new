<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpDocumentAttachmentsAPIRequest;
use App\Http\Requests\API\UpdateErpDocumentAttachmentsAPIRequest;
use App\Models\ErpDocumentAttachments;
use App\Repositories\ErpDocumentAttachmentsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpDocumentAttachmentsResource;
use Response;

/**
 * Class ErpDocumentAttachmentsController
 * @package App\Http\Controllers\API
 */

class ErpDocumentAttachmentsAPIController extends AppBaseController
{
    /** @var  ErpDocumentAttachmentsRepository */
    private $erpDocumentAttachmentsRepository;

    public function __construct(ErpDocumentAttachmentsRepository $erpDocumentAttachmentsRepo)
    {
        $this->erpDocumentAttachmentsRepository = $erpDocumentAttachmentsRepo;
    }

    /**
     * Display a listing of the ErpDocumentAttachments.
     * GET|HEAD /erpDocumentAttachments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpDocumentAttachments = $this->erpDocumentAttachmentsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpDocumentAttachmentsResource::collection($erpDocumentAttachments), 'Erp Document Attachments retrieved successfully');
    }

    /**
     * Store a newly created ErpDocumentAttachments in storage.
     * POST /erpDocumentAttachments
     *
     * @param CreateErpDocumentAttachmentsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpDocumentAttachmentsAPIRequest $request)
    {
        $input = $request->all();

        $erpDocumentAttachments = $this->erpDocumentAttachmentsRepository->create($input);

        return $this->sendResponse(new ErpDocumentAttachmentsResource($erpDocumentAttachments), 'Erp Document Attachments saved successfully');
    }

    /**
     * Display the specified ErpDocumentAttachments.
     * GET|HEAD /erpDocumentAttachments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpDocumentAttachments $erpDocumentAttachments */
        $erpDocumentAttachments = $this->erpDocumentAttachmentsRepository->find($id);

        if (empty($erpDocumentAttachments)) {
            return $this->sendError('Erp Document Attachments not found');
        }

        return $this->sendResponse(new ErpDocumentAttachmentsResource($erpDocumentAttachments), 'Erp Document Attachments retrieved successfully');
    }

    /**
     * Update the specified ErpDocumentAttachments in storage.
     * PUT/PATCH /erpDocumentAttachments/{id}
     *
     * @param int $id
     * @param UpdateErpDocumentAttachmentsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpDocumentAttachmentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpDocumentAttachments $erpDocumentAttachments */
        $erpDocumentAttachments = $this->erpDocumentAttachmentsRepository->find($id);

        if (empty($erpDocumentAttachments)) {
            return $this->sendError('Erp Document Attachments not found');
        }

        $erpDocumentAttachments = $this->erpDocumentAttachmentsRepository->update($input, $id);

        return $this->sendResponse(new ErpDocumentAttachmentsResource($erpDocumentAttachments), 'ErpDocumentAttachments updated successfully');
    }

    /**
     * Remove the specified ErpDocumentAttachments from storage.
     * DELETE /erpDocumentAttachments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpDocumentAttachments $erpDocumentAttachments */
        $erpDocumentAttachments = $this->erpDocumentAttachmentsRepository->find($id);

        if (empty($erpDocumentAttachments)) {
            return $this->sendError('Erp Document Attachments not found');
        }

        $erpDocumentAttachments->delete();

        return $this->sendSuccess('Erp Document Attachments deleted successfully');
    }

    public function downloadAttachment(Request $request) {
        $id = $request->input('id') ?? 0;

        $documentAttachments = $this->erpDocumentAttachmentsRepository->downloadFile($id);
        if ($documentAttachments['status']) {
            return $documentAttachments['data'];
        } else {
            return $this->sendError($documentAttachments['message'], $documentAttachments['code']);
        }
    }
}
