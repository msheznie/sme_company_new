<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAlertAPIRequest;
use App\Http\Requests\API\UpdateAlertAPIRequest;
use App\Models\Alert;
use App\Repositories\AlertRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AlertResource;
use Response;

/**
 * Class AlertController
 * @package App\Http\Controllers\API
 */

class AlertAPIController extends AppBaseController
{
    /** @var  AlertRepository */
    private $alertRepository;
    public $notFoundMessage = 'Alert not found';

    public function __construct(AlertRepository $alertRepo)
    {
        $this->alertRepository = $alertRepo;
    }

    /**
     * Display a listing of the Alert.
     * GET|HEAD /alerts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $alerts = $this->alertRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AlertResource::collection($alerts), 'Alerts retrieved successfully');
    }

    /**
     * Store a newly created Alert in storage.
     * POST /alerts
     *
     * @param CreateAlertAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAlertAPIRequest $request)
    {
        $input = $request->all();

        $alert = $this->alertRepository->create($input);

        return $this->sendResponse(new AlertResource($alert), 'Alert saved successfully');
    }

    /**
     * Display the specified Alert.
     * GET|HEAD /alerts/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Alert $alert */
        $alert = $this->alertRepository->find($id);

        if (empty($alert))
        {
            return $this->sendError($this->notFoundMessage);
        }

        return $this->sendResponse(new AlertResource($alert), 'Alert retrieved successfully');
    }

    /**
     * Update the specified Alert in storage.
     * PUT/PATCH /alerts/{id}
     *
     * @param int $id
     * @param UpdateAlertAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAlertAPIRequest $request)
    {
        $input = $request->all();

        /** @var Alert $alert */
        $alert = $this->alertRepository->find($id);

        if (empty($alert))
        {
            return $this->sendError($this->notFoundMessage);
        }

        $alert = $this->alertRepository->update($input, $id);

        return $this->sendResponse(new AlertResource($alert), 'Alert updated successfully');
    }

    /**
     * Remove the specified Alert from storage.
     * DELETE /alerts/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Alert $alert */
        $alert = $this->alertRepository->find($id);

        if (empty($alert))
        {
            return $this->sendError($this->notFoundMessage);
        }

        $alert->delete();

        return $this->sendSuccess('Alert deleted successfully');
    }
}
