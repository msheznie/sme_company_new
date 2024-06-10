<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ContractCreationException;
use App\Http\Requests\API\CreateContractHistoryAPIRequest;
use App\Http\Requests\API\UpdateContractHistoryAPIRequest;
use App\Http\Requests\CreateContractRequest;
use App\Repositories\ContractHistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractHistoryResource;
use Response;

/**
 * Class ContractHistoryController
 * @package App\Http\Controllers\API
 */

class ContractHistoryAPIController extends AppBaseController
{
    /** @var  ContractHistoryRepository */
    private $contractHistoryRepository;

    public function __construct(ContractHistoryRepository $contractHistoryRepo)
    {
        $this->contractHistoryRepository = $contractHistoryRepo;
    }

    /**
     * Display a listing of the ContractHistory.
     * GET|HEAD /contractHistories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractHistories = $this->contractHistoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse
        (
            ContractHistoryResource::collection($contractHistories), 'Contract Histories retrieved successfully'
        );
    }

    /**
     * Store a newly created ContractHistory in storage.
     * POST /contractHistories
     *
     * @param CreateContractHistoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractHistoryAPIRequest $request)
    {
        $input = $request->all();

        $contractHistory = $this->contractHistoryRepository->create($input);

        return $this->sendResponse
        (
            new ContractHistoryResource($contractHistory), 'Contract History saved successfully'
        );
    }

    /**
     * Display the specified ContractHistory.
     * GET|HEAD /contractHistories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractHistory $contractHistory */
        $contractHistory = $this->contractHistoryRepository->find($id);

        if (empty($contractHistory))
        {
            return $this->sendError('Contract History not found');
        }

        return $this->sendResponse
        (
            new ContractHistoryResource($contractHistory), 'Contract History retrieved successfully'
        );
    }

    /**
     * Update the specified ContractHistory in storage.
     * PUT/PATCH /contractHistories/{id}
     *
     * @param int $id
     * @param UpdateContractHistoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractHistory $contractHistory */
        $contractHistory = $this->contractHistoryRepository->find($id);

        if (empty($contractHistory))
        {
            return $this->sendError('Contract History not found');
        }

        $contractHistory = $this->contractHistoryRepository->update($input, $id);

        return $this->sendResponse
        (
            new ContractHistoryResource($contractHistory), 'ContractHistory updated successfully'
        );
    }

    /**
     * Remove the specified ContractHistory from storage.
     * DELETE /contractHistories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractHistory $contractHistory */
        $contractHistory = $this->contractHistoryRepository->find($id);

        if (empty($contractHistory))
        {
            return $this->sendError('Contract History not found');
        }

        $contractHistory->delete();

        return $this->sendSuccess('Contract History deleted successfully');
    }

    public function createContractHistory(CreateContractRequest $request)
    {
        $request->validated();
        try
        {
            $contractUuid =  $this->contractHistoryRepository->createContractHistory($request);
            return $this->sendResponse($contractUuid,'Successfully Created');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError('An unexpected error occurred. '.$e->getMessage() , 500);
        }
    }

    public function getAllAddendumData(Request $request)
    {
        $input = $request->all();
        try
        {
            $data = $this->contractHistoryRepository->getAllAddendumData($input);
            $responseData = ['data' => $data];
            return response()->json($responseData);
        } catch (\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

}
