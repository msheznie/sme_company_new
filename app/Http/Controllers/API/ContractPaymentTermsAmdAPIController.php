<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateContractPaymentTermsAmdAPIRequest;
use App\Http\Requests\API\UpdateContractPaymentTermsAmdAPIRequest;
use App\Models\ContractPaymentTermsAmd;
use App\Repositories\ContractPaymentTermsAmdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractPaymentTermsAmdResource;
use Response;

/**
 * Class ContractPaymentTermsAmdController
 * @package App\Http\Controllers\API
 */

class ContractPaymentTermsAmdAPIController extends AppBaseController
{
    /** @var  ContractPaymentTermsAmdRepository */
    private $contractPaymentTermsAmdRepository;
    protected $errorMessage = 'Contract Payment Terms Amd not found';

    public function __construct(ContractPaymentTermsAmdRepository $contractPaymentTermsAmdRepo)
    {
        $this->contractPaymentTermsAmdRepository = $contractPaymentTermsAmdRepo;
    }

    /**
     * Display a listing of the ContractPaymentTermsAmd.
     * GET|HEAD /contractPaymentTermsAmds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractPaymentTermsAmds = $this->contractPaymentTermsAmdRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractPaymentTermsAmdResource::collection($contractPaymentTermsAmds),
            'Contract Payment Terms Amds retrieved successfully');
    }

    /**
     * Store a newly created ContractPaymentTermsAmd in storage.
     * POST /contractPaymentTermsAmds
     *
     * @param CreateContractPaymentTermsAmdAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractPaymentTermsAmdAPIRequest $request)
    {
        $input = $request->all();

        $contractPaymentTermsAmd = $this->contractPaymentTermsAmdRepository->create($input);

        return $this->sendResponse(new ContractPaymentTermsAmdResource($contractPaymentTermsAmd),
            'Contract Payment Terms Amd saved successfully');
    }

    /**
     * Display the specified ContractPaymentTermsAmd.
     * GET|HEAD /contractPaymentTermsAmds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractPaymentTermsAmd $contractPaymentTermsAmd */
        $contractPaymentTermsAmd = $this->contractPaymentTermsAmdRepository->find($id);

        if (empty($contractPaymentTermsAmd))
        {
            return $this->sendError($this->errorMessage);
        }

        return $this->sendResponse(new ContractPaymentTermsAmdResource($contractPaymentTermsAmd),
            'Contract Payment Terms Amd retrieved successfully');
    }

    /**
     * Update the specified ContractPaymentTermsAmd in storage.
     * PUT/PATCH /contractPaymentTermsAmds/{id}
     *
     * @param int $id
     * @param UpdateContractPaymentTermsAmdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractPaymentTermsAmdAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractPaymentTermsAmd $contractPaymentTermsAmd */
        $contractPaymentTermsAmd = $this->contractPaymentTermsAmdRepository->find($id);

        if (empty($contractPaymentTermsAmd))
        {
            return $this->sendError($this->errorMessage);
        }

        $contractPaymentTermsAmd = $this->contractPaymentTermsAmdRepository->update($input, $id);

        return $this->sendResponse(new ContractPaymentTermsAmdResource($contractPaymentTermsAmd),
            'ContractPaymentTermsAmd updated successfully');
    }

    /**
     * Remove the specified ContractPaymentTermsAmd from storage.
     * DELETE /contractPaymentTermsAmds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractPaymentTermsAmd $contractPaymentTermsAmd */
        $contractPaymentTermsAmd = $this->contractPaymentTermsAmdRepository->find($id);

        if (empty($contractPaymentTermsAmd))
        {
            return $this->sendError($this->errorMessage);
        }

        $contractPaymentTermsAmd->delete();

        return $this->sendSuccess('Contract Payment Terms Amd deleted successfully');
    }
    public function getContractPaymentTermsAmd($id, Request $request)
    {
        try
        {
            $paymentTermsAmd = $this->contractPaymentTermsAmdRepository->getContractPaymentTermsAmd($id, $request);
            return $this->sendResponse($paymentTermsAmd, trans('common.payment_term_retrieved_successfully'));
        } catch (CommonException $exception)
        {
            return $this->sendError($exception->getMessage());
        }
        catch(\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }
}
