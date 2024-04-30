<?php

namespace App\Http\Controllers\API;

use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateContractBoqItemsAPIRequest;
use App\Http\Requests\API\UpdateContractBoqItemsAPIRequest;
use App\Models\Company;
use App\Models\ContractBoqItems;
use App\Models\ContractMaster;
use App\Repositories\ContractBoqItemsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractBoqItemsResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class ContractBoqItemsController
 * @package App\Http\Controllers\API
 */

class ContractBoqItemsAPIController extends AppBaseController
{
    /** @var  ContractBoqItemsRepository */
    private $contractBoqItemsRepository;

    public function __construct(ContractBoqItemsRepository $contractBoqItemsRepo)
    {
        $this->contractBoqItemsRepository = $contractBoqItemsRepo;
    }

    /**
     * Display a listing of the ContractBoqItems.
     * GET|HEAD /contractBoqItems
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractBoqItems = $this->contractBoqItemsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ContractBoqItemsResource::collection($contractBoqItems), 'BOQ Items retrieved successfully');
    }

    /**
     * Store a newly created ContractBoqItems in storage.
     * POST /contractBoqItems
     *
     * @param CreateContractBoqItemsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractBoqItemsAPIRequest $request)
    {
        $input = $request->all();

        $contractBoqItems = $this->contractBoqItemsRepository->create($input);

        return $this->sendResponse(new ContractBoqItemsResource($contractBoqItems), 'BOQ Items saved successfully');
    }

    /**
     * Display the specified ContractBoqItems.
     * GET|HEAD /contractBoqItems/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractBoqItems $contractBoqItems */
        $contractBoqItems = $this->contractBoqItemsRepository->find($id);

        if (empty($contractBoqItems)) {
            return $this->sendError('Contract Boq Items not found');
        }

        return $this->sendResponse(new ContractBoqItemsResource($contractBoqItems), 'BOQ Items retrieved successfully');
    }

    /**
     * Update the specified ContractBoqItems in storage.
     * PUT/PATCH /contractBoqItems/{id}
     *
     * @param int $id
     * @param UpdateContractBoqItemsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractBoqItemsAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractBoqItems $contractBoqItems */
        $contractBoqItems = $this->contractBoqItemsRepository->find($id);

        if (empty($contractBoqItems)) {
            return $this->sendError('Contract Boq Items not found');
        }

        $contractBoqItems = $this->contractBoqItemsRepository->update($input, $id);

        return $this->sendResponse(new ContractBoqItemsResource($contractBoqItems), 'BOQ Items updated successfully');
    }

    /**
     * Remove the specified ContractBoqItems from storage.
     * DELETE /contractBoqItems/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contractResult = ContractBoqItems::select('id')->where('uuid', $id)->first();
        $contractBoqItems = $this->contractBoqItemsRepository->find($contractResult->id);

        if (empty($contractBoqItems)) {
            return $this->sendError('Boq Item not found');
        }

        $contractBoqItems->delete();

        return $this->sendSuccess('BOQ Item deleted successfully');
    }

    public function getBoqItemsByCompany(Request $request)
    {
        return $this->contractBoqItemsRepository->getBoqItems($request);
    }

    public function updateBoqItemsQty(UpdateContractBoqItemsAPIRequest $request)
    {
        $input = $request->all();
        $contractBoqItems = $this->contractBoqItemsRepository->findByUuid($input['uuid']);

        if (empty($contractBoqItems)) {
            return $this->sendError('BOQ Item not found');
        }

        if($input['type'] == 'minQty') {
            $inputArr = ['minQty' => $input['qty']];
        } elseif($input['type'] == 'maxQty') {
            $inputArr = ['maxQty' => $input['qty']];
        } else {
            $inputArr = ['qty' => $input['qty']];
        }
        $inputArr['updated_by'] = General::currentEmployeeId();
        $inputArr['updated_at'] = Carbon::now();
        $contractBoqItems = $this->contractBoqItemsRepository->update($inputArr, $contractBoqItems->id);

        return $this->sendResponse(new ContractBoqItemsResource($contractBoqItems), 'BOQ Item updated successfully');
    }

    public function copyBoqItemsQty(UpdateContractBoqItemsAPIRequest $request)
    {
        $input = $request->all();
        $contractBoqItems = $this->contractBoqItemsRepository->findByUuid($input['uuid']);

        if (empty($contractBoqItems)) {
            return $this->sendError('BOQ Items not found');
        }

        $getValidRangeOfIdsToUpdate = $this->contractBoqItemsRepository->copyIdsRange($contractBoqItems);

        if($input['type'] == 'minQty') {
            $inputArr = ['minQty' => $input['qty']];
        } elseif($input['type'] == 'maxQty') {
            $inputArr = ['maxQty' => $input['qty']];
        } else {
            $inputArr = ['qty' => $input['qty']];
        }
        $inputArr['updated_by'] = General::currentEmployeeId();
        $inputArr['updated_at'] = Carbon::now();

        return $this->contractBoqItemsRepository->copySameQty($getValidRangeOfIdsToUpdate, $inputArr);
    }

    public function addTenderBoqItems(Request $request): array
    {
        $input = $request->all();
        $contractResult = ContractMaster::select('id')->where('uuid', $input['uuid'])->first();
        $insertArray = [
            'uuid' => bin2hex(random_bytes(16)),
            'contractId' => $contractResult->id,
            'itemId' => $input['itemId'],
            'description' => $input['description'],
            'created_by' => General::currentEmployeeId(),
            'created_at' => Carbon::now(),
            'companyId' => $input['companyId']
        ];
        DB::beginTransaction();
        try {
            $this->contractBoqItemsRepository->create($insertArray);
            DB::commit();
            return ['status' => true, 'message' => 'BOQ Item added successfully'];
        } catch (\Exception $ex) {
                DB::rollBack();
                return ['status' => false, 'message' => $ex->getMessage()];
            }
    }

    public function exportBoqItems(Request $request)
    {
        $input = $request->all();
        $type = $request->type;
        $disk = $request->disk;
        $docName = $request->doc_name;
        $contractBoqItems = $this->contractBoqItemsRepository->exportBoqItemsReport($request);
        $companyMaster = Company::find(isset($input['companySystemID']) ? $input['companySystemID'] : null);
        $companyCode = isset($companyMaster->CompanyID) ? $companyMaster->CompanyID : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($contractBoqItems);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '') {
            return $this->sendError('unable_to_export_excel');
        } else {
            return $this->sendResponse($basePath, trans('success_export'));
        }
    }
}
