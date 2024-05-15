<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractAdditionalDocumentsAPIRequest;
use App\Http\Requests\API\UpdateContractAdditionalDocumentsAPIRequest;
use App\Models\ContractAdditionalDocuments;
use App\Repositories\ContractAdditionalDocumentsRepository;
use App\Repositories\ErpDocumentAttachmentsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractAdditionalDocumentsResource;
use Response;

/**
 * Class ContractAdditionalDocumentsController
 * @package App\Http\Controllers\API
 */

class ContractAdditionalDocumentsAPIController extends AppBaseController
{
    /** @var  ContractAdditionalDocumentsRepository */
    private $contractAdditionalDocumentsRepository;
    private $erpDocumentAttachmentsRepository;

    public function __construct(
        ContractAdditionalDocumentsRepository $contractAdditionalDocumentsRepo,
        ErpDocumentAttachmentsRepository $erpDocumentAttachmentsRepo
    )
    {
        $this->contractAdditionalDocumentsRepository = $contractAdditionalDocumentsRepo;
        $this->erpDocumentAttachmentsRepository = $erpDocumentAttachmentsRepo;
    }

    /**
     * Display a listing of the ContractAdditionalDocuments.
     * GET|HEAD /contractAdditionalDocuments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractAdditionalDocuments = $this->contractAdditionalDocumentsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractAdditionalDocumentsResource::collection($contractAdditionalDocuments),
            'Contract Additional Documents retrieved successfully');
    }

    /**
     * Store a newly created ContractAdditionalDocuments in storage.
     * POST /contractAdditionalDocuments
     *
     * @param CreateContractAdditionalDocumentsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractAdditionalDocumentsAPIRequest $request)
    {
        $input = $request->all();

        $contractAdditionalDocuments = $this->contractAdditionalDocumentsRepository->createAdditionalDocument($input);

        if (!$contractAdditionalDocuments['status']) {
            return $this->sendError($contractAdditionalDocuments['message']);
        } else {
            return $this->sendResponse([], $contractAdditionalDocuments['message']);
        }
    }

    /**
     * Display the specified ContractAdditionalDocuments.
     * GET|HEAD /contractAdditionalDocuments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractAdditionalDocuments $contractAdditionalDocuments */
        $contractAdditionalDocuments = $this->contractAdditionalDocumentsRepository->findByUuid($id,
            ['documentName']
        );

        if (empty($contractAdditionalDocuments)) {
            return $this->sendError(trans('common.contract_additional_document_not_found'));
        }

        return $this->sendResponse($contractAdditionalDocuments,
            trans('common.contract_additional_document_retrieved_successfully'));
    }

    /**
     * Update the specified ContractAdditionalDocuments in storage.
     * PUT/PATCH /contractAdditionalDocuments/{id}
     *
     * @param int $id
     * @param UpdateContractAdditionalDocumentsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractAdditionalDocumentsAPIRequest $request)
    {
        $input = $request->all();
        /** @var ContractAdditionalDocuments $contractAdditionalDocuments */
        $contractAdditionalDocuments = $this->contractAdditionalDocumentsRepository->findByUuid($id, ['id']);

        if (empty($contractAdditionalDocuments)) {
            return $this->sendError(trans('common.contract_additional_document_not_found'));
        }
        $additionalDocument = $this->contractAdditionalDocumentsRepository
            ->updateAdditionalDocumentHeader($input, $contractAdditionalDocuments['id']);
        if (!$additionalDocument['status']) {
            return $this->sendError($additionalDocument['message']);
        } else {
            return $this->sendResponse([], $additionalDocument['message']);
        }
    }

    /**
     * Remove the specified ContractAdditionalDocuments from storage.
     * DELETE /contractAdditionalDocuments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractAdditionalDocuments $contractAdditionalDocuments */
        $contractAdditionalDocuments = $this->contractAdditionalDocumentsRepository->findByUuid($id, ['id']);

        if (empty($contractAdditionalDocuments)) {
            return $this->sendError(trans('common.contract_additional_document_not_found'));
        }
        $contractAdditionalDocuments->delete();
        return $this->sendSuccess(trans('common.contract_additional_document_deleted_successfully'));
    }
    public function getAdditionalDocumentList(Request $request) {
        return $this->contractAdditionalDocumentsRepository->additionalDocumentList($request);
    }
    public function addAdditionalAttachment(Request $request)
    {
        $documentMasterID = $request->input('documentMasterID') ?? 122;
        $additionalDocument = $this->contractAdditionalDocumentsRepository->findByUuid(
            $request->input('uuid'),
            ['id', 'documentName']
        );
        if(empty($additionalDocument)) {
            return $this->sendError(trans('common.contract_additional_document_not_found'));
        }
        $deleteOldAttachment = $this->contractAdditionalDocumentsRepository->deleteFile($documentMasterID,
            $additionalDocument['id']);
        if(!$deleteOldAttachment['status']){
            return $this->sendError($deleteOldAttachment['message']);
        }
        $attachment = $this->erpDocumentAttachmentsRepository
            ->saveDocumentAttachments($request, $additionalDocument['id']);
        if(!$attachment['status']) {
            $errorCode = $attachment['code'] ?? 404;
            return $this->sendError($attachment['message'], $errorCode);
        } else{
            return $this->sendResponse([], $attachment['message']);
        }
    }
}
