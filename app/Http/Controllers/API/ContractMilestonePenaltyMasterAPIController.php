<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateContractMilestonePenaltyMasterAPIRequest;
use App\Http\Requests\API\UpdateContractMilestonePenaltyMasterAPIRequest;
use App\Models\ContractMilestonePenaltyMaster;
use App\Repositories\ContractMilestonePenaltyMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMilestonePenaltyMasterResource;
use Response;

/**
 * Class ContractMilestonePenaltyMasterController
 * @package App\Http\Controllers\API
 */

class ContractMilestonePenaltyMasterAPIController extends AppBaseController
{
    /** @var  ContractMilestonePenaltyMasterRepository */
    private $contractMilestonePenaltyMasterRepository;

    public function __construct(ContractMilestonePenaltyMasterRepository $contractMilestonePenaltyMasterRepo)
    {
        $this->contractMilestonePenaltyMasterRepository = $contractMilestonePenaltyMasterRepo;
    }

    /**
     * Display a listing of the ContractMilestonePenaltyMaster.
     * GET|HEAD /contractMilestonePenaltyMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMilestonePenaltyMasters = $this->contractMilestonePenaltyMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMilestonePenaltyMasterResource::collection($contractMilestonePenaltyMasters), 'Contract Milestone Penalty Masters retrieved successfully');
    }

    /**
     * Store a newly created ContractMilestonePenaltyMaster in storage.
     * POST /contractMilestonePenaltyMasters
     *
     * @param CreateContractMilestonePenaltyMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractMilestonePenaltyMasterAPIRequest $request)
    {
        $input = $request->all();
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        $contractUuid = $input['contractUuid'] ?? null;
        try
        {
            $this->contractMilestonePenaltyMasterRepository->createMilestonePenalty(
                $input, $contractUuid, $selectedCompanyID);
            return $this->sendResponse([], 'Milestone penalty master created successfully');
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }

    /**
     * Display the specified ContractMilestonePenaltyMaster.
     * GET|HEAD /contractMilestonePenaltyMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractMilestonePenaltyMaster $contractMilestonePenaltyMaster */
        $contractMilestonePenaltyMaster = $this->contractMilestonePenaltyMasterRepository->find($id);

        if (empty($contractMilestonePenaltyMaster)) {
            return $this->sendError('Contract Milestone Penalty Master not found');
        }

        return $this->sendResponse(new ContractMilestonePenaltyMasterResource($contractMilestonePenaltyMaster), 'Contract Milestone Penalty Master retrieved successfully');
    }

    /**
     * Update the specified ContractMilestonePenaltyMaster in storage.
     * PUT/PATCH /contractMilestonePenaltyMasters/{id}
     *
     * @param int $id
     * @param UpdateContractMilestonePenaltyMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMilestonePenaltyMasterAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $milestonePenalty = $this->contractMilestonePenaltyMasterRepository->findByUuid($id, ['id']);

            if (empty($milestonePenalty))
            {
                throw new CommonException('Milestone penalty not found.');
            }
            $this->contractMilestonePenaltyMasterRepository->updateMilestonePenalty($input, $milestonePenalty['id']);
            return $this->sendResponse(['id' => $id], trans('Milestone penalty updated successfully.'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified ContractMilestonePenaltyMaster from storage.
     * DELETE /contractMilestonePenaltyMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractMilestonePenaltyMaster $contractMilestonePenaltyMaster */
        $contractMilestonePenaltyMaster = $this->contractMilestonePenaltyMasterRepository->find($id);

        if (empty($contractMilestonePenaltyMaster)) {
            return $this->sendError('Contract Milestone Penalty Master not found');
        }

        $contractMilestonePenaltyMaster->delete();

        return $this->sendSuccess('Contract Milestone Penalty Master deleted successfully');
    }

    public function milestonePenaltyMasterData(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        try
        {
            $response = $this->contractMilestonePenaltyMasterRepository->getMilestonePenaltyMasterData(
                $contractUuid, $companySystemID);
            return $this->sendResponse($response, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
}
