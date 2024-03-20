<?php

namespace App\Http\Controllers\API;

use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Http\Requests\API\CreateContractMasterAPIRequest;
use App\Http\Requests\API\UpdateContractMasterAPIRequest;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\Company;
use App\Models\ContractMaster;
use App\Repositories\ContractMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMasterResource;
use Response;

/**
 * Class ContractMasterController
 * @package App\Http\Controllers\API
 */

class ContractMasterAPIController extends AppBaseController
{
    /** @var  ContractMasterRepository */
    private $contractMasterRepository;

    public function __construct(ContractMasterRepository $contractMasterRepo)
    {
        $this->contractMasterRepository = $contractMasterRepo;
    }

    /**
     * Display a listing of the ContractMaster.
     * GET|HEAD /contractMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractMasters = $this->contractMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractMasterResource::collection($contractMasters), 'Contract Masters retrieved successfully');
    }

    /**
     * Store a newly created ContractMaster in storage.
     * POST /contractMasters
     *
     * @param CreateContractMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractMasterAPIRequest $request)
    {
        $input = $request->all();

        $contractMaster = $this->contractMasterRepository->create($input);

        return $this->sendResponse(new ContractMasterResource($contractMaster), 'Contract Master saved successfully');
    }

    /**
     * Display the specified ContractMaster.
     * GET|HEAD /contractMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractMaster $contractMaster */
        $contractMaster = $this->contractMasterRepository->find($id);

        if (empty($contractMaster)) {
            return $this->sendError('Contract Master not found');
        }

        return $this->sendResponse(new ContractMasterResource($contractMaster), 'Contract Master retrieved successfully');
    }

    /**
     * Update the specified ContractMaster in storage.
     * PUT/PATCH /contractMasters/{id}
     *
     * @param int $id
     * @param UpdateContractMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractMaster $contractMaster */
        $contractMaster = $this->contractMasterRepository->find($id);

        if (empty($contractMaster)) {
            return $this->sendError('Contract Master not found');
        }

        $contractMaster = $this->contractMasterRepository->update($input, $id);

        return $this->sendResponse(new ContractMasterResource($contractMaster), 'ContractMaster updated successfully');
    }

    /**
     * Remove the specified ContractMaster from storage.
     * DELETE /contractMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractMaster $contractMaster */
        $contractMaster = $this->contractMasterRepository->find($id);

        if (empty($contractMaster)) {
            return $this->sendError('Contract Master not found');
        }

        $contractMaster->delete();

        return $this->sendSuccess('Contract Master deleted successfully');
    }

    public function getContractMaster(Request $request)
    {
        return $this->contractMasterRepository->getContractMaster($request);
    }

    public function getAllContractMasterFilters(Request $request)
    {
        $contractMasterFilters = $this->contractMasterRepository->getAllContractMasterFilters($request);
        return $this->sendResponse($contractMasterFilters, 'Retrieved successfully');
    }

    public function getCounterPartyNames(Request $request)
    {
        $counterPartyNames = $this->contractMasterRepository->getCounterPartyNames($request);
        return $this->sendResponse($counterPartyNames, 'Retrieved successfully');
    }

    public function exportContractMaster(Request $request)
    {
        $input = $request->all();
        $type = $request->type;
        $disk = $request->disk;
        $docName = $request->doc_name;
        $contractMaster = $this->contractMasterRepository->exportContractMasterReport($request);
        $companyMaster = Company::find(isset($input['companySystemID']) ? $input['companySystemID'] : null);
        $companyCode = isset($companyMaster->CompanyID) ? $companyMaster->CompanyID : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($contractMaster);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '') {
            return $this->sendError('unable_to_export_excel');
        } else {
            return $this->sendResponse($basePath, trans('success_export'));
        }
    }

    public function deleteFileFromAws(Request $request)
    {
        $path = $request->path;
        $disk = $request->disk;
        DeleteFileFromS3Job::dispatch($path, $disk)->delay(now()->addMinutes(5));
    }
}
