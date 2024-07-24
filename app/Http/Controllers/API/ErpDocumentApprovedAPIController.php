<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateErpDocumentApprovedAPIRequest;
use App\Http\Requests\API\UpdateErpDocumentApprovedAPIRequest;
use App\Models\ErpDocumentApproved;
use App\Repositories\ErpDocumentApprovedRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpDocumentApprovedResource;
use App\Services\AttachmentService;
use Response;

/**
 * Class ErpDocumentApprovedController
 * @package App\Http\Controllers\API
 */

class ErpDocumentApprovedAPIController extends AppBaseController
{
    /** @var  ErpDocumentApprovedRepository */
    private $erpDocumentApprovedRepository;
    private $attachmentService;

    public function __construct(
        ErpDocumentApprovedRepository $erpDocumentApprovedRepo,
        AttachmentService  $attachmentService
    )
    {
        $this->erpDocumentApprovedRepository = $erpDocumentApprovedRepo;
        $this->attachmentService = $attachmentService;
    }

    /**
     * Display a listing of the ErpDocumentApproved.
     * GET|HEAD /erpDocumentApproveds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpDocumentApproveds = $this->erpDocumentApprovedRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpDocumentApprovedResource::collection($erpDocumentApproveds),
            'Erp Document Approveds retrieved successfully');
    }

    /**
     * Store a newly created ErpDocumentApproved in storage.
     * POST /erpDocumentApproveds
     *
     * @param CreateErpDocumentApprovedAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpDocumentApprovedAPIRequest $request)
    {
        $input = $request->all();

        $erpDocumentApproved = $this->erpDocumentApprovedRepository->create($input);

        return $this->sendResponse(new ErpDocumentApprovedResource($erpDocumentApproved),
            'Erp Document Approved saved successfully');
    }

    /**
     * Display the specified ErpDocumentApproved.
     * GET|HEAD /erpDocumentApproveds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpDocumentApproved $erpDocumentApproved */
        $erpDocumentApproved = $this->erpDocumentApprovedRepository->find($id);

        if (empty($erpDocumentApproved))
        {
            return $this->sendError(trans('common.document_approved_not_found'));
        }

        return $this->sendResponse(new ErpDocumentApprovedResource($erpDocumentApproved),
            'Erp Document Approved retrieved successfully');
    }

    /**
     * Update the specified ErpDocumentApproved in storage.
     * PUT/PATCH /erpDocumentApproveds/{id}
     *
     * @param int $id
     * @param UpdateErpDocumentApprovedAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpDocumentApprovedAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpDocumentApproved $erpDocumentApproved */
        $erpDocumentApproved = $this->erpDocumentApprovedRepository->find($id);

        if (empty($erpDocumentApproved))
        {
            return $this->sendError(trans('common.document_approved_not_found'));
        }

        $erpDocumentApproved = $this->erpDocumentApprovedRepository->update($input, $id);

        return $this->sendResponse(new ErpDocumentApprovedResource($erpDocumentApproved),
            'ErpDocumentApproved updated successfully');
    }

    /**
     * Remove the specified ErpDocumentApproved from storage.
     * DELETE /erpDocumentApproveds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpDocumentApproved $erpDocumentApproved */
        $erpDocumentApproved = $this->erpDocumentApprovedRepository->find($id);

        if (empty($erpDocumentApproved))
        {
            return $this->sendError(trans('common.document_approved_not_found'));
        }

        $erpDocumentApproved->delete();

        return $this->sendSuccess('Erp Document Approved deleted successfully');
    }
    public function getApprovedRecords(Request $request)
    {
        try
        {
            $input  = $request->all();
            $selectedCompanyID = $input['selectedCompanyID'];
            $documentSystemID = $input['documentSystemID'] ?? 0;
            $documentSystemUuid = $input['documentSystemCode'] ?? 0;

            $ids = $this->attachmentService->getDocumentSystemID($documentSystemUuid,
                $documentSystemID,
                $selectedCompanyID
            );
            $response = $this->erpDocumentApprovedRepository->getApprovedRecords(
                $selectedCompanyID,
                $documentSystemID,
                $ids
            );
            return $this->sendResponse($response, trans('common.approval_status_record_retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage());
        }
    }
}
