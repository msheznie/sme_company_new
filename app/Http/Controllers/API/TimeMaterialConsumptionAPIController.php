<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Http\Requests\API\CreateTimeMaterialConsumptionAPIRequest;
use App\Http\Requests\API\UpdateTimeMaterialConsumptionAPIRequest;
use App\Models\TimeMaterialConsumption;
use App\Models\TimeMaterialConsumptionAmd;
use App\Repositories\TimeMaterialConsumptionRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TimeMaterialConsumptionResource;
use Response;
use App\Services\GeneralService;

/**
 * Class TimeMaterialConsumptionController
 * @package App\Http\Controllers\API
 */

class TimeMaterialConsumptionAPIController extends AppBaseController
{
    /** @var  TimeMaterialConsumptionRepository */
    private $timeMaterialConsumptionRepository;

    public function __construct(TimeMaterialConsumptionRepository $timeMaterialConsumptionRepo)
    {
        $this->timeMaterialConsumptionRepository = $timeMaterialConsumptionRepo;
    }

    /**
     * Display a listing of the TimeMaterialConsumption.
     * GET|HEAD /timeMaterialConsumptions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $timeMaterialConsumptions = $this->timeMaterialConsumptionRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(TimeMaterialConsumptionResource::collection($timeMaterialConsumptions),
            'Time Material Consumptions retrieved successfully');
    }

    /**
     * Store a newly created TimeMaterialConsumption in storage.
     * POST /timeMaterialConsumptions
     *
     * @param CreateTimeMaterialConsumptionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTimeMaterialConsumptionAPIRequest $request)
    {
        $input = $request->all();
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        $contractUuid = $input['contract_id'] ?? null;
        $historyUuid = $input['historyUuid'] ?? null;
        $amendment = $input['amendment'] ?? false;
        try
        {
            $this->timeMaterialConsumptionRepository->createTimeMaterialConsumption(
                $contractUuid,
                $selectedCompanyID,
                $historyUuid,
                $amendment
            );
            return $this->sendResponse([], trans('common.time_and_material_consumption_created_successfully_dot'));
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }

    /**
     * Display the specified TimeMaterialConsumption.
     * GET|HEAD /timeMaterialConsumptions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TimeMaterialConsumption $timeMaterialConsumption */
        $timeMaterialConsumption = $this->timeMaterialConsumptionRepository->find($id);

        if (empty($timeMaterialConsumption))
        {
            return $this->sendError(trans('common.time_material_consumption_not_found'));
        }

        return $this->sendResponse(new TimeMaterialConsumptionResource($timeMaterialConsumption),
            trans('common.time_material_consumption_retrieved_successfully'));
    }

    /**
     * Update the specified TimeMaterialConsumption in storage.
     * PUT/PATCH /timeMaterialConsumptions/{id}
     *
     * @param int $id
     * @param UpdateTimeMaterialConsumptionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimeMaterialConsumptionAPIRequest $request)
    {
        $input = $request->all();
        $amendment = $input['amendment'] ?? false;
        $historyUuid = $input['historyID'] ?? false;
        try
        {
            $historyID = $amendment
                ? (ContractManagementUtils::getContractHistoryData($historyUuid)['id']
                    ?? GeneralService::sendException(trans('common.contract_history_not_found')))
                : 0;

            $consumption = $amendment ? TimeMaterialConsumptionAmd::findTimeMaterialConsumption($id, $historyID)
                :$this->timeMaterialConsumptionRepository->findByUuid($id, ['id']);

            if (empty($consumption))
            {
                GeneralService::sendException(trans('common.time_material_consumption_not_found'));
            }
            $updateId = $amendment ? $consumption['amd_id'] : $consumption['id'];
            $this->timeMaterialConsumptionRepository->updateTimeMaterialConsumption($input, $updateId);
            return $this->sendResponse([], trans('common.time_and_material_consumption_saved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified TimeMaterialConsumption from storage.
     * DELETE /timeMaterialConsumptions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        /** @var TimeMaterialConsumption $timeMaterialConsumption */
        $amendment = $request->input('amendment') ?? false;
        $historyUuid = $request->input('contractHistoryUuid') ?? false;
        try
        {
            $historyID = $amendment
                ? (ContractManagementUtils::getContractHistoryData($historyUuid)['id']
                    ?? GeneralService::sendException(trans('common.contract_history_not_found')))
                : 0;

            $consumption = $amendment ? TimeMaterialConsumptionAmd::findTimeMaterialConsumption($id, $historyID)
                : $this->timeMaterialConsumptionRepository->findByUuid($id, ['id', 'deleted_by']);

            if (empty($consumption))
            {
                GeneralService::sendException(trans('common.time_material_consumption_not_found'));
            }
            $consumption->deleted_by = General::currentEmployeeId();
            $consumption->save();
            $consumption->delete();

            return $this->sendSuccess(trans('common.time_material_consumption_deleted_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError(trans('common.failed_to_delete_time_and_material_consumption') . $ex->getMessage());
        } catch(\Exception $ex)
        {
            return $this->sendError(trans('common.failed_to_delete_time_and_material_consumption') . $ex->getMessage());
        }

    }
    public function getTimeConsumptionFormData(Request $request)
    {
        $selectedCompanyID = $request->input('selectedCompanyID') ??0;
        $response = $this->timeMaterialConsumptionRepository->getTimeConsumptionFormData($selectedCompanyID);
        return $this->sendResponse($response, trans('common.retrieved_successfully'));
    }
    public function getAllTimeMaterialConsumption(Request $request)
    {
        $selectedCompanyID = $request->input('selectedCompanyID') ??0;
        $contractUuid = $request->input('contract_id') ?? null;
        $historyUuid = $request->input('historyUuid') ?? null;
        $amendment = $request->input('amendment') ?? null;
        try
        {
            $response = $this->timeMaterialConsumptionRepository->getAllTimeMaterialConsumption(
                $contractUuid,
                $selectedCompanyID,
                $historyUuid,
                $amendment
            );
            return $this->sendResponse($response, trans('common.time_material_consumption_retrieved_successfully'));
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }
    public function pullItemsFromBOQ(Request $request)
    {
        $selectedCompanyID = $request->input('selectedCompanyID') ??0;
        $contractUuid = $request->input('contract_id') ?? null;
        $historyUuid = $request->input('historyUuid') ?? null;
        $amendment = $request->input('amendment') ?? false;
        $formData = $request->input('formData') ?? [];
        if(empty($formData))
        {
            GeneralService::sendException(trans('common.empty_records_found'));
        }
        try
        {
            $this->timeMaterialConsumptionRepository->pullItemsFromBOQ(
                $selectedCompanyID,
                $contractUuid,
                $formData,
                $amendment,
                $historyUuid
            );
            return $this->sendResponse([], trans('common.items_from_boq_pulled_successfully'));
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }
}
