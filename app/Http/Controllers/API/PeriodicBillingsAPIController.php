<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreatePeriodicBillingsAPIRequest;
use App\Http\Requests\API\UpdatePeriodicBillingsAPIRequest;
use App\Models\PeriodicBillings;
use App\Repositories\PeriodicBillingsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PeriodicBillingsResource;
use Response;

/**
 * Class PeriodicBillingsController
 * @package App\Http\Controllers\API
 */

class PeriodicBillingsAPIController extends AppBaseController
{
    /** @var  PeriodicBillingsRepository */
    private $periodicBillingsRepository;

    public function __construct(PeriodicBillingsRepository $periodicBillingsRepo)
    {
        $this->periodicBillingsRepository = $periodicBillingsRepo;
    }

    /**
     * Display a listing of the PeriodicBillings.
     * GET|HEAD /periodicBillings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $periodicBillings = $this->periodicBillingsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PeriodicBillingsResource::collection($periodicBillings),
            'Periodic Billings retrieved successfully');
    }

    /**
     * Store a newly created PeriodicBillings in storage.
     * POST /periodicBillings
     *
     * @param CreatePeriodicBillingsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePeriodicBillingsAPIRequest $request)
    {
        $input = $request->all();
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        $contractUuid = $input['contractUuid'] ?? null;
        try
        {
            $this->periodicBillingsRepository->createPeriodicBilling($input, $contractUuid, $selectedCompanyID);
            return $this->sendResponse([], 'Periodic billing created successfully');
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }

    /**
     * Display the specified PeriodicBillings.
     * GET|HEAD /periodicBillings/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PeriodicBillings $periodicBillings */
        $periodicBillings = $this->periodicBillingsRepository->find($id);

        if (empty($periodicBillings))
        {
            return $this->sendError('Periodic Billings not found');
        }

        return $this->sendResponse(new PeriodicBillingsResource($periodicBillings),
            'Periodic Billings retrieved successfully');
    }

    /**
     * Update the specified PeriodicBillings in storage.
     * PUT/PATCH /periodicBillings/{id}
     *
     * @param int $id
     * @param UpdatePeriodicBillingsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePeriodicBillingsAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $periodicBilling = $this->periodicBillingsRepository->findByUuid($id, ['id']);

            if (empty($periodicBilling))
            {
                throw new CommonException('Periodic billing not found.');
            }
            $this->periodicBillingsRepository->updatePeriodicBilling($input, $periodicBilling['id']);
            return $this->sendResponse(['id' => $id], trans('Periodic billing updated successfully.'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }

    }

    /**
     * Remove the specified PeriodicBillings from storage.
     * DELETE /periodicBillings/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PeriodicBillings $periodicBillings */
        $periodicBillings = $this->periodicBillingsRepository->find($id);

        if (empty($periodicBillings))
        {
            return $this->sendError('Periodic Billings not found');
        }

        $periodicBillings->delete();

        return $this->sendSuccess('Periodic Billings deleted successfully');
    }
    public function periodicBillingFormData(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        try
        {
            $resp = $this->periodicBillingsRepository->getPaymentScheduleFormData($contractUuid, $companySystemID);
            return $this->sendResponse($resp, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
}
