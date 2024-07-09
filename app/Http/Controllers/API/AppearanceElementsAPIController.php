<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAppearanceElementsAPIRequest;
use App\Http\Requests\API\UpdateAppearanceElementsAPIRequest;
use App\Models\AppearanceElements;
use App\Repositories\AppearanceElementsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AppearanceElementsResource;
use Response;

/**
 * Class AppearanceElementsController
 * @package App\Http\Controllers\API
 */

class AppearanceElementsAPIController extends AppBaseController
{
    /** @var  AppearanceElementsRepository */
    private $appearanceElementsRepository;
    protected $notFound = 'Appearance Elements not found';

    public function __construct(AppearanceElementsRepository $appearanceElementsRepo)
    {
        $this->appearanceElementsRepository = $appearanceElementsRepo;
    }

    /**
     * Display a listing of the AppearanceElements.
     * GET|HEAD /appearanceElements
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $appearanceElements = $this->appearanceElementsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AppearanceElementsResource::collection($appearanceElements),
            'Appearance Elements retrieved successfully');
    }

    /**
     * Store a newly created AppearanceElements in storage.
     * POST /appearanceElements
     *
     * @param CreateAppearanceElementsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAppearanceElementsAPIRequest $request)
    {
        $input = $request->all();

        $appearanceElements = $this->appearanceElementsRepository->create($input);

        return $this->sendResponse(new AppearanceElementsResource($appearanceElements),
            'Appearance Elements saved successfully');
    }

    /**
     * Display the specified AppearanceElements.
     * GET|HEAD /appearanceElements/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AppearanceElements $appearanceElements */
        $appearanceElements = $this->appearanceElementsRepository->find($id);

        if (empty($appearanceElements))
        {
            return $this->sendError($this->notFound);
        }

        return $this->sendResponse(new AppearanceElementsResource($appearanceElements),
            'Appearance Elements retrieved successfully');
    }

    /**
     * Update the specified AppearanceElements in storage.
     * PUT/PATCH /appearanceElements/{id}
     *
     * @param int $id
     * @param UpdateAppearanceElementsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAppearanceElementsAPIRequest $request)
    {
        $input = $request->all();

        /** @var AppearanceElements $appearanceElements */
        $appearanceElements = $this->appearanceElementsRepository->find($id);

        if (empty($appearanceElements))
        {
            return $this->sendError($this->notFound);
        }

        $appearanceElements = $this->appearanceElementsRepository->update($input, $id);

        return $this->sendResponse(new AppearanceElementsResource($appearanceElements),
            'AppearanceElements updated successfully');
    }

    /**
     * Remove the specified AppearanceElements from storage.
     * DELETE /appearanceElements/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AppearanceElements $appearanceElements */
        $appearanceElements = $this->appearanceElementsRepository->find($id);

        if (empty($appearanceElements))
        {
            return $this->sendError('Appearance Elements not found');
        }

        $appearanceElements->delete();

        return $this->sendSuccess('Appearance Elements deleted successfully');
    }
}
