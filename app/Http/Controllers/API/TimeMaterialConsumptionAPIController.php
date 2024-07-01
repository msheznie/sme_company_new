<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateTimeMaterialConsumptionAPIRequest;
use App\Http\Requests\API\UpdateTimeMaterialConsumptionAPIRequest;
use App\Models\TimeMaterialConsumption;
use App\Repositories\TimeMaterialConsumptionRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TimeMaterialConsumptionResource;
use Response;

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
        try
        {
            $this->timeMaterialConsumptionRepository->createTimeMaterialConsumption(
                $contractUuid,
                $selectedCompanyID
            );
            return $this->sendResponse([], 'Time and material consumption created successfully.');
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
            return $this->sendError('Time Material Consumption not found');
        }

        return $this->sendResponse(new TimeMaterialConsumptionResource($timeMaterialConsumption),
            'Time Material Consumption retrieved successfully');
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
        try
        {
            $consumption = $this->timeMaterialConsumptionRepository->findByUuid($id, ['id']);

            if (empty($consumption))
            {
                throw new CommonException('Time and material consumption not found.');
            }
            $this->timeMaterialConsumptionRepository->updateTimeMaterialConsumption($input, $consumption['id']);
            return $this->sendResponse([], trans('Time and material consumption saved successfully.'));
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
    public function destroy($id)
    {
        /** @var TimeMaterialConsumption $timeMaterialConsumption */
        $consumption = $this->timeMaterialConsumptionRepository->findByUuid($id, ['id']);

        if (empty($consumption))
        {
            return $this->sendError(trans('common.time_material_consumption_not_found'));
        }

        $consumption->delete();

        return $this->sendSuccess('Time Material Consumption deleted successfully');
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
        try
        {
            $response = $this->timeMaterialConsumptionRepository->getAllTimeMaterialConsumption(
                $contractUuid,
                $selectedCompanyID
            );
            return $this->sendResponse($response, 'Time material consumption retrieved successfully.');
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
        $formData = $request->input('formData') ?? [];
        if(empty($formData))
        {
            throw new CommonException('Empty records found.');
        }
        try
        {
            $this->timeMaterialConsumptionRepository->pullItemsFromBOQ(
                $selectedCompanyID,
                $contractUuid,
                $formData
            );
            return $this->sendResponse([], 'Items from BOQ pulled successfully.');
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }
}
