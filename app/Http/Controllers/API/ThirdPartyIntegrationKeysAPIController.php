<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateThirdPartyIntegrationKeysAPIRequest;
use App\Http\Requests\API\UpdateThirdPartyIntegrationKeysAPIRequest;
use App\Models\ThirdPartyIntegrationKeys;
use App\Repositories\ThirdPartyIntegrationKeysRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ThirdPartyIntegrationKeysResource;
use Response;

/**
 * Class ThirdPartyIntegrationKeysController
 * @package App\Http\Controllers\API
 */

class ThirdPartyIntegrationKeysAPIController extends AppBaseController
{
    /** @var  ThirdPartyIntegrationKeysRepository */
    private $thirdPartyIntegrationKeysRepository;

    public function __construct(ThirdPartyIntegrationKeysRepository $thirdPartyIntegrationKeysRepo)
    {
        $this->thirdPartyIntegrationKeysRepository = $thirdPartyIntegrationKeysRepo;
    }

    /**
     * Display a listing of the ThirdPartyIntegrationKeys.
     * GET|HEAD /thirdPartyIntegrationKeys
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $thirdPartyIntegrationKeys = $this->thirdPartyIntegrationKeysRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ThirdPartyIntegrationKeysResource::collection($thirdPartyIntegrationKeys), 'Third Party Integration Keys retrieved successfully');
    }

    /**
     * Store a newly created ThirdPartyIntegrationKeys in storage.
     * POST /thirdPartyIntegrationKeys
     *
     * @param CreateThirdPartyIntegrationKeysAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateThirdPartyIntegrationKeysAPIRequest $request)
    {
        $input = $request->all();

        $thirdPartyIntegrationKeys = $this->thirdPartyIntegrationKeysRepository->create($input);

        return $this->sendResponse(new ThirdPartyIntegrationKeysResource($thirdPartyIntegrationKeys), 'Third Party Integration Keys saved successfully');
    }

    /**
     * Display the specified ThirdPartyIntegrationKeys.
     * GET|HEAD /thirdPartyIntegrationKeys/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ThirdPartyIntegrationKeys $thirdPartyIntegrationKeys */
        $thirdPartyIntegrationKeys = $this->thirdPartyIntegrationKeysRepository->find($id);

        if (empty($thirdPartyIntegrationKeys)) {
            return $this->sendError('Third Party Integration Keys not found');
        }

        return $this->sendResponse(new ThirdPartyIntegrationKeysResource($thirdPartyIntegrationKeys), 'Third Party Integration Keys retrieved successfully');
    }

    /**
     * Update the specified ThirdPartyIntegrationKeys in storage.
     * PUT/PATCH /thirdPartyIntegrationKeys/{id}
     *
     * @param int $id
     * @param UpdateThirdPartyIntegrationKeysAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateThirdPartyIntegrationKeysAPIRequest $request)
    {
        $input = $request->all();

        /** @var ThirdPartyIntegrationKeys $thirdPartyIntegrationKeys */
        $thirdPartyIntegrationKeys = $this->thirdPartyIntegrationKeysRepository->find($id);

        if (empty($thirdPartyIntegrationKeys)) {
            return $this->sendError('Third Party Integration Keys not found');
        }

        $thirdPartyIntegrationKeys = $this->thirdPartyIntegrationKeysRepository->update($input, $id);

        return $this->sendResponse(new ThirdPartyIntegrationKeysResource($thirdPartyIntegrationKeys), 'ThirdPartyIntegrationKeys updated successfully');
    }

    /**
     * Remove the specified ThirdPartyIntegrationKeys from storage.
     * DELETE /thirdPartyIntegrationKeys/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ThirdPartyIntegrationKeys $thirdPartyIntegrationKeys */
        $thirdPartyIntegrationKeys = $this->thirdPartyIntegrationKeysRepository->find($id);

        if (empty($thirdPartyIntegrationKeys)) {
            return $this->sendError('Third Party Integration Keys not found');
        }

        $thirdPartyIntegrationKeys->delete();

        return $this->sendSuccess('Third Party Integration Keys deleted successfully');
    }
}
