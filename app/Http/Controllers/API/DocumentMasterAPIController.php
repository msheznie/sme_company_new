<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDocumentMasterAPIRequest;
use App\Http\Requests\API\UpdateDocumentMasterAPIRequest;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractDocument;
use App\Models\ContractMaster;
use App\Models\DocumentMaster;
use App\Repositories\DocumentMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DocumentMasterResource;
use Illuminate\Support\Facades\Log;
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

        return $this->sendResponse(DocumentMasterResource::collection($documentMasters),
            'Document Masters retrieved successfully');
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

        $documentMaster = $this->documentMasterRepository->createDocumentMaster($input);

        if($documentMaster['status']){
            return $this->sendResponse([], $documentMaster['message']);
        } else{
            $statusCode = $documentMaster['code'] ?? 404;
            return $this->sendError($documentMaster['message'], $statusCode);
        }
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

        return
            $this->sendResponse(new DocumentMasterResource($documentMaster), 'Document Master retrieved successfully');
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
        $documentId = DocumentMaster::select('id', 'companySystemID')->where('uuid', $id)->first();
        $documentMaster = $this->documentMasterRepository->find($documentId->id);

        $contractDocument = ContractDocument::where('documentType', $documentId->id)
            ->where('companySystemID', $documentId->companySystemID)->first();
        $contractAdditionalDocument = ContractAdditionalDocuments::where('documentType', $documentId->id)
            ->where('companySystemID', $documentId->companySystemID)->first();

        if ($contractDocument || $contractAdditionalDocument) {
            return $this->sendError(trans('common.document_type_is_already_used_in_the_contract'));
        }

        if (empty($documentMaster)) {
            return $this->sendError(trans('common.document_master_not_found'));
        }

        $documentMaster->delete();

        return $this->sendSuccess(trans('common.document_master_deleted_successfully'));
    }

    public function getDocumentMasterData(Request $request){
        return $this->documentMasterRepository->getDocumentMasterData($request);
    }

    public function documentStatusUpdate(Request $request){
        $documentStatus = $this->documentMasterRepository->documentStatusUpdate($request);
        return $this->sendResponse($documentStatus, trans('common.document_status_updated_successfully'));
    }
}
