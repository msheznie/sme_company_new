<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAppearanceSettingsAPIRequest;
use App\Http\Requests\API\UpdateAppearanceSettingsAPIRequest;
use App\Models\AppearanceSettings;
use App\Repositories\AppearanceSettingsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AppearanceSettingsResource;
use Response;

/**
 * Class AppearanceSettingsController
 * @package App\Http\Controllers\API
 */

class AppearanceSettingsAPIController extends AppBaseController
{
    /** @var  AppearanceSettingsRepository */
    private $appearanceSettingsRepository;
    protected $notFoundMessage = 'Appearance Settings not found';

    public function __construct(AppearanceSettingsRepository $appearanceSettingsRepo)
    {
        $this->appearanceSettingsRepository = $appearanceSettingsRepo;
    }

    /**
     * Display a listing of the AppearanceSettings.
     * GET|HEAD /appearanceSettings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $appearanceSettings = $this->appearanceSettingsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AppearanceSettingsResource::collection($appearanceSettings),
            'Appearance Settings retrieved successfully');
    }

    /**
     * Store a newly created AppearanceSettings in storage.
     * POST /appearanceSettings
     *
     * @param CreateAppearanceSettingsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAppearanceSettingsAPIRequest $request)
    {
        $input = $request->all();

        $appearanceSettings = $this->appearanceSettingsRepository->create($input);

        return $this->sendResponse(new AppearanceSettingsResource($appearanceSettings),
            'Appearance Settings saved successfully');
    }

    /**
     * Display the specified AppearanceSettings.
     * GET|HEAD /appearanceSettings/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AppearanceSettings $appearanceSettings */
        $appearanceSettings = $this->appearanceSettingsRepository->find($id);

        if (empty($appearanceSettings))
        {
            return $this->sendError($this->notFoundMessage);
        }

        return $this->sendResponse(new AppearanceSettingsResource($appearanceSettings),
            'Appearance Settings retrieved successfully');
    }

    /**
     * Update the specified AppearanceSettings in storage.
     * PUT/PATCH /appearanceSettings/{id}
     *
     * @param int $id
     * @param UpdateAppearanceSettingsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAppearanceSettingsAPIRequest $request)
    {
        $input = $request->all();

        /** @var AppearanceSettings $appearanceSettings */
        $appearanceSettings = $this->appearanceSettingsRepository->find($id);

        if (empty($appearanceSettings))
        {
            return $this->sendError($this->notFoundMessage);
        }

        $appearanceSettings = $this->appearanceSettingsRepository->update($input, $id);

        return $this->sendResponse(new AppearanceSettingsResource($appearanceSettings),
            'AppearanceSettings updated successfully');
    }

    /**
     * Remove the specified AppearanceSettings from storage.
     * DELETE /appearanceSettings/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AppearanceSettings $appearanceSettings */
        $appearanceSettings = $this->appearanceSettingsRepository->find($id);

        if (empty($appearanceSettings))
        {
            return $this->sendError($this->notFoundMessage);
        }

        $appearanceSettings->delete();

        return $this->sendSuccess('Appearance Settings deleted successfully');
    }
}
