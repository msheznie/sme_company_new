<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractSettingDetailAPIRequest;
use App\Http\Requests\API\UpdateContractSettingDetailAPIRequest;
use App\Models\ContractSettingDetail;
use App\Repositories\ContractSettingDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractSettingDetailResource;
use Response;

/**
 * Class ContractSettingDetailController
 * @package App\Http\Controllers\API
 */

class ContractSettingDetailAPIController extends AppBaseController
{
    /** @var  ContractSettingDetailRepository */
    private $contractSettingDetailRepository;

    public function __construct(ContractSettingDetailRepository $contractSettingDetailRepo)
    {
        $this->contractSettingDetailRepository = $contractSettingDetailRepo;
    }

    /**
     * Display a listing of the ContractSettingDetail.
     * GET|HEAD /contractSettingDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractSettingDetails = $this->contractSettingDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractSettingDetailResource::collection($contractSettingDetails), 'Contract Setting Details retrieved successfully');
    }

    /**
     * Store a newly created ContractSettingDetail in storage.
     * POST /contractSettingDetails
     *
     * @param CreateContractSettingDetailAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractSettingDetailAPIRequest $request)
    {
        $input = $request->all();

        $contractSettingDetail = $this->contractSettingDetailRepository->create($input);

        return $this->sendResponse(new ContractSettingDetailResource($contractSettingDetail), 'Contract Setting Detail saved successfully');
    }

    /**
     * Display the specified ContractSettingDetail.
     * GET|HEAD /contractSettingDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractSettingDetail $contractSettingDetail */
        $contractSettingDetail = $this->contractSettingDetailRepository->find($id);

        if (empty($contractSettingDetail)) {
            return $this->sendError('Contract Setting Detail not found');
        }

        return $this->sendResponse(new ContractSettingDetailResource($contractSettingDetail), 'Contract Setting Detail retrieved successfully');
    }

    /**
     * Update the specified ContractSettingDetail in storage.
     * PUT/PATCH /contractSettingDetails/{id}
     *
     * @param int $id
     * @param UpdateContractSettingDetailAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractSettingDetailAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractSettingDetail $contractSettingDetail */
        $contractSettingDetail = $this->contractSettingDetailRepository->find($id);

        if (empty($contractSettingDetail)) {
            return $this->sendError('Contract Setting Detail not found');
        }

        $contractSettingDetail = $this->contractSettingDetailRepository->update($input, $id);

        return $this->sendResponse(new ContractSettingDetailResource($contractSettingDetail), 'ContractSettingDetail updated successfully');
    }

    /**
     * Remove the specified ContractSettingDetail from storage.
     * DELETE /contractSettingDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractSettingDetail $contractSettingDetail */
        $contractSettingDetail = $this->contractSettingDetailRepository->find($id);

        if (empty($contractSettingDetail)) {
            return $this->sendError('Contract Setting Detail not found');
        }

        $contractSettingDetail->delete();

        return $this->sendSuccess('Contract Setting Detail deleted successfully');
    }
}
