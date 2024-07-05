<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ContractCreationException;
use App\Http\Requests\API\CreateCMContractReminderScenarioAPIRequest;
use App\Http\Requests\API\UpdateCMContractReminderScenarioAPIRequest;
use App\Models\CMContractReminderScenario;
use App\Repositories\CMContractReminderScenarioRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CMContractReminderScenarioResource;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class CMContractReminderScenarioController
 * @package App\Http\Controllers\API
 */

class CMContractReminderScenarioAPIController extends AppBaseController
{
    const UNEXPECTED_ERROR_MESSAGE = 'UNEXPECTED_ERROR_MESSAGE';

    /** @var  CMContractReminderScenarioRepository */
    private $cMContractReminderScenarioRepository;

    public function __construct(CMContractReminderScenarioRepository $cMContractReminderScenarioRepo)
    {
        $this->cMContractReminderScenarioRepository = $cMContractReminderScenarioRepo;
    }

    /**
     * Display a listing of the CMContractReminderScenario.
     * GET|HEAD /cMContractReminderScenarios
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cMContractReminderScenarioRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(' ', 'Contract Reminder Scenarios retrieved successfully');
    }

    /**
     * Store a newly created CMContractReminderScenario in storage.
     * POST /cMContractReminderScenarios
     *
     * @param CreateCMContractReminderScenarioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCMContractReminderScenarioAPIRequest $request)
    {
        $input = $request->all();

        $this->cMContractReminderScenarioRepository->create($input);

        return $this->sendResponse('', 'C M Contract Reminder Scenario saved successfully');
    }

    /**
     * Display the specified CMContractReminderScenario.
     * GET|HEAD /cMContractReminderScenarios/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CMContractReminderScenario $cMContractReminderScenario */
        $cMContractReminderScenario = $this->cMContractReminderScenarioRepository->find($id);

        if (empty($cMContractReminderScenario))
        {
            return $this->sendError('Contract Reminder Scenario not found');
        }

        return $this->sendResponse('', 'C M Contract Reminder Scenario retrieved successfully');
    }

    /**
     * Update the specified CMContractReminderScenario in storage.
     * PUT/PATCH /cMContractReminderScenarios/{id}
     *
     * @param int $id
     * @param UpdateCMContractReminderScenarioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCMContractReminderScenarioAPIRequest $request)
    {
        $input = $request->all();

        /** @var CMContractReminderScenario $cMContractReminderScenario */
        $cMContractReminderScenario = $this->cMContractReminderScenarioRepository->find($id);

        if (empty($cMContractReminderScenario))
        {
            return $this->sendError('C M Contract Reminder Scenario not found');
        }

        $this->cMContractReminderScenarioRepository->update($input, $id);

        return $this->sendResponse('', 'CMContractReminderScenario updated successfully');
    }

    /**
     * Remove the specified CMContractReminderScenario from storage.
     * DELETE /cMContractReminderScenarios/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CMContractReminderScenario $cMContractReminderScenario */
        $cMContractReminderScenario = $this->cMContractReminderScenarioRepository->find($id);

        if (empty($cMContractReminderScenario))
        {
            return $this->sendError('C M Contract Reminder Scenario not found');
        }

        $cMContractReminderScenario->delete();

        return $this->sendSuccess('C M Contract Reminder Scenario deleted successfully');
    }

    public function showReminders(Request $request)
    {
        $input = $request->all();
        try
        {
            $data = $this->cMContractReminderScenarioRepository->showReminders($input);
            $responseData = ['data' => $data];
            return response()->json($responseData);
        } catch (\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

    public function showRemindersDropValues(Request $request)
    {
        $input = $request->all();
        try
        {
            $data = $this->cMContractReminderScenarioRepository->showRemindersDropValues($input);
            $isActive = $this->cMContractReminderScenarioRepository->showRemindersValues($input);
            $beforeVal = $this->cMContractReminderScenarioRepository->showRemindersSettingValue($input, 1);
            $afterVal = $this->cMContractReminderScenarioRepository->showRemindersSettingValue($input, 2);
            $responseData = ['data' => $data, 'isActive' => $isActive,
                'beforeVal' => $beforeVal, 'afterVal' => $afterVal ];
            return response()->json($responseData);
        } catch (\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

    public function createReminderConfiguration(Request $request)
    {
        try
        {
            $contractUuid =  $this->cMContractReminderScenarioRepository->createContractReminderConfiguration($request);
            return $this->sendResponse($contractUuid,'Successfully Created');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' .$e, 500);
        }
    }
}
