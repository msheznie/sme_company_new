<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateThirdPartySystemsAPIRequest;
use App\Http\Requests\API\UpdateThirdPartySystemsAPIRequest;
use App\Models\ThirdPartySystems;
use App\Repositories\ThirdPartySystemsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ThirdPartySystemsResource;
use Response;

/**
 * Class ThirdPartySystemsController
 * @package App\Http\Controllers\API
 */

class ThirdPartySystemsAPIController extends AppBaseController
{
    /** @var  ThirdPartySystemsRepository */
    private $thirdPartySystemsRepository;

    public function __construct(ThirdPartySystemsRepository $thirdPartySystemsRepo)
    {
        $this->thirdPartySystemsRepository = $thirdPartySystemsRepo;
    }

    /**
     * Display a listing of the ThirdPartySystems.
     * GET|HEAD /thirdPartySystems
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $thirdPartySystems = $this->thirdPartySystemsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ThirdPartySystemsResource::collection($thirdPartySystems), 'Third Party Systems retrieved successfully');
    }

    /**
     * Store a newly created ThirdPartySystems in storage.
     * POST /thirdPartySystems
     *
     * @param CreateThirdPartySystemsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateThirdPartySystemsAPIRequest $request)
    {
        $input = $request->all();

        $thirdPartySystems = $this->thirdPartySystemsRepository->create($input);

        return $this->sendResponse(new ThirdPartySystemsResource($thirdPartySystems), 'Third Party Systems saved successfully');
    }

    /**
     * Display the specified ThirdPartySystems.
     * GET|HEAD /thirdPartySystems/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ThirdPartySystems $thirdPartySystems */
        $thirdPartySystems = $this->thirdPartySystemsRepository->find($id);

        if (empty($thirdPartySystems)) {
            return $this->sendError('Third Party Systems not found');
        }

        return $this->sendResponse(new ThirdPartySystemsResource($thirdPartySystems), 'Third Party Systems retrieved successfully');
    }

    /**
     * Update the specified ThirdPartySystems in storage.
     * PUT/PATCH /thirdPartySystems/{id}
     *
     * @param int $id
     * @param UpdateThirdPartySystemsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateThirdPartySystemsAPIRequest $request)
    {
        $input = $request->all();

        /** @var ThirdPartySystems $thirdPartySystems */
        $thirdPartySystems = $this->thirdPartySystemsRepository->find($id);

        if (empty($thirdPartySystems)) {
            return $this->sendError('Third Party Systems not found');
        }

        $thirdPartySystems = $this->thirdPartySystemsRepository->update($input, $id);

        return $this->sendResponse(new ThirdPartySystemsResource($thirdPartySystems), 'ThirdPartySystems updated successfully');
    }

    /**
     * Remove the specified ThirdPartySystems from storage.
     * DELETE /thirdPartySystems/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ThirdPartySystems $thirdPartySystems */
        $thirdPartySystems = $this->thirdPartySystemsRepository->find($id);

        if (empty($thirdPartySystems)) {
            return $this->sendError('Third Party Systems not found');
        }

        $thirdPartySystems->delete();

        return $this->sendSuccess('Third Party Systems deleted successfully');
    }
}
