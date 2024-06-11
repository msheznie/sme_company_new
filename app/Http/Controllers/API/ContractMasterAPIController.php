<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Helpers\inventory;
use App\Http\Requests\API\CreateContractMasterAPIRequest;
use App\Http\Requests\API\UpdateContractMasterAPIRequest;
use App\Http\Requests\API\RejectDocumentAPIRequest;
use App\Http\Requests\API\ApproveDocumentRequest;
use App\Http\Requests\API\ContractConfirmRequest;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\Company;
use App\Models\ContractMaster;
use App\Models\FinanceItemCategoryMaster;
use App\Models\FinanceItemCategorySub;
use App\Models\ItemAssigned;
use App\Repositories\CompanyRepository;
use App\Repositories\ContractMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMasterResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Response;
use Yajra\DataTables\DataTables;

/**
 * Class ContractMasterController
 * @package App\Http\Controllers\API
 */

class ContractMasterAPIController extends AppBaseController
{
    /** @var  ContractMasterRepository */
    private $contractMasterRepository;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    public function __construct(ContractMasterRepository $contractMasterRepo, CompanyRepository $companyRepository)
    {
        $this->contractMasterRepository = $contractMasterRepo;
        $this->companyRepository = $companyRepository;
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

        return $this->sendResponse(ContractMasterResource::collection($contractMasters),
            'Contract Masters retrieved successfully');
    }

    /**
     * Store a newly created ContractMaster in storage.
     * POST /contractMasters
     *
     * @param CreateContractMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $contractMaster = $this->contractMasterRepository->createContractMaster($input);
        return $this->sendResponse($contractMaster, 'Contract Master created successfully');
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
        $contractMaster = $this->contractMasterRepository->findByUuid($id,
            ['uuid', 'contractCode', 'title', 'contractType', 'counterParty', 'counterPartyName', 'referenceCode',
                'startDate', 'endDate', 'status', 'contractOwner', 'contractAmount', 'description',
                'primaryCounterParty', 'primaryEmail', 'primaryPhoneNumber', 'secondaryCounterParty',
                'secondaryEmail', 'secondaryPhoneNumber', 'agreementSignDate', 'startDate', 'endDate',
                'notifyDays', 'contractTermPeriod'
            ],
            [
                "contractTypes" => ['contract_typeId', 'uuid', 'cm_type_name'],
                "contractUsers" => ['id', 'uuid'],
                "contractOwners" => ['id', 'uuid']
            ]
        );
        if (empty($contractMaster))
        {
            return $this->sendError( trans('common.contract_not_found'));
        }
        $contractMaster = $this->contractMasterRepository->unsetValues($contractMaster);
        $editData = $contractMaster;
        $response = $this->contractMasterRepository->getEditFormData($editData['counterParty']);
        $response['editData'] = $editData;

        return $this->sendResponse($response, trans('common.contract_retrieved_successfully'));
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
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;

        /** @var ContractMaster $contractMaster */
        $contractMaster = $this->contractMasterRepository->findByUuid($id, ['id']);

        if (empty($contractMaster))
        {
            return $this->sendError(trans('common.contract_not_found'));
        }

        $contractMasterUpdate = $this->contractMasterRepository->updateContract(
            $input,
            $contractMaster['id'],
            $selectedCompanyID
        );

        if($contractMasterUpdate['status'])
        {
            return $this->sendResponse(['id' => $id], $contractMasterUpdate['message']);
        } else
        {
            $statusCode = $contractMasterUpdate['code'] ?? 404;
            return $this->sendError($contractMasterUpdate['message'], $statusCode);
        }
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

        if (empty($contractMaster))
        {
            return $this->sendError(trans('common.contract_not_found'));
        }

        $contractMaster->delete();

        return $this->sendSuccess(trans('common.contract_deleted_successfully'));
    }

    public function getContractMaster(Request $request)
    {
        return $this->contractMasterRepository->getContractMaster($request);
    }

    public function getAllContractMasterFilters(Request $request)
    {
        $contractMasterFilters = $this->contractMasterRepository->getAllContractMasterFilters($request);
        return $this->sendResponse($contractMasterFilters,
            trans('common.retrieved_successfully'));
    }

    public function getCounterPartyNames(Request $request)
    {
        $counterPartyNames = $this->contractMasterRepository->getCounterPartyNames($request);
        return $this->sendResponse($counterPartyNames,
            trans('common.retrieved_successfully'));
    }

    public function exportContractMaster(Request $request)
    {
        $type = $request->type;
        $disk = $request->disk;
        $docName = $request->doc_name;
        $companySystemID = $request->selectedCompanyID ?? 0;
        $contractMaster = $this->contractMasterRepository->exportContractMasterReport($request);
        $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) ?? 'common' : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($contractMaster);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '')
        {
            return $this->sendError('unable_to_export_excel');
        } else
        {
            return $this->sendResponse($basePath, trans('success_export'));
        }
    }

    public function deleteFileFromAws(Request $request)
    {
        $path = $request->path;
        $disk = $request->disk;
        DeleteFileFromS3Job::dispatch($path, $disk)->delay(now()->addMinutes(5));
    }

    public function getUserFormDataContractEdit(Request $request)
    {
        $contractType = $request->input('search');
        $fromContractType = $request->input('fromContractType');
        $value = $contractType['value'];

        $fromData = $this->contractMasterRepository->userFormData($value, $fromContractType);

        if($fromData['status'])
        {
            return $this->sendResponse($fromData['data'], $fromData['message']);
        } else
        {
            $statusCode = $fromData['code'] ?? 404;
            return $this->sendError($fromData['message'], $statusCode);
        }

    }

    public function getContractTypeSectionData(Request $request)
    {
        $contractSecs = $this->contractMasterRepository->getContractTypeSectionData($request);
        return $this->sendResponse($contractSecs, 'Retrieved successfully');
    }

    public function updateContractSettingDetails(Request $request)
    {
        $updateContractSetting = $this->contractMasterRepository->updateContractSettingDetails($request);

        if($updateContractSetting['status'])
        {
            return $this->sendResponse([], $updateContractSetting['message']);
        } else
        {
            $statusCode = $updateContractSetting['code'] ?? 404;
            return $this->sendError($updateContractSetting['message'], $statusCode);
        }
    }

    public function getActiveContractSectionDetails(Request $request)
    {
        $contractSectionDetails = $this->contractMasterRepository->getActiveContractSectionDetails($request);
        return $this->sendResponse($contractSectionDetails, 'Retrieved successfully');
    }

    public function getContractOverallRetentionData(Request $request)
    {
        $contractOverallRetentionData = $this->contractMasterRepository->getContractOverallRetentionData($request);
        return $this->sendResponse($contractOverallRetentionData, 'Overall Retention data retrieved successfully');
    }

    public function updateOverallRetention(Request $request)
    {
        $overallRetention = $this->contractMasterRepository->updateOverallRetention($request);
        if ($overallRetention['status'])
        {
            return $this->sendResponse([], $overallRetention['message']);
        }
        return $this->sendError($overallRetention['message'], $overallRetention['code'] ?? 404);

    }

    public function getItemMasterFormData(Request $request)
    {
        $itemCategory = FinanceItemCategoryMaster::all();
        $itemCategorySubArray = FinanceItemCategorySub::where('isActive',1)->get();

        $output = [
            'financeItemCategoryMaster' => $itemCategory,
            'financeItemCategorySub' => $itemCategorySubArray,
        ];

        return $this->sendResponse($output, 'Record retrieved successfully');
    }

    public function getAllAssignedItemsByCompany(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $itemMasters = $this->getAssignedItemsByCompanyQry($input);

        return DataTables::eloquent($itemMasters)
            ->addIndexColumn()
            ->with('orderCondition')
            ->addColumn('Actions', 'Actions', "Actions")
            ->addColumn('current', function ($row)
            {
                $data = array('companySystemID' => $row->companySystemID,
                    'itemCodeSystem' => $row->itemCodeSystem,
                    'wareHouseId' => null);
                $itemCurrentCostAndQty = Inventory::itemCurrentCostAndQty($data);

                return array('local' => $itemCurrentCostAndQty['wacValueLocal'],
                    'rpt' => $itemCurrentCostAndQty['wacValueReporting'],
                    'stock' => $itemCurrentCostAndQty['currentStockQty']);
            })
            ->make(true);
    }

    public function getAssignedItemsByCompanyQry($request)
    {
        $input = $request;
        $contractResult = ContractMaster::select('id')->where('uuid', $input['uuid'] )->first();
        $contractId = $contractResult->id;
        $companyId = $input['companyId'];

        $isGroup = $this->companyRepository->checkIsCompanyGroup($companyId);

        if ($isGroup)
        {
            $childCompanies = $this->companyRepository->getGroupCompany($companyId);
        } else
        {
            $childCompanies = [$companyId];
        }

        $itemMasters = ItemAssigned::with(['unit', 'financeMainCategory', 'financeSubCategory'])
            ->whereIn('companySystemID', $childCompanies)
            ->where('financeCategoryMaster', 1)
            ->whereDoesntHave('contractBoqItems', function ($query) use ($contractId)
            {
                $query->where('contractId', $contractId);
            })
            ->orderBy('idItemAssigned', 'desc');

        if (array_key_exists('financeCategoryMaster', $input)
            && $input['financeCategoryMaster'] != null
            && $input['financeCategoryMaster']['value'] > 0 && !is_null($input['financeCategoryMaster']['value'])
        )
        {
            $itemMasters->where('financeCategoryMaster', $input['financeCategoryMaster']['value']);
        }

        if (array_key_exists('financeCategorySub', $input)
            && $input['financeCategorySub'] != null
            && $input['financeCategorySub']['value'] > 0 && !is_null($input['financeCategorySub']['value'])
        )
        {
            $itemMasters->where('financeCategorySub', $input['financeCategorySub']['value']);
        }

        $search = $input['search']['value'];
        if ($search)
        {
            $itemMasters = $itemMasters->where(function ($query) use ($search)
            {
                $query->where('itemPrimaryCode', 'LIKE', "%{$search}%")
                    ->orWhere('secondaryItemCode', 'LIKE', "%{$search}%")
                    ->orWhere('itemDescription', 'LIKE', "%{$search}%");
            });
        }
        return $itemMasters;
    }

    public function getSubcategoriesByMainCategory(Request $request)
    {
        if($request->get('itemCategoryID') != 0)
        {
            return FinanceItemCategorySub::where('itemCategoryID',$request->get('itemCategoryID'))->where('isActive',1)
                ->get();
        } else
        {
           return FinanceItemCategorySub::where('isActive',1)->get();
        }
    }

    public function getContractConfirmationData(Request $request)
    {
        return $this->contractMasterRepository->getContractConfirmationData($request);
    }

    public function confirmContract(ContractConfirmRequest $request)
    {
        try
        {
            $this->contractMasterRepository->confirmContract($request);
            return $this->sendResponse([], trans('common.document_confirmed_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
    public function getContractApprovals(Request $request)
    {
        return $this->contractMasterRepository->getContractApprovals($request);
    }
    public function approveContract(ApproveDocumentRequest $request)
    {
        try
        {
            $this->contractMasterRepository->approveContract($request);
            return $this->sendResponse([], trans('common.document_approved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
    }
    public function rejectContract(RejectDocumentAPIRequest $request)
    {
        try
        {
            $this->contractMasterRepository->rejectContract($request);
            return $this->sendResponse([], trans('common.document_successfully_rejected'));
        }
        catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

}
