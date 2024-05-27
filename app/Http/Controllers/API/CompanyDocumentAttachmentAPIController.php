<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCompanyDocumentAttachmentAPIRequest;
use App\Http\Requests\API\UpdateCompanyDocumentAttachmentAPIRequest;
use App\Models\CompanyDocumentAttachment;
use App\Repositories\CompanyDocumentAttachmentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CompanyDocumentAttachmentResource;
use Response;

/**
 * Class CompanyDocumentAttachmentController
 * @package App\Http\Controllers\API
 */

class CompanyDocumentAttachmentAPIController extends AppBaseController
{
    /** @var  CompanyDocumentAttachmentRepository */
    private $companyDocumentAttachmentRepository;

    public function __construct(CompanyDocumentAttachmentRepository $companyDocumentAttachmentRepo)
    {
        $this->companyDocumentAttachmentRepository = $companyDocumentAttachmentRepo;
    }

    /**
     * Display a listing of the CompanyDocumentAttachment.
     * GET|HEAD /companyDocumentAttachments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $companyDocumentAttachments = $this->companyDocumentAttachmentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CompanyDocumentAttachmentResource::collection($companyDocumentAttachments), 'Company Document Attachments retrieved successfully');
    }

    /**
     * Store a newly created CompanyDocumentAttachment in storage.
     * POST /companyDocumentAttachments
     *
     * @param CreateCompanyDocumentAttachmentAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCompanyDocumentAttachmentAPIRequest $request)
    {
        $input = $request->all();

        $companyDocumentAttachment = $this->companyDocumentAttachmentRepository->create($input);

        return $this->sendResponse(new CompanyDocumentAttachmentResource($companyDocumentAttachment), 'Company Document Attachment saved successfully');
    }

    /**
     * Display the specified CompanyDocumentAttachment.
     * GET|HEAD /companyDocumentAttachments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CompanyDocumentAttachment $companyDocumentAttachment */
        $companyDocumentAttachment = $this->companyDocumentAttachmentRepository->find($id);

        if (empty($companyDocumentAttachment)) {
            return $this->sendError('Company Document Attachment not found');
        }

        return $this->sendResponse(new CompanyDocumentAttachmentResource($companyDocumentAttachment), 'Company Document Attachment retrieved successfully');
    }

    /**
     * Update the specified CompanyDocumentAttachment in storage.
     * PUT/PATCH /companyDocumentAttachments/{id}
     *
     * @param int $id
     * @param UpdateCompanyDocumentAttachmentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompanyDocumentAttachmentAPIRequest $request)
    {
        $input = $request->all();

        /** @var CompanyDocumentAttachment $companyDocumentAttachment */
        $companyDocumentAttachment = $this->companyDocumentAttachmentRepository->find($id);

        if (empty($companyDocumentAttachment)) {
            return $this->sendError('Company Document Attachment not found');
        }

        $companyDocumentAttachment = $this->companyDocumentAttachmentRepository->update($input, $id);

        return $this->sendResponse(new CompanyDocumentAttachmentResource($companyDocumentAttachment), 'CompanyDocumentAttachment updated successfully');
    }

    /**
     * Remove the specified CompanyDocumentAttachment from storage.
     * DELETE /companyDocumentAttachments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CompanyDocumentAttachment $companyDocumentAttachment */
        $companyDocumentAttachment = $this->companyDocumentAttachmentRepository->find($id);

        if (empty($companyDocumentAttachment)) {
            return $this->sendError('Company Document Attachment not found');
        }

        $companyDocumentAttachment->delete();

        return $this->sendSuccess('Company Document Attachment deleted successfully');
    }
}
