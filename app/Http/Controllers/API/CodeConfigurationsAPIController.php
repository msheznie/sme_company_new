<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateCodeConfigurationsAPIRequest;
use App\Http\Requests\API\UpdateCodeConfigurationsAPIRequest;
use App\Models\CodeConfigurations;
use App\Repositories\CodeConfigurationsRepository;
use App\Services\GeneralService;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CodeConfigurationsResource;
use Response;

/**
 * Class CodeConfigurationsController
 * @package App\Http\Controllers\API
 */

class CodeConfigurationsAPIController extends AppBaseController
{
    /** @var  CodeConfigurationsRepository */
    private $codeConfigurationsRepository;

    public function __construct(CodeConfigurationsRepository $codeConfigurationsRepo)
    {
        $this->codeConfigurationsRepository = $codeConfigurationsRepo;
    }

    /**
     * Display a listing of the CodeConfigurations.
     * GET|HEAD /codeConfigurations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $codeConfigurations = $this->codeConfigurationsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CodeConfigurationsResource::collection($codeConfigurations),
            'Code Configurations retrieved successfully');
    }

    /**
     * Store a newly created CodeConfigurations in storage.
     * POST /codeConfigurations
     *
     * @param CreateCodeConfigurationsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCodeConfigurationsAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $this->codeConfigurationsRepository->createCodeConfiguration($input);
            return $this->sendResponse([], 'Code configuration created successfully');
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified CodeConfigurations.
     * GET|HEAD /codeConfigurations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CodeConfigurations $codeConfigurations */
        $codeConfigurations = $this->codeConfigurationsRepository->find($id);

        if (empty($codeConfigurations))
        {
            return $this->sendError('Code Configurations not found');
        }

        return $this->sendResponse(new CodeConfigurationsResource($codeConfigurations),
            'Code Configurations retrieved successfully');
    }

    /**
     * Update the specified CodeConfigurations in storage.
     * PUT/PATCH /codeConfigurations/{id}
     *
     * @param int $id
     * @param UpdateCodeConfigurationsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCodeConfigurationsAPIRequest $request)
    {
        $input = $request->all();

        /** @var CodeConfigurations $codeConfigurations */
        $codeConfiguration = $this->codeConfigurationsRepository->findByUuid($id, ['id']);

        if (empty($codeConfiguration))
        {
            GeneralService::sendException(trans('common.code_configurations_not_found'));
        }
        try
        {
            $this->codeConfigurationsRepository->updateCodeConfiguration($input, $codeConfiguration['id']);
            return $this->sendResponse([], 'Code configuration updated successfully');
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified CodeConfigurations from storage.
     * DELETE /codeConfigurations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CodeConfigurations $codeConfigurations */
        $codeConfigurations = $this->codeConfigurationsRepository->find($id);

        if (empty($codeConfigurations))
        {
            return $this->sendError('Code Configurations not found');
        }

        $codeConfigurations->delete();

        return $this->sendSuccess('Code Configurations deleted successfully');
    }
    public function getAllCodeConfiguration(Request $request)
    {
        return $this->codeConfigurationsRepository->getAllCodeConfiguration($request);
    }
}
