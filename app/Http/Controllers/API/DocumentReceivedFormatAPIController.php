<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDocumentReceivedFormatAPIRequest;
use App\Http\Requests\API\UpdateDocumentReceivedFormatAPIRequest;
use App\Models\DocumentReceivedFormat;
use App\Repositories\DocumentReceivedFormatRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DocumentReceivedFormatResource;
use Response;

/**
 * Class DocumentReceivedFormatController
 * @package App\Http\Controllers\API
 */

class DocumentReceivedFormatAPIController extends AppBaseController
{
    /** @var  DocumentReceivedFormatRepository */
    private $documentReceivedFormatRepository;

    public function __construct(DocumentReceivedFormatRepository $documentReceivedFormatRepo)
    {
        $this->documentReceivedFormatRepository = $documentReceivedFormatRepo;
    }

    /**
     * Display a listing of the DocumentReceivedFormat.
     * GET|HEAD /documentReceivedFormats
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $documentReceivedFormats = $this->documentReceivedFormatRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(DocumentReceivedFormatResource::collection($documentReceivedFormats), 'Document Received Formats retrieved successfully');
    }

    /**
     * Store a newly created DocumentReceivedFormat in storage.
     * POST /documentReceivedFormats
     *
     * @param CreateDocumentReceivedFormatAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentReceivedFormatAPIRequest $request)
    {
        $input = $request->all();

        $documentReceivedFormat = $this->documentReceivedFormatRepository->create($input);

        return $this->sendResponse(new DocumentReceivedFormatResource($documentReceivedFormat), 'Document Received Format saved successfully');
    }

    /**
     * Display the specified DocumentReceivedFormat.
     * GET|HEAD /documentReceivedFormats/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentReceivedFormat $documentReceivedFormat */
        $documentReceivedFormat = $this->documentReceivedFormatRepository->find($id);

        if (empty($documentReceivedFormat)) {
            return $this->sendError('Document Received Format not found');
        }

        return $this->sendResponse(new DocumentReceivedFormatResource($documentReceivedFormat), 'Document Received Format retrieved successfully');
    }

    /**
     * Update the specified DocumentReceivedFormat in storage.
     * PUT/PATCH /documentReceivedFormats/{id}
     *
     * @param int $id
     * @param UpdateDocumentReceivedFormatAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentReceivedFormatAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentReceivedFormat $documentReceivedFormat */
        $documentReceivedFormat = $this->documentReceivedFormatRepository->find($id);

        if (empty($documentReceivedFormat)) {
            return $this->sendError('Document Received Format not found');
        }

        $documentReceivedFormat = $this->documentReceivedFormatRepository->update($input, $id);

        return $this->sendResponse(new DocumentReceivedFormatResource($documentReceivedFormat), 'DocumentReceivedFormat updated successfully');
    }

    /**
     * Remove the specified DocumentReceivedFormat from storage.
     * DELETE /documentReceivedFormats/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentReceivedFormat $documentReceivedFormat */
        $documentReceivedFormat = $this->documentReceivedFormatRepository->find($id);

        if (empty($documentReceivedFormat)) {
            return $this->sendError('Document Received Format not found');
        }

        $documentReceivedFormat->delete();

        return $this->sendSuccess('Document Received Format deleted successfully');
    }
}
