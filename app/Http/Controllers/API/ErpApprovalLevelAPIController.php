<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpApprovalLevelAPIRequest;
use App\Http\Requests\API\UpdateErpApprovalLevelAPIRequest;
use App\Models\ErpApprovalLevel;
use App\Repositories\ErpApprovalLevelRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpApprovalLevelResource;
use Response;

/**
 * Class ErpApprovalLevelController
 * @package App\Http\Controllers\API
 */

class ErpApprovalLevelAPIController extends AppBaseController
{
    /** @var  ErpApprovalLevelRepository */
    private $erpApprovalLevelRepository;

    public function __construct(ErpApprovalLevelRepository $erpApprovalLevelRepo)
    {
        $this->erpApprovalLevelRepository = $erpApprovalLevelRepo;
    }

    /**
     * Display a listing of the ErpApprovalLevel.
     * GET|HEAD /erpApprovalLevels
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpApprovalLevels = $this->erpApprovalLevelRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpApprovalLevelResource::collection($erpApprovalLevels), 'Erp Approval Levels retrieved successfully');
    }

    /**
     * Store a newly created ErpApprovalLevel in storage.
     * POST /erpApprovalLevels
     *
     * @param CreateErpApprovalLevelAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpApprovalLevelAPIRequest $request)
    {
        $input = $request->all();

        $erpApprovalLevel = $this->erpApprovalLevelRepository->create($input);

        return $this->sendResponse(new ErpApprovalLevelResource($erpApprovalLevel), 'Erp Approval Level saved successfully');
    }

    /**
     * Display the specified ErpApprovalLevel.
     * GET|HEAD /erpApprovalLevels/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpApprovalLevel $erpApprovalLevel */
        $erpApprovalLevel = $this->erpApprovalLevelRepository->find($id);

        if (empty($erpApprovalLevel)) {
            return $this->sendError('Erp Approval Level not found');
        }

        return $this->sendResponse(new ErpApprovalLevelResource($erpApprovalLevel), 'Erp Approval Level retrieved successfully');
    }

    /**
     * Update the specified ErpApprovalLevel in storage.
     * PUT/PATCH /erpApprovalLevels/{id}
     *
     * @param int $id
     * @param UpdateErpApprovalLevelAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpApprovalLevelAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpApprovalLevel $erpApprovalLevel */
        $erpApprovalLevel = $this->erpApprovalLevelRepository->find($id);

        if (empty($erpApprovalLevel)) {
            return $this->sendError('Erp Approval Level not found');
        }

        $erpApprovalLevel = $this->erpApprovalLevelRepository->update($input, $id);

        return $this->sendResponse(new ErpApprovalLevelResource($erpApprovalLevel), 'ErpApprovalLevel updated successfully');
    }

    /**
     * Remove the specified ErpApprovalLevel from storage.
     * DELETE /erpApprovalLevels/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpApprovalLevel $erpApprovalLevel */
        $erpApprovalLevel = $this->erpApprovalLevelRepository->find($id);

        if (empty($erpApprovalLevel)) {
            return $this->sendError('Erp Approval Level not found');
        }

        $erpApprovalLevel->delete();

        return $this->sendSuccess('Erp Approval Level deleted successfully');
    }
}
