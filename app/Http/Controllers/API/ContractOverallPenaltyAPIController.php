<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateContractOverallPenaltyAPIRequest;
use App\Http\Requests\API\UpdateContractOverallPenaltyAPIRequest;
use App\Models\ContractOverallPenalty;
use App\Repositories\ContractOverallPenaltyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractOverallPenaltyResource;
use Response;

/**
 * Class ContractOverallPenaltyController
 * @package App\Http\Controllers\API
 */

class ContractOverallPenaltyAPIController extends AppBaseController
{
    /** @var  ContractOverallPenaltyRepository */
    private $contractOverallPenaltyRepository;

    public function __construct(ContractOverallPenaltyRepository $contractOverallPenaltyRepo)
    {
        $this->contractOverallPenaltyRepository = $contractOverallPenaltyRepo;
    }

    /**
     * Display a listing of the ContractOverallPenalty.
     * GET|HEAD /contractOverallPenalties
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractOverallPenalties = $this->contractOverallPenaltyRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractOverallPenaltyResource::collection($contractOverallPenalties), 'Contract Overall Penalties retrieved successfully');
    }

    /**
     * Store a newly created ContractOverallPenalty in storage.
     * POST /contractOverallPenalties
     *
     * @param CreateContractOverallPenaltyAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractOverallPenaltyAPIRequest $request)
    {
        $input = $request->all();
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        $contractUuid = $input['contractUuid'] ?? null;
        try
        {
            $this->contractOverallPenaltyRepository->createOverallPenalty($input, $contractUuid, $selectedCompanyID);
            return $this->sendResponse([], 'Overall penalty created successfully');
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }

    /**
     * Display the specified ContractOverallPenalty.
     * GET|HEAD /contractOverallPenalties/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractOverallPenalty $contractOverallPenalty */
        $contractOverallPenalty = $this->contractOverallPenaltyRepository->find($id);

        if (empty($contractOverallPenalty)) {
            return $this->sendError('Contract Overall Penalty not found');
        }

        return $this->sendResponse(new ContractOverallPenaltyResource($contractOverallPenalty), 'Contract Overall Penalty retrieved successfully');
    }

    /**
     * Update the specified ContractOverallPenalty in storage.
     * PUT/PATCH /contractOverallPenalties/{id}
     *
     * @param int $id
     * @param UpdateContractOverallPenaltyAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractOverallPenaltyAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $overallPenalty = $this->contractOverallPenaltyRepository->findByUuid($id);

            if (empty($overallPenalty))
            {
                throw new CommonException('Overall penalty not found.');
            }
            $this->contractOverallPenaltyRepository->updateOverallPenalty($input, $overallPenalty['id']);
            return $this->sendResponse(['id' => $id], trans('Overall penalty updated successfully.'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified ContractOverallPenalty from storage.
     * DELETE /contractOverallPenalties/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractOverallPenalty $contractOverallPenalty */
        $contractOverallPenalty = $this->contractOverallPenaltyRepository->find($id);

        if (empty($contractOverallPenalty)) {
            return $this->sendError('Contract Overall Penalty not found');
        }

        $contractOverallPenalty->delete();

        return $this->sendSuccess('Contract Overall Penalty deleted successfully');
    }

    public function overallPenaltyData(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        try
        {
            $response = $this->contractOverallPenaltyRepository->getOverallPenaltyData($contractUuid, $companySystemID);
            return $this->sendResponse($response, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function updatePenaltyStatus(Request $request)
    {
        $input = $request->all();
        $uuid = $request->input('uuid');
        try
        {
            $overallPenalty = $this->contractOverallPenaltyRepository->findByUuid($uuid);

            if (empty($overallPenalty))
            {
                throw new CommonException('Overall penalty not found.');
            }
            $this->contractOverallPenaltyRepository->updatePenaltyStatus($input, $overallPenalty['id']);
            return $this->sendResponse(['id' => $uuid], trans('Penalty status updated successfully.'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
}
