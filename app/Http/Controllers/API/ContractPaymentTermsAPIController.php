<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateContractPaymentTermsAPIRequest;
use App\Http\Requests\API\UpdateContractPaymentTermsAPIRequest;
use App\Models\ContractPaymentTerms;
use App\Repositories\ContractPaymentTermsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractPaymentTermsResource;
use Response;

/**
 * Class ContractPaymentTermsController
 * @package App\Http\Controllers\API
 */

class ContractPaymentTermsAPIController extends AppBaseController
{
    /** @var  ContractPaymentTermsRepository */
    private $contractPaymentTermsRepository;

    public function __construct(ContractPaymentTermsRepository $contractPaymentTermsRepo)
    {
        $this->contractPaymentTermsRepository = $contractPaymentTermsRepo;
    }

    /**
     * Display a listing of the ContractPaymentTerms.
     * GET|HEAD /contractPaymentTerms
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractPaymentTerms = $this->contractPaymentTermsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractPaymentTermsResource::
        collection($contractPaymentTerms), 'Contract Payment Terms retrieved successfully');
    }

    /**
     * Store a newly created ContractPaymentTerms in storage.
     * POST /contractPaymentTerms
     *
     * @param CreateContractPaymentTermsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractPaymentTermsAPIRequest $request)
    {
        $input = $request->all();
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        try
        {
            $this->contractPaymentTermsRepository->createPaymentTerm($input, $companySystemID);
            return $this->sendResponse([], 'Contract payment term created successfully');
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }

    /**
     * Display the specified ContractPaymentTerms.
     * GET|HEAD /contractPaymentTerms/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractPaymentTerms $contractPaymentTerms */
        $contractPaymentTerms = $this->contractPaymentTermsRepository->find($id);

        if (empty($contractPaymentTerms))
        {
            return $this->sendError('Contract Payment Terms not found');
        }

        return $this->sendResponse
        (new ContractPaymentTermsResource($contractPaymentTerms), 'Contract Payment Terms retrieved successfully');
    }

    /**
     * Update the specified ContractPaymentTerms in storage.
     * PUT/PATCH /contractPaymentTerms/{id}
     *
     * @param int $id
     * @param UpdateContractPaymentTermsAPIRequest $request
     *
     * @return Response
     */
    public function update(UpdateContractPaymentTermsAPIRequest $request)
    {
        $input = $request->all();

        try
        {
            $contractPaymentTerm = $this->contractPaymentTermsRepository->findByUuid($input['uuid'], ['id']);

            if (empty($contractPaymentTerm))
            {
                throw new CommonException(trans('common.contract_payment_term_not_found'));
            }

            $this->contractPaymentTermsRepository->updatePaymentTerms($input, $contractPaymentTerm['id']);
            return $this->sendResponse([], trans('Contract payment term updated successfully.'));

        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified ContractPaymentTerms from storage.
     * DELETE /contractPaymentTerms/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractPaymentTerms $contractPaymentTerms */
        $contractPaymentTerms = $this->contractPaymentTermsRepository->findByUuid($id, ['id']);

        if (empty($contractPaymentTerms))
        {
            return $this->sendError(trans('common.contract_payment_term_not_found'));
        }

        $contractPaymentTerms->delete();

        return $this->sendSuccess('Contract payment term deleted successfully');
    }

    public function getContractPaymentTerms($id, Request $request)
    {
        $contractPaymentTerms = $this->contractPaymentTermsRepository->getContractPaymentTerms($id, $request);
        if(!$contractPaymentTerms['status'])
        {
            return $this->sendError($contractPaymentTerms['message']);
        } else
        {
            return $this->sendResponse($contractPaymentTerms, trans('common.payment_term_retrieved_successfully'));
        }
    }

    public function updatePaymentTerm(Request $request)
    {
        $input = $request->all();

        try
        {
            $contractPaymentTerm = $this->contractPaymentTermsRepository->findByUuid($input['uuid'], ['id']);

            if (empty($contractPaymentTerm))
            {
                throw new CommonException('Contract payment term not found');
            }

            $this->contractPaymentTermsRepository->updatePaymentTerms($input, $contractPaymentTerm['id']);
            return $this->sendResponse([], trans('Contract payment term updated successfully.'));

        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
}
