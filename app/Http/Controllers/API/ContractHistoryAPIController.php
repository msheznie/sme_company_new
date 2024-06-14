<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exceptions\ContractCreationException;
use App\Http\Requests\API\ApproveDocumentRequest;
use App\Http\Requests\API\CreateContractHistoryAPIRequest;
use App\Http\Requests\API\RejectDocumentAPIRequest;
use App\Http\Requests\API\UpdateContractHistoryAPIRequest;
use App\Http\Requests\CreateContractRequest;
use App\Repositories\ContractHistoryRepository;
use App\Services\ContractHistoryService;
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
    protected $contractHistoryService;

    const UNEXPECTED_ERROR_MESSAGE = 'An unexpected error occurred.';
    public function __construct
    (
    ContractHistoryRepository $contractHistoryRepo,
    ContractHistoryService $contractHistoryService
    )

    {
        $this->contractHistoryRepository = $contractHistoryRepo;
        $this->contractHistoryService = $contractHistoryService;
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
            return $this->sendError('Contract History not found to update');
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
            return $this->sendError('Contract History not found to delete');
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
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' .$e->getMessage() , 500);
        }
    }

    public function getCategoryWiseContractData(Request $request)
    {
        $input = $request->all();
        try
        {
            $data = $this->contractHistoryService->getCategoryWiseContractData($input);
            $responseData = ['data' => $data];
            return response()->json($responseData);
        } catch (\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteContractHistory(Request $request)
    {
        try
        {
            $this->contractHistoryService->deleteContractHistory($request->all());
            return $this->sendSuccess('Successfully deleted');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function getContractHistory(Request $request)
    {
        try
        {
            return $this->contractHistoryRepository->getContractHistory($request);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function updateContractStatus(Request $request)
    {
        try
        {
            $this->contractHistoryService->updateContractStatus($request->all());
            return $this->sendSuccess('Successfully Status Updated');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);

        }
    }

    public function getContractApprovals(Request $request)
    {
        try
        {
            return $this->contractHistoryService->getContractApprovals($request);

        } catch (\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

    public function approveContract(ApproveDocumentRequest $request)
    {
        try
        {
            $this->contractHistoryService->approveContract($request);
            return $this->sendResponse([], trans('common.document_approved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function rejectContract(RejectDocumentAPIRequest $request)
    {
        try
        {
            $this->contractHistoryService->rejectContract($request);
            return $this->sendResponse([], trans('common.document_successfully_rejected'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function updateExtendStatus(Request $request)
    {
        try
        {
            $this->contractHistoryService->updateExtendStatus($request->all());
            return $this->sendSuccess('Successfully Status Updated');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

}
