<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Http\Requests\API\CreateTemplateMasterAPIRequest;
use App\Http\Requests\API\UpdateTemplateMasterAPIRequest;
use App\Jobs\DeleteFileJob;
use App\Models\ContractMaster;
use App\Models\TemplateMaster;
use App\Repositories\TemplateMasterRepository;
use App\Services\GeneralService;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TemplateMasterResource;
use App\Services\ContractMasterService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Response;

/**
 * Class TemplateMasterController
 * @package App\Http\Controllers\API
 */

class TemplateMasterAPIController extends AppBaseController
{
    /** @var  TemplateMasterRepository */
    private $templateMasterRepository;
    private $contractMasterService;

    public function __construct(
        TemplateMasterRepository $templateMasterRepo,
        ContractMasterService $contractMasterService
    )
    {
        $this->templateMasterRepository = $templateMasterRepo;
        $this->contractMasterService = $contractMasterService;
    }

    /**
     * Display a listing of the TemplateMaster.
     * GET|HEAD /templateMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $templateMasters = $this->templateMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(TemplateMasterResource::collection($templateMasters), 'Template Masters retrieved successfully');
    }

    /**
     * Store a newly created TemplateMaster in storage.
     * POST /templateMasters
     *
     * @param CreateTemplateMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTemplateMasterAPIRequest $request)
    {
        try
        {
            $this->templateMasterRepository->createTemplateMaster($request);
            return $this->sendResponse([], 'Template master created successfully.');
        } catch (CommonException $ex)
        {
            return $this->sendError('Failed to create template master: ' . $ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError('Failed to create template master: ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified TemplateMaster.
     * GET|HEAD /templateMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        /** @var TemplateMaster $templateMaster */
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        $templateMaster = $this->templateMasterRepository->findByUuid($id, ['id', 'uuid', 'contract_id', 'filePath', 'content']);

        if (empty($templateMaster)) {
            return $this->sendError('Template Master not found');
        }

        $contract = ContractMaster::select('uuid')->where('id', $templateMaster->contract_id)->first();
        $contractUuid = $contract['uuid'] ?? null;

        $contractMaster = $this->contractMasterService->getContractViewData($contractUuid, $companySystemID, null);

        $disk = 's3';
        $fileContent = '';

        if (Storage::disk($disk)->exists($templateMaster->filePath))
        {
            $downloadedFile = Storage::disk($disk)->get($templateMaster->filePath);
            $fileName = basename($templateMaster->filePath); // Get the file name from the path
            $publicPath = public_path('uploads/templates');

            if (!File::exists($publicPath)) {
                File::makeDirectory($publicPath, 0755, true);
            }

            $path = $publicPath . '/' . $fileName;
            File::put($path, $downloadedFile);

            $fileContent = File::get(public_path('uploads/templates/' . $fileName));
            DeleteFileJob::dispatch(public_path('uploads/templates/' . $fileName))->delay(now()->addMinute(2));
        }

        $tempData = [
            'template_master' => $templateMaster,
            'contract_master' => $contractMaster,
            'file_content' => $fileContent
        ];

        return $this->sendResponse($tempData, 'Template Master retrieved successfully');
    }

    /**
     * Update the specified TemplateMaster in storage.
     * PUT/PATCH /templateMasters/{id}
     *
     * @param int $id
     * @param UpdateTemplateMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTemplateMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var TemplateMaster $templateMaster */
        try
        {
            $templateMaster = $this->templateMasterRepository->findByUuid($id);

            if (empty($templateMaster)) {
                GeneralService::sendException('Template master not found');
            }

            $this->templateMasterRepository->updateTemplateMaster($input, $templateMaster->id, $templateMaster->filePath);
            return $this->sendResponse([], 'Template master updated successfully');

        } catch (CommonException $ex)
        {
            return $this->sendError('Failed to create template master: ' . $ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError('Failed to create template master: ' . $ex->getMessage());
        }
    }

    /**
     * Remove the specified TemplateMaster from storage.
     * DELETE /templateMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TemplateMaster $templateMaster */
        $templateMaster = $this->templateMasterRepository->find($id);

        if (empty($templateMaster)) {
            return $this->sendError('Template Master not found');
        }

        $templateMaster->delete();

        return $this->sendSuccess('Template Master deleted successfully');
    }
}
