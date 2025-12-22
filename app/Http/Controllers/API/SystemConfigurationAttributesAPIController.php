<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSystemConfigurationAttributesAPIRequest;
use App\Http\Requests\API\UpdateSystemConfigurationAttributesAPIRequest;
use App\Models\SystemConfigurationAttributes;
use App\Repositories\SystemConfigurationAttributesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SystemConfigurationAttributesResource;
use Response;

/**
 * Class SystemConfigurationAttributesController
 * @package App\Http\Controllers\API
 */

class SystemConfigurationAttributesAPIController extends AppBaseController
{
    /** @var  SystemConfigurationAttributesRepository */
    private $systemConfigurationAttributesRepository;
    protected $notFound = 'System Configuration Attributes not found';

    public function __construct(SystemConfigurationAttributesRepository $systemConfigurationAttributesRepo)
    {
        $this->systemConfigurationAttributesRepository = $systemConfigurationAttributesRepo;
    }

    /**
     * Display a listing of the SystemConfigurationAttributes.
     * GET|HEAD /systemConfigurationAttributes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $systemConfigurationAttributes = $this->systemConfigurationAttributesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SystemConfigurationAttributesResource::collection($systemConfigurationAttributes),
            'System Configuration Attributes retrieved successfully');
    }

    /**
     * Store a newly created SystemConfigurationAttributes in storage.
     * POST /systemConfigurationAttributes
     *
     * @param CreateSystemConfigurationAttributesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSystemConfigurationAttributesAPIRequest $request)
    {
        $input = $request->all();

        $systemConfigurationAttributes = $this->systemConfigurationAttributesRepository->create($input);

        return $this->sendResponse(new SystemConfigurationAttributesResource($systemConfigurationAttributes),
            'System Configuration Attributes saved successfully');
    }

    /**
     * Display the specified SystemConfigurationAttributes.
     * GET|HEAD /systemConfigurationAttributes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SystemConfigurationAttributes $systemConfigurationAttributes */
        $systemConfigurationAttributes = $this->systemConfigurationAttributesRepository->find($id);

        if (empty($systemConfigurationAttributes))
        {
            return $this->sendError($this->notFound);
        }

        return $this->sendResponse(new SystemConfigurationAttributesResource($systemConfigurationAttributes),
            'System Configuration Attributes retrieved successfully');
    }

    /**
     * Update the specified SystemConfigurationAttributes in storage.
     * PUT/PATCH /systemConfigurationAttributes/{id}
     *
     * @param int $id
     * @param UpdateSystemConfigurationAttributesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSystemConfigurationAttributesAPIRequest $request)
    {
        $input = $request->all();

        /** @var SystemConfigurationAttributes $systemConfigurationAttributes */
        $systemConfigurationAttributes = $this->systemConfigurationAttributesRepository->find($id);

        if (empty($systemConfigurationAttributes))
        {
            return $this->sendError('System Configuration Attributes not found');
        }

        $systemConfigurationAttributes = $this->systemConfigurationAttributesRepository->update($input, $id);

        return $this->sendResponse(new SystemConfigurationAttributesResource($systemConfigurationAttributes),
            'SystemConfigurationAttributes updated successfully');
    }

    /**
     * Remove the specified SystemConfigurationAttributes from storage.
     * DELETE /systemConfigurationAttributes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SystemConfigurationAttributes $systemConfigurationAttributes */
        $systemConfigurationAttributes = $this->systemConfigurationAttributesRepository->find($id);

        if (empty($systemConfigurationAttributes))
        {
            return $this->sendError($this->notFound);
        }

        $systemConfigurationAttributes->delete();

        return $this->sendSuccess('System Configuration Attributes deleted successfully');
    }
}
