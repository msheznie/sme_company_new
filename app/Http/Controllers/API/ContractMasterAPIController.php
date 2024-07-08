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
use App\Models\CMContractBoqItemsAmd;
use App\Models\Company;
use App\Models\ContractBoqItems;
use App\Models\ContractMaster;
use App\Models\ContractSettingDetail;
use App\Models\ContractSettingMaster;
use App\Models\ContractUsers;
use App\Models\FinanceItemCategoryMaster;
use App\Models\FinanceItemCategorySub;
use App\Models\ItemAssigned;
use App\Repositories\CompanyRepository;
use App\Repositories\ContractHistoryRepository;
use App\Repositories\ContractMasterRepository;
use App\Services\ContractMasterService;
use App\Services\ContractHistoryService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractMasterResource;
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
    private $contractMasterService;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;
    private $contractHistoryRepository;

    public function __construct(
        ContractMasterRepository $contractMasterRepo,
        CompanyRepository $companyRepository,
        ContractMasterService $contractMasterService,
        ContractHistoryRepository $contractHistoryRepository
    )
    {
        $this->contractMasterRepository = $contractMasterRepo;
        $this->companyRepository = $companyRepository;
        $this->contractMasterService = $contractMasterService;
        $this->contractHistoryRepository = $contractHistoryRepository;
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
    public function show($id,Request $request,ContractHistoryService $contractHistoryService)
    {
        $input = $request->all();
        $comapnyId = $input['selectedCompanyID'];
        $contractMaster = $this->contractMasterRepository->findByUuid($id,
            [   'id', 'uuid', 'contractCode', 'title', 'contractType', 'counterParty', 'counterPartyName',
                'referenceCode', 'startDate', 'endDate', 'status', 'contractOwner', 'contractAmount', 'description',
                'primaryCounterParty', 'primaryEmail', 'primaryPhoneNumber', 'secondaryCounterParty',
                'secondaryEmail', 'secondaryPhoneNumber', 'agreementSignDate', 'startDate', 'endDate',
                'notifyDays', 'contractTermPeriod','is_amendment','is_addendum','is_renewal','is_extension',
                'is_revision','is_termination','parent_id', 'confirmed_yn', 'approved_yn', 'refferedBackYN', 'tender_id'
            ],
            [
                "contractTypes" => ['contract_typeId', 'uuid', 'cm_type_name'],
                "contractUsers" => ['id', 'uuid'],
                "contractOwners" => ['id', 'uuid'],
                "contractMasterHistory" => ['id', 'uuid','contractCode']
            ]
        );
        if (empty($contractMaster))
        {
            return $this->sendError( trans('common.contract_not_found'));
        }

        $contractCategoryWiseData =  $contractHistoryService->getCategoryWiseContractCount($contractMaster,$comapnyId);
        if($contractCategoryWiseData)
        {
            $contractMaster['contractCategoryWiseCount'] = count($contractCategoryWiseData);
        }


        $contractMaster = $this->contractMasterRepository->unsetValues($contractMaster);
        $userUuid = ContractUsers::getContractUserIdByUuid($contractMaster['counterPartyNameUuid']);
        $editData = $contractMaster;
        $response = $this->contractMasterRepository->getEditFormData($editData['counterParty'], $userUuid);
        $contactMaster = new ContractMaster();
        $lastSerialNumber  = $contactMaster->getMaxContractId();
        $contractCode = ContractManagementUtils::generateCode($lastSerialNumber, 'CO');
        $activeMilestonePS = ContractSettingDetail::getActiveContractPaymentSchedule($contractMaster['id']);
        $response['editData'] = $editData;
        $response['newContractCode'] = $contractCode;
        $response['activeMilestonePS'] = $activeMilestonePS['sectionDetailId'] ?? 0;
        $response['boqActive'] = ContractSettingMaster::checkActiveContractSettings($contractMaster['id'], 'boq');
        $milestoneActive = ContractSettingMaster::checkActiveContractSettings($contractMaster['id'], 'milestone');
        $milestoneHasRec = ContractManagementUtils::checkContractMilestoneExists($contractMaster['id']);
        $response['milestoneActive'] = $milestoneActive && $milestoneHasRec;
        $response['disableAmount'] = $this->contractMasterService->disableAmountField($contractMaster['id']);
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
    public function update($uuid, UpdateContractMasterAPIRequest $request)
    {
        $input = $request->all();
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        $fromAmendment = $request->input('amendment');

        /** @var ContractMaster $contractMaster */

        try
        {

            if ($fromAmendment)
            {
                $contractMaster = $this->getContractHistoryId($uuid);
            } else
            {
                $contractMaster = $this->getContractMasterId($uuid);
            }

            $this->contractMasterRepository->updateContract(
                $input,
                $contractMaster['id'],
                $selectedCompanyID,
                $contractMaster['status'],
            );

           return $this->sendResponse(['id' => $uuid], trans('common.contract_updated_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
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

    public function getSupplierTenderList(Request $request)
    {
        $counterparty = $request->input('counterParty');

        $fromData = $this->contractMasterRepository->getTenderList($counterparty);

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

        $amedment = $input['amendment'];
        $contractResult = ContractMaster::select('id')->where('uuid', $input['uuid'] )->first();


        $colName = $amedment ? 'contract_history_id' : 'contractId';
        $id = $amedment ? self::getHistoryId($input['uuid']) :  $contractResult->id;
        $relationShip = $amedment ? 'contractBoqItemsAmd' : 'contractBoqItems';




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
            ->whereDoesntHave($relationShip, function ($query) use ($id , $colName)
            {
                $query->where($colName, $id);
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
    public function getContractViewData(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $historyUuid = $request->input('historyUuid') ?? null;
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        try
        {
            $response = $this->contractMasterService->getContractViewData
            ($contractUuid, $selectedCompanyID, $historyUuid);
            return $this->sendResponse($response, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    protected function getContractHistoryId($uuid)
    {
        $contractHistory = $this->contractHistoryRepository->findByUuid($uuid, ['id']);
        if (empty($contractHistory))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        return $contractHistory;
    }

    protected function getContractMasterId($uuid)
    {
        $contractMaster = $this->contractMasterRepository->findByUuid($uuid, ['id']);
        if (empty($contractMaster))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        return $contractMaster;
    }

    public function getHistoryId($id)
    {
        $data = ContractManagementUtils::getContractHistoryData($id);
        return $data;
    }

}
