<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContractSettingMasterAPIRequest;
use App\Http\Requests\API\UpdateContractSettingMasterAPIRequest;
use App\Models\ContractSettingMaster;
use App\Repositories\ContractSettingMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractSettingMasterResource;
use Response;

/**
 * Class ContractSettingMasterController
 * @package App\Http\Controllers\API
 */

class ContractSettingMasterAPIController extends AppBaseController
{
    /** @var  ContractSettingMasterRepository */
    private $contractSettingMasterRepository;

    public function __construct(ContractSettingMasterRepository $contractSettingMasterRepo)
    {
        $this->contractSettingMasterRepository = $contractSettingMasterRepo;
    }

    /**
     * Display a listing of the ContractSettingMaster.
     * GET|HEAD /contractSettingMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractSettingMasters = $this->contractSettingMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractSettingMasterResource::collection($contractSettingMasters), 'Contract Setting Masters retrieved successfully');
    }

    /**
     * Store a newly created ContractSettingMaster in storage.
     * POST /contractSettingMasters
     *
     * @param CreateContractSettingMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractSettingMasterAPIRequest $request)
    {
        $input = $request->all();

        $contractSettingMaster = $this->contractSettingMasterRepository->create($input);

        return $this->sendResponse(new ContractSettingMasterResource($contractSettingMaster), 'Contract Setting Master saved successfully');
    }

    /**
     * Display the specified ContractSettingMaster.
     * GET|HEAD /contractSettingMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractSettingMaster $contractSettingMaster */
        $contractSettingMaster = $this->contractSettingMasterRepository->find($id);

        if (empty($contractSettingMaster)) {
            return $this->sendError('Contract Setting Master not found');
        }

        return $this->sendResponse(new ContractSettingMasterResource($contractSettingMaster), 'Contract Setting Master retrieved successfully');
    }

    /**
     * Update the specified ContractSettingMaster in storage.
     * PUT/PATCH /contractSettingMasters/{id}
     *
     * @param int $id
     * @param UpdateContractSettingMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractSettingMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractSettingMaster $contractSettingMaster */
        $contractSettingMaster = $this->contractSettingMasterRepository->find($id);

        if (empty($contractSettingMaster)) {
            return $this->sendError('Contract Setting Master not found');
        }

        $contractSettingMaster = $this->contractSettingMasterRepository->update($input, $id);

        return $this->sendResponse(new ContractSettingMasterResource($contractSettingMaster), 'ContractSettingMaster updated successfully');
    }

    /**
     * Remove the specified ContractSettingMaster from storage.
     * DELETE /contractSettingMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractSettingMaster $contractSettingMaster */
        $contractSettingMaster = $this->contractSettingMasterRepository->find($id);

        if (empty($contractSettingMaster)) {
            return $this->sendError('Contract Setting Master not found');
        }

        $contractSettingMaster->delete();

        return $this->sendSuccess('Contract Setting Master deleted successfully');
    }
}
