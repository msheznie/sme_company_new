<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSystemConfigurationDetailAPIRequest;
use App\Http\Requests\API\UpdateSystemConfigurationDetailAPIRequest;
use App\Models\SystemConfigurationDetail;
use App\Repositories\SystemConfigurationDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SystemConfigurationDetailResource;
use Response;

/**
 * Class SystemConfigurationDetailController
 * @package App\Http\Controllers\API
 */

class SystemConfigurationDetailAPIController extends AppBaseController
{
    /** @var  SystemConfigurationDetailRepository */
    private $systemConfigurationDetailRepository;
    protected $notFound = 'System Configuration Detail not found';

    public function __construct(SystemConfigurationDetailRepository $systemConfigurationDetailRepo)
    {
        $this->systemConfigurationDetailRepository = $systemConfigurationDetailRepo;
    }

    /**
     * Display a listing of the SystemConfigurationDetail.
     * GET|HEAD /systemConfigurationDetails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $systemConfigurationDetails = $this->systemConfigurationDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SystemConfigurationDetailResource::collection($systemConfigurationDetails),
            'System Configuration Details retrieved successfully');
    }

    /**
     * Store a newly created SystemConfigurationDetail in storage.
     * POST /systemConfigurationDetails
     *
     * @param CreateSystemConfigurationDetailAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSystemConfigurationDetailAPIRequest $request)
    {
        $input = $request->all();

        $systemConfigurationDetail = $this->systemConfigurationDetailRepository->create($input);

        return $this->sendResponse(new SystemConfigurationDetailResource($systemConfigurationDetail),
            'System Configuration Detail saved successfully');
    }

    /**
     * Display the specified SystemConfigurationDetail.
     * GET|HEAD /systemConfigurationDetails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SystemConfigurationDetail $systemConfigurationDetail */
        $systemConfigurationDetail = $this->systemConfigurationDetailRepository->find($id);

        if (empty($systemConfigurationDetail))
        {
            return $this->sendError($this->notFound);
        }

        return $this->sendResponse(new SystemConfigurationDetailResource($systemConfigurationDetail),
            'System Configuration Detail retrieved successfully');
    }

    /**
     * Update the specified SystemConfigurationDetail in storage.
     * PUT/PATCH /systemConfigurationDetails/{id}
     *
     * @param int $id
     * @param UpdateSystemConfigurationDetailAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSystemConfigurationDetailAPIRequest $request)
    {
        $input = $request->all();

        /** @var SystemConfigurationDetail $systemConfigurationDetail */
        $systemConfigurationDetail = $this->systemConfigurationDetailRepository->find($id);

        if (empty($systemConfigurationDetail))
        {
            return $this->sendError($this->notFound);
        }

        $systemConfigurationDetail = $this->systemConfigurationDetailRepository->update($input, $id);

        return $this->sendResponse(new SystemConfigurationDetailResource($systemConfigurationDetail),
            'SystemConfigurationDetail updated successfully');
    }

    /**
     * Remove the specified SystemConfigurationDetail from storage.
     * DELETE /systemConfigurationDetails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SystemConfigurationDetail $systemConfigurationDetail */
        $systemConfigurationDetail = $this->systemConfigurationDetailRepository->find($id);

        if (empty($systemConfigurationDetail))
        {
            return $this->sendError($this->notFound);
        }

        $systemConfigurationDetail->delete();

        return $this->sendSuccess('System Configuration Detail deleted successfully');
    }
}
