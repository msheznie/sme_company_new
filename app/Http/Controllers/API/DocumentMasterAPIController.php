<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDocumentMasterAPIRequest;
use App\Http\Requests\API\UpdateDocumentMasterAPIRequest;
use App\Models\DocumentMaster;
use App\Repositories\DocumentMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DocumentMasterResource;
use Response;

/**
 * Class DocumentMasterController
 * @package App\Http\Controllers\API
 */

class DocumentMasterAPIController extends AppBaseController
{
    /** @var  DocumentMasterRepository */
    private $documentMasterRepository;

    public function __construct(DocumentMasterRepository $documentMasterRepo)
    {
        $this->documentMasterRepository = $documentMasterRepo;
    }

    /**
     * Display a listing of the DocumentMaster.
     * GET|HEAD /documentMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $documentMasters = $this->documentMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(DocumentMasterResource::collection($documentMasters), 'Document Masters retrieved successfully');
    }

    /**
     * Store a newly created DocumentMaster in storage.
     * POST /documentMasters
     *
     * @param CreateDocumentMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentMasterAPIRequest $request)
    {
        $input = $request->all();

        $documentMaster = $this->documentMasterRepository->create($input);

        return $this->sendResponse(new DocumentMasterResource($documentMaster), 'Document Master saved successfully');
    }

    /**
     * Display the specified DocumentMaster.
     * GET|HEAD /documentMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentMaster $documentMaster */
        $documentMaster = $this->documentMasterRepository->find($id);

        if (empty($documentMaster)) {
            return $this->sendError('Document Master not found');
        }

        return $this->sendResponse(new DocumentMasterResource($documentMaster), 'Document Master retrieved successfully');
    }

    /**
     * Update the specified DocumentMaster in storage.
     * PUT/PATCH /documentMasters/{id}
     *
     * @param int $id
     * @param UpdateDocumentMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentMaster $documentMaster */
        $documentMaster = $this->documentMasterRepository->find($id);

        if (empty($documentMaster)) {
            return $this->sendError('Document Master not found');
        }

        $documentMaster = $this->documentMasterRepository->update($input, $id);

        return $this->sendResponse(new DocumentMasterResource($documentMaster), 'DocumentMaster updated successfully');
    }

    /**
     * Remove the specified DocumentMaster from storage.
     * DELETE /documentMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentMaster $documentMaster */
        $documentMaster = $this->documentMasterRepository->find($id);

        if (empty($documentMaster)) {
            return $this->sendError('Document Master not found');
        }

        $documentMaster->delete();

        return $this->sendSuccess('Document Master deleted successfully');
    }
}
