<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ContractCreationException;
use App\Http\Requests\API\CreateContractDocumentAPIRequest;
use App\Http\Requests\API\UpdateContractDocumentAPIRequest;
use App\Models\ContractDocument;
use App\Repositories\ContractDocumentRepository;
use App\Repositories\ErpDocumentAttachmentsRepository;
use App\Services\ContractAmendmentService;
use App\Services\ContractDocumentService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractDocumentResource;
use Carbon\Carbon;
use Response;

/**
 * Class ContractDocumentController
 * @package App\Http\Controllers\API
 */

class ContractDocumentAPIController extends AppBaseController
{
    /** @var  ContractDocumentRepository */
    private $contractDocumentRepository;
    private $erpDocumentAttachmentsRepository;

    protected $contractDocumentService;
    public function __construct(
        ContractDocumentRepository $contractDocumentRepo,
        ErpDocumentAttachmentsRepository $erpDocumentAttachmentsRepo,
        ContractDocumentService $contractDocumentService
    )
    {
        $this->contractDocumentRepository = $contractDocumentRepo;
        $this->erpDocumentAttachmentsRepository = $erpDocumentAttachmentsRepo;
        $this->contractDocumentService = $contractDocumentService;
    }
    const UNEXPECTED_ERROR_MESSAGE = 'An unexpected error occurred.';
    /**
     * Display a listing of the ContractDocument.
     * GET|HEAD /contractDocuments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractDocuments = $this->contractDocumentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractDocumentResource::collection($contractDocuments),
            'Contract Documents retrieved successfully');
    }

    /**
     * Store a newly created ContractDocument in storage.
     * POST /contractDocuments
     *
     * @param CreateContractDocumentAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractDocumentAPIRequest $request)
    {
        try
        {
            $contractId = $this->contractDocumentService->createContractDocument($request);
            return $this->sendResponse($contractId,'Successfully Created');
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE, 500);
        }
    }

    /**
     * Display the specified ContractDocument.
     * GET|HEAD /contractDocuments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractDocument $contractDocument */
        $contractDocument = $this->contractDocumentRepository->findByUuid($id, ['id']);

        if (empty($contractDocument)) {
            return $this->sendError(trans('common.contract_document_id_not_found'));
        }
        $contractDocumentData = $this->contractDocumentRepository->getEditData($contractDocument['id']);

        $contractDocumentData['time'] = $contractDocumentData['receivedDate'] ?? null;
        $contractDocumentData['returnedTime'] = $contractDocumentData['returnedDate'] ?? null;
        $response = [
            'editData' => $contractDocumentData,
            'documentReceivedFormat' => ContractManagementUtils::getDocumentReceivedFormat()
        ];

        return $this->sendResponse($response,
            trans('common.contract_document_retrieved_successfully'));
    }

    /**
     * Update the specified ContractDocument in storage.
     * PUT/PATCH /contractDocuments/{id}
     *
     * @param int $id
     * @param UpdateContractDocumentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractDocumentAPIRequest $request)
    {
        $input = $request->all();
        $amendment = $input['amendment'];

        $contractDocumentExist = $amendment
            ?
            ContractAmendmentService::getContractDocumentAmend($id)
            :
            $this->contractDocumentRepository->findByUuid($id, ['id']);


        if (empty($contractDocumentExist)) {
            return $this->sendError(trans('common.contract_document_id_not_found'));
        }

        $contractDocument = $this->contractDocumentRepository
            ->updateDocumentHeader($input, $contractDocumentExist['id']);

        if (!$contractDocument['status']) {
            return $this->sendError($contractDocument['message']);
        } else {
            return $this->sendResponse([], $contractDocument['message']);
        }
    }

    /**
     * Remove the specified ContractDocument from storage.
     * DELETE /contractDocuments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractDocument $contractDocument */
        $contractDocument = $this->contractDocumentRepository->findByUuid($id, ['id']);

        if (empty($contractDocument)) {
            return $this->sendError(trans('common.contract_document_id_not_found'));
        }

        $contractDocument->delete();

        return $this->sendSuccess(trans('common.contract_document_removed_successfully'));
    }

    public function getContractDocumentFormData(Request $request){
        $companySystemID = $request->input('selectedCompanyID') ?? false;
        $response['document_types'] = ContractManagementUtils::getDocumentTypeMasters($companySystemID);
        return $this->sendResponse($response, trans('common.contract_document_form_data_retrieved_successfully'));
    }

    public function getContractDocumentList(Request $request){
        $input = $request->all();
        return $this->contractDocumentRepository->getContractDocumentList($input);
    }
    public function updateDocumentReceived(Request $request) {
        $documentReceived = $this->contractDocumentRepository->updateDocumentReceived($request);
        if (!$documentReceived['status']) {
            $errorCode = $documentReceived['code'] ?? 404;
            return $this->sendError($documentReceived['message'], $errorCode);
        } else {
            if($request->input('file')){

                $amendment = $request->input('amendment');
                $historyId = 0;
                if($amendment)
                {
                    $histroyData = ContractManagementUtils::getContractHistoryData
                    (
                        $request->input('contractHistoryUuid')
                    );

                    $historyId = $histroyData['id'];

                    $contractDocument = ContractAmendmentService::getcontractDocumentDataAmd
                    (
                        $historyId, $request->input('uuid')
                    );
                }else
                {
                    $contractDocument = $this->contractDocumentRepository->findByUuid(
                        $request->input('uuid'),
                        ['id', 'documentName']
                    );
                }

                if(empty($contractDocument))
                {
                    return $this->sendError(trans('common.contract_document_id_not_found'));
                }

                $attachment = $this->erpDocumentAttachmentsRepository
                    ->saveDocumentAttachments($request, $contractDocument['id'], $historyId);
                if(!$attachment['status'])
                {
                    $errorCode = $attachment['code'] ?? 404;
                    return $this->sendError($attachment['message'], $errorCode);
                }
            }
            return $this->sendResponse([], $documentReceived['message']);
        }
    }
    public function updateDocumentReturn(Request $request)
    {
        $documentReturned = $this->contractDocumentRepository->updateDocumentReturned($request);
        if (!$documentReturned['status']) {
            $errorCode = $documentReturned['code'] ?? 404;
            return $this->sendError($documentReturned['message'], $errorCode);
        } else {
            return $this->sendResponse([], $documentReturned['message']);
        }
    }

    public function getContractDocumentByUuid(Request $request)
    {
        try
        {
            return $this->contractDocumentService->getContractDocumentByUuid($request);
        } catch (\Exception $e)
        {
            return $this->sendError( 'Something Went Wrong' . $e->getMessage(), 500);
        }
    }

    public function getContractDocumentPath(Request $request)
    {
        try
        {
            $data = $this->contractDocumentService->getContractDocumentPath($request);
            $responseData = ['data' => $data];
            return response()->json($responseData);
        } catch (\Exception $e)
        {
            return $this->sendError( 'Something Went Wrong' . $e->getMessage(), 500);
        }
    }

    public function deleteDocumentTracing(Request $request)
    {
        try
        {
            $this->contractDocumentService->deleteDocumentTracing($request);
            return $this->sendResponse([],'Successfully Deleted');
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE, 500);
        }
    }

    public function updateContractDocument(CreateContractDocumentAPIRequest $request)
    {
        try
        {
            $contractId = $this->contractDocumentService->updateContractDocument($request);
            return $this->sendResponse($contractId,'Successfully updated');
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE, 500);
        }
    }

}
