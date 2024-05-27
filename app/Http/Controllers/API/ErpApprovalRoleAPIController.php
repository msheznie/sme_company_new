<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateErpApprovalRoleAPIRequest;
use App\Http\Requests\API\UpdateErpApprovalRoleAPIRequest;
use App\Models\ErpApprovalRole;
use App\Repositories\ErpApprovalRoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpApprovalRoleResource;
use Response;

/**
 * Class ErpApprovalRoleController
 * @package App\Http\Controllers\API
 */

class ErpApprovalRoleAPIController extends AppBaseController
{
    /** @var  ErpApprovalRoleRepository */
    private $erpApprovalRoleRepository;

    public function __construct(ErpApprovalRoleRepository $erpApprovalRoleRepo)
    {
        $this->erpApprovalRoleRepository = $erpApprovalRoleRepo;
    }

    /**
     * Display a listing of the ErpApprovalRole.
     * GET|HEAD /erpApprovalRoles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpApprovalRoles = $this->erpApprovalRoleRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpApprovalRoleResource::collection($erpApprovalRoles), 'Erp Approval Roles retrieved successfully');
    }

    /**
     * Store a newly created ErpApprovalRole in storage.
     * POST /erpApprovalRoles
     *
     * @param CreateErpApprovalRoleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpApprovalRoleAPIRequest $request)
    {
        $input = $request->all();

        $erpApprovalRole = $this->erpApprovalRoleRepository->create($input);

        return $this->sendResponse(new ErpApprovalRoleResource($erpApprovalRole), 'Erp Approval Role saved successfully');
    }

    /**
     * Display the specified ErpApprovalRole.
     * GET|HEAD /erpApprovalRoles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpApprovalRole $erpApprovalRole */
        $erpApprovalRole = $this->erpApprovalRoleRepository->find($id);

        if (empty($erpApprovalRole)) {
            return $this->sendError('Erp Approval Role not found');
        }

        return $this->sendResponse(new ErpApprovalRoleResource($erpApprovalRole), 'Erp Approval Role retrieved successfully');
    }

    /**
     * Update the specified ErpApprovalRole in storage.
     * PUT/PATCH /erpApprovalRoles/{id}
     *
     * @param int $id
     * @param UpdateErpApprovalRoleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpApprovalRoleAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpApprovalRole $erpApprovalRole */
        $erpApprovalRole = $this->erpApprovalRoleRepository->find($id);

        if (empty($erpApprovalRole)) {
            return $this->sendError('Erp Approval Role not found');
        }

        $erpApprovalRole = $this->erpApprovalRoleRepository->update($input, $id);

        return $this->sendResponse(new ErpApprovalRoleResource($erpApprovalRole), 'ErpApprovalRole updated successfully');
    }

    /**
     * Remove the specified ErpApprovalRole from storage.
     * DELETE /erpApprovalRoles/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpApprovalRole $erpApprovalRole */
        $erpApprovalRole = $this->erpApprovalRoleRepository->find($id);

        if (empty($erpApprovalRole)) {
            return $this->sendError('Erp Approval Role not found');
        }

        $erpApprovalRole->delete();

        return $this->sendSuccess('Erp Approval Role deleted successfully');
    }
}
