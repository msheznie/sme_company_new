<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWebEmployeeProfileAPIRequest;
use App\Http\Requests\API\UpdateWebEmployeeProfileAPIRequest;
use App\Models\WebEmployeeProfile;
use App\Repositories\WebEmployeeProfileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\WebEmployeeProfileResource;
use Response;

/**
 * Class WebEmployeeProfileController
 * @package App\Http\Controllers\API
 */

class WebEmployeeProfileAPIController extends AppBaseController
{
    /** @var  WebEmployeeProfileRepository */
    private $webEmployeeProfileRepository;

    public function __construct(WebEmployeeProfileRepository $webEmployeeProfileRepo)
    {
        $this->webEmployeeProfileRepository = $webEmployeeProfileRepo;
    }

    /**
     * Display a listing of the WebEmployeeProfile.
     * GET|HEAD /webEmployeeProfiles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $webEmployeeProfiles = $this->webEmployeeProfileRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(WebEmployeeProfileResource::collection($webEmployeeProfiles), 'Web Employee Profiles retrieved successfully');
    }

    /**
     * Store a newly created WebEmployeeProfile in storage.
     * POST /webEmployeeProfiles
     *
     * @param CreateWebEmployeeProfileAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateWebEmployeeProfileAPIRequest $request)
    {
        $input = $request->all();

        $webEmployeeProfile = $this->webEmployeeProfileRepository->create($input);

        return $this->sendResponse(new WebEmployeeProfileResource($webEmployeeProfile), 'Web Employee Profile saved successfully');
    }

    /**
     * Display the specified WebEmployeeProfile.
     * GET|HEAD /webEmployeeProfiles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var WebEmployeeProfile $webEmployeeProfile */
        $webEmployeeProfile = $this->webEmployeeProfileRepository->find($id);

        if (empty($webEmployeeProfile)) {
            return $this->sendError('Web Employee Profile not found');
        }

        return $this->sendResponse(new WebEmployeeProfileResource($webEmployeeProfile), 'Web Employee Profile retrieved successfully');
    }

    /**
     * Update the specified WebEmployeeProfile in storage.
     * PUT/PATCH /webEmployeeProfiles/{id}
     *
     * @param int $id
     * @param UpdateWebEmployeeProfileAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWebEmployeeProfileAPIRequest $request)
    {
        $input = $request->all();

        /** @var WebEmployeeProfile $webEmployeeProfile */
        $webEmployeeProfile = $this->webEmployeeProfileRepository->find($id);

        if (empty($webEmployeeProfile)) {
            return $this->sendError('Web Employee Profile not found');
        }

        $webEmployeeProfile = $this->webEmployeeProfileRepository->update($input, $id);

        return $this->sendResponse(new WebEmployeeProfileResource($webEmployeeProfile), 'WebEmployeeProfile updated successfully');
    }

    /**
     * Remove the specified WebEmployeeProfile from storage.
     * DELETE /webEmployeeProfiles/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var WebEmployeeProfile $webEmployeeProfile */
        $webEmployeeProfile = $this->webEmployeeProfileRepository->find($id);

        if (empty($webEmployeeProfile)) {
            return $this->sendError('Web Employee Profile not found');
        }

        $webEmployeeProfile->delete();

        return $this->sendSuccess('Web Employee Profile deleted successfully');
    }
}
