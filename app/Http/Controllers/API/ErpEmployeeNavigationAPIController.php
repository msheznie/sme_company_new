<?php

namespace App\Http\Controllers\API;

use App\Helpers\General;
use App\Http\Requests\API\CreateErpEmployeeNavigationAPIRequest;
use App\Http\Requests\API\UpdateErpEmployeeNavigationAPIRequest;
use App\Models\ErpEmployeeNavigation;
use App\Repositories\ErpEmployeeNavigationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ErpEmployeeNavigationResource;
use Illuminate\Support\Facades\Auth;
use Response;
use Exception;

/**
 * Class ErpEmployeeNavigationController
 * @package App\Http\Controllers\API
 */

class ErpEmployeeNavigationAPIController extends AppBaseController
{
    /** @var  ErpEmployeeNavigationRepository */
    private $erpEmployeeNavigationRepository;

    public function __construct(ErpEmployeeNavigationRepository $erpEmployeeNavigationRepo)
    {
        $this->erpEmployeeNavigationRepository = $erpEmployeeNavigationRepo;
    }

    /**
     * Display a listing of the ErpEmployeeNavigation.
     * GET|HEAD /erpEmployeeNavigations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $erpEmployeeNavigations = $this->erpEmployeeNavigationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ErpEmployeeNavigationResource::collection($erpEmployeeNavigations), 'Erp Employee Navigations retrieved successfully');
    }

    /**
     * Store a newly created ErpEmployeeNavigation in storage.
     * POST /erpEmployeeNavigations
     *
     * @param CreateErpEmployeeNavigationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateErpEmployeeNavigationAPIRequest $request)
    {
        $input = $request->all();

        $erpEmployeeNavigation = $this->erpEmployeeNavigationRepository->create($input);

        return $this->sendResponse(new ErpEmployeeNavigationResource($erpEmployeeNavigation), 'Erp Employee Navigation saved successfully');
    }

    /**
     * Display the specified ErpEmployeeNavigation.
     * GET|HEAD /erpEmployeeNavigations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ErpEmployeeNavigation $erpEmployeeNavigation */
        $erpEmployeeNavigation = $this->erpEmployeeNavigationRepository->find($id);

        if (empty($erpEmployeeNavigation)) {
            return $this->sendError('Erp Employee Navigation not found');
        }

        return $this->sendResponse(new ErpEmployeeNavigationResource($erpEmployeeNavigation), 'Erp Employee Navigation retrieved successfully');
    }

    /**
     * Update the specified ErpEmployeeNavigation in storage.
     * PUT/PATCH /erpEmployeeNavigations/{id}
     *
     * @param int $id
     * @param UpdateErpEmployeeNavigationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErpEmployeeNavigationAPIRequest $request)
    {
        $input = $request->all();

        /** @var ErpEmployeeNavigation $erpEmployeeNavigation */
        $erpEmployeeNavigation = $this->erpEmployeeNavigationRepository->find($id);

        if (empty($erpEmployeeNavigation)) {
            return $this->sendError('Erp Employee Navigation not found');
        }

        $erpEmployeeNavigation = $this->erpEmployeeNavigationRepository->update($input, $id);

        return $this->sendResponse(new ErpEmployeeNavigationResource($erpEmployeeNavigation), 'ErpEmployeeNavigation updated successfully');
    }

    /**
     * Remove the specified ErpEmployeeNavigation from storage.
     * DELETE /erpEmployeeNavigations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ErpEmployeeNavigation $erpEmployeeNavigation */
        $erpEmployeeNavigation = $this->erpEmployeeNavigationRepository->find($id);

        if (empty($erpEmployeeNavigation)) {
            return $this->sendError('Erp Employee Navigation not found');
        }

        $erpEmployeeNavigation->delete();

        return $this->sendSuccess('Erp Employee Navigation deleted successfully');
    }

    public function getCompanyList() {
        $user_id = General::currentEmployeeId();
        try {
            return $this->erpEmployeeNavigationRepository->getCurrentUserCompanies($user_id);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
