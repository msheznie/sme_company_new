<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpDocumentReferedHistoryAPIRequest;
use App\Http\Requests\API\UpdateErpDocumentReferedHistoryAPIRequest;
use App\Models\ErpDocumentReferedHistory;
use App\Repositories\ErpDocumentReferedHistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpDocumentReferedHistoryResource;
use Response;

/**
 * Class ErpDocumentReferedHistoryController
 * @package App\Http\Controllers\API
 */

class ErpDocumentReferedHistoryAPIController extends AppBaseController
{
    /** @var  ErpDocumentReferedHistoryRepository */
    private $erpDocumentReferedHistoryRepository;

    public function __construct(ErpDocumentReferedHistoryRepository $erpDocumentReferedHistoryRepo)
    {
        $this->erpDocumentReferedHistoryRepository = $erpDocumentReferedHistoryRepo;
    }

    /**
     * Display a listing of the ErpDocumentReferedHistory.
     * GET|HEAD /erpDocumentReferedHistories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpDocumentReferedHistories = $this->erpDocumentReferedHistoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpDocumentReferedHistoryResource::collection($erpDocumentReferedHistories), 'Erp Document Refered Histories retrieved successfully');
    }

    /**
     * Store a newly created ErpDocumentReferedHistory in storage.
     * POST /erpDocumentReferedHistories
     *
     * @param CreateErpDocumentReferedHistoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpDocumentReferedHistoryAPIRequest $request)
    {
        $input = $request->all();

        $erpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepository->create($input);

        return $this->sendResponse(new ErpDocumentReferedHistoryResource($erpDocumentReferedHistory), 'Erp Document Refered History saved successfully');
    }

    /**
     * Display the specified ErpDocumentReferedHistory.
     * GET|HEAD /erpDocumentReferedHistories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpDocumentReferedHistory $erpDocumentReferedHistory */
        $erpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepository->find($id);

        if (empty($erpDocumentReferedHistory)) {
            return $this->sendError('Erp Document Refered History not found');
        }

        return $this->sendResponse(new ErpDocumentReferedHistoryResource($erpDocumentReferedHistory), 'Erp Document Refered History retrieved successfully');
    }

    /**
     * Update the specified ErpDocumentReferedHistory in storage.
     * PUT/PATCH /erpDocumentReferedHistories/{id}
     *
     * @param int $id
     * @param UpdateErpDocumentReferedHistoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpDocumentReferedHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpDocumentReferedHistory $erpDocumentReferedHistory */
        $erpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepository->find($id);

        if (empty($erpDocumentReferedHistory)) {
            return $this->sendError('Erp Document Refered History not found');
        }

        $erpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepository->update($input, $id);

        return $this->sendResponse(new ErpDocumentReferedHistoryResource($erpDocumentReferedHistory), 'ErpDocumentReferedHistory updated successfully');
    }

    /**
     * Remove the specified ErpDocumentReferedHistory from storage.
     * DELETE /erpDocumentReferedHistories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpDocumentReferedHistory $erpDocumentReferedHistory */
        $erpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepository->find($id);

        if (empty($erpDocumentReferedHistory)) {
            return $this->sendError('Erp Document Refered History not found');
        }

        $erpDocumentReferedHistory->delete();

        return $this->sendSuccess('Erp Document Refered History deleted successfully');
    }
}
