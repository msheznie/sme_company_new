<?php

namespace App\Services;

use App\Helpers\General;
use App\Models\CMContractReminderScenario;
use App\Models\CMContractTypes;
use App\Models\CMContractTypeSections;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractBoqItems;
use App\Models\ContractDeliverables;
use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Exceptions\CommonException;
use App\Models\ContractMilestone;
use App\Models\ContractMilestonePenaltyDetail;
use App\Models\ContractMilestonePenaltyMaster;
use App\Models\ContractMilestoneRetention;
use App\Models\ContractOverallRetention;
use App\Models\ContractPaymentTerms;
use App\Models\ContractSettingDetail;
use App\Models\ContractUserAssign;
use App\Models\ContractUsers;
use App\Models\MilestonePaymentSchedules;
use App\Models\PeriodicBillings;
use App\Models\TimeMaterialConsumption;
use App\Repositories\ContractBoqItemsRepository;
use App\Repositories\ContractDocumentRepository;
use App\Repositories\ContractMasterRepository;
use App\Models\ContractMaster;
use App\Models\CurrencyMaster;
use App\Models\Company;
use App\Repositories\ContractMilestonePenaltyDetailRepository;
use App\Repositories\ContractOverallPenaltyRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ContractMasterService
{
    protected $contractMasterRepository;
    protected $contractBoqItemsRepository;
    protected $contractMilestonePenaltyDetailRepository;
    protected $contractOverallPenaltyRepository;
    protected $contractDocumentRepository;

    public function __construct(ContractMasterRepository $contractMasterRepository,
                                ContractBoqItemsRepository $contractBoqItemsRepository,
    ContractMilestonePenaltyDetailRepository $contractMilestonePenaltyDetailRepository,
    ContractOverallPenaltyRepository $contractOverallPenaltyRepository,
    ContractDocumentRepository $contractDocumentRepository)
    {
        $this->contractMasterRepository = $contractMasterRepository;
        $this->ContractBoqItemsRepository = $contractBoqItemsRepository;
        $this->contractMilestonePenaltyDetailRepository = $contractMilestonePenaltyDetailRepository;
        $this->contractOverallPenaltyRepository = $contractOverallPenaltyRepository;
        $this->contractDocumentRepository = $contractDocumentRepository;
    }

    public function getContractViewData($contractUUid, $selectedCompanyID, $historyUuid)
    {
        $contractMaster = ContractMaster::select('id', 'uuid', 'contractCode', 'title', 'description', 'contractType',
                'counterParty', 'counterPartyName', 'referenceCode', 'contractOwner', 'contractAmount', 'startDate',
                'endDate', 'agreementSignDate', 'contractTermPeriod', 'contractRenewalDate',
                'contractExtensionDate', 'contractTerminateDate', 'contractRevisionDate', 'primaryCounterParty',
                'primaryEmail', 'primaryPhoneNumber', 'secondaryCounterParty', 'secondaryEmail', 'secondaryPhoneNumber',
                'tender_id', 'effective_date'
            )
            ->with([
                'contractTypes' => function ($q)
                {
                    $q->select('contract_typeId', 'cm_type_name');
                }, 'contractOwners' => function ($q)
                {
                    $q->select('id', 'contractUserCode', 'contractUserName');
                }, 'counterParties' => function ($q)
                {
                    $q->select('cmCounterParty_id', 'cmCounterParty_name');
                }, 'contractUsers' => function ($q)
                {
                    $q->select('id', 'contractUserCode', 'contractUserName');
                }, 'tenderMaster' => function ($q)
                {
                    $q->select('id', 'title');
                }
            ])
            ->where('uuid', $contractUUid)
            ->where('companySystemID', $selectedCompanyID)
            ->first();
        $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = CurrencyMaster::getCurrencyCode($currencyId);
        $history = ContractHistory::getContractHistory($historyUuid, $selectedCompanyID);
        $milestones = ContractMilestone::getContractMilestone($contractMaster['id'], $selectedCompanyID);
        $deliverables = ContractDeliverables::getDeliverables($contractMaster['id'], $selectedCompanyID, 0);
        $assignedUsers = ContractUserAssign::getAssignedUsers($selectedCompanyID, $contractUUid)->get();
        $milestonePayment = MilestonePaymentSchedules::milestonePaymentSchedules(
            null, $selectedCompanyID, $contractUUid)->get();
        $timeAndMaterial = TimeMaterialConsumption::getAllTimeMaterialConsumption($contractMaster['id']);
        $periodicBilling = PeriodicBillings::getContractPeriodicBilling($contractMaster['id']);
        $boqItems = $this->ContractBoqItemsRepository->getBoqItems($selectedCompanyID, $contractUUid, false, 0);
        $overallRetention = ContractOverallRetention::getContractOverall($contractMaster['id'],$selectedCompanyID);
        $milestoneRetention = ContractMilestoneRetention::ContractMilestoneRetention(
            $selectedCompanyID, $contractMaster['id'])->get();
        $milestonePenaltyMaster = ContractMilestonePenaltyMaster::getMilestonePenaltyMaster(
            $contractMaster['id'], $selectedCompanyID);
        $milestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepository->getMilestonePenaltyDetails(
            null, $contractUUid, $selectedCompanyID);
        $overallPenalty = $this->contractOverallPenaltyRepository->getOverallPenaltyData(
            $contractUUid, $selectedCompanyID);
        $sectionData = $this->contractMasterRepository->getContractTypeSectionData($contractUUid);
        $confirmationData = $this->contractMasterRepository->getContractConfirmationData($contractUUid);
        $paymentTerms = ContractPaymentTerms::getContractPaymentTerms($contractMaster['id'], $selectedCompanyID);
        $configuration = CMContractReminderScenario::getContractExpiryData($contractMaster['id'], $selectedCompanyID);
        $activeMilestonePS = ContractSettingDetail::getActiveContractPaymentSchedule($contractMaster['id']);
        $activePenalty = ContractSettingDetail::getActiveContractPenalty($contractMaster['id']);
        $activeRetention = ContractSettingDetail::getActiveContractRetention($contractMaster['id']);
        $contractDocuments = ContractDocument::contractDocuments($selectedCompanyID, $contractMaster['id'])->get();
        $contractAdditionalDocuments = ContractAdditionalDocuments::additionalDocumentList(
            $selectedCompanyID, $contractMaster['id'])->get();


        return [
            'viewData' => $contractMaster,
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces,
            'history' => $history,
            'milestones' => $milestones,
            'deliverables' => $deliverables,
            'assignedUsers' => $assignedUsers,
            'milestonePayment' => $milestonePayment,
            'timeAndMaterial' => $timeAndMaterial,
            'periodicBilling' => $periodicBilling,
            'boqItems' => $boqItems,
            'overallRetention' => $overallRetention,
            'milestoneRetention' => $milestoneRetention,
            'milestonePenaltyMaster' => $milestonePenaltyMaster,
            'milestonePenaltyDetail' => $milestonePenaltyDetail,
            'overallPenalty' => $overallPenalty,
            'sectionData' => $sectionData,
            'confirmationData' => $confirmationData,
            'paymentTerms' => $paymentTerms,
            'configuration' => $configuration,
            'contractDocuments' => $contractDocuments,
            'contractAdditionalDocuments' => $contractAdditionalDocuments,
            'activeMilestonePS' => $activeMilestonePS['sectionDetailId'] ?? 0,
            'activePenalty' => $activePenalty['sectionDetailId'] ?? 0,
            'activeRetention' => $activeRetention['sectionDetailId'] ?? 0,
        ];
    }
    public function disableAmountField($contractID)
    {
        return ContractMaster::where('id', $contractID)
            ->where(function ($q)
            {
                $q->whereHas('contractMilestonePaymentSchedule')
                    ->orWhereHas('contractRetention');
            })
            ->exists();
    }

    public function disableContractTypeField($contractTypeUUId, $companySystemID, $contractId)
    {
        $contractType = CMContractTypes::getContractType($contractTypeUUId);
        $sections = CMContractTypeSections::getContractTypeSections($contractType->contract_typeId, $companySystemID);
        $cmSectionIds = $sections->pluck('cmSection_id')->toArray();

        foreach ($cmSectionIds as $sectionId)
        {
            if ($this->checkRecordExistence($sectionId, $contractId, $companySystemID))
            {
                return true;
            }
        }

        return false;
    }

    public function disableTenderReferenceField($companySystemID, $contractId)
    {
        if (ContractBoqItems::checkBoqAddedFormTender($contractId, $companySystemID))
        {
            return true;
        }

        return false;
    }

    private function checkRecordExistence($sectionId, $contractId, $companySystemID)
    {
        switch ($sectionId)
        {
            case 1:
                $result = ContractBoqItems::checkBoqAddedForContract($contractId, $companySystemID);
                break;
            case 2:
                $hasMilestone = ContractMilestone::checkMilestoneAddedForContract($contractId, $companySystemID);
                $hasDeliverables = ContractDeliverables::checkDeliverableAddedForContract(
                    $contractId,
                    $companySystemID
                );
                $result = $hasMilestone || $hasDeliverables;
                break;
            case 3:
                $milestonePayment = MilestonePaymentSchedules::existMilestonePayment($contractId, $companySystemID);
                $result = $milestonePayment !== null;
                break;
            case 4:
                $result = ContractMilestoneRetention::checkRetentionAddedForContract($contractId, $companySystemID);
                break;
            default:
                $result = false;
        }

        return $result;
    }
    public function getContractDetailsReport($request)
    {
        $input  = $request->all();
        $searchKeyword = $request->input('search.value');
        $companyId =  $input['companyId'];
        $filter = $input['filter'] ?? null;
        $languages =  ContractMaster::contractDetailReport($searchKeyword, $companyId, $filter);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }
    public function getContractReportFormData($request)
    {
        $selectedCompanyID = $request->input('selectedCompanyID');
        $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        return [
            'decimalPlace' => $decimalPlaces,
            'counterPartiesName' => ContractManagementUtils::counterPartyNames(1),
            'contractTypes' => ContractManagementUtils::getContractTypes()
        ];
    }
    public function exportContractDetailsReport($request)
    {
        $input  = $request->all();
        $search = false;
        $selectedCompanyID =  $input['selectedCompanyID'];
        $filter = $input['filter'] ?? null;
        $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);

        $contractDetails = ContractMaster::contractDetailReport($search, $selectedCompanyID, $filter)->get();
        $data[0][trans('common.contract_code')] = trans('common.contract_code');
        $data[0][trans('common.title')] = trans('common.title');
        $data[0][trans('common.counter_party_name')] = trans('common.counter_party_name');
        $data[0][trans('common.contract_type')] = trans('common.contract_type');
        $data[0][trans('common.contract_start_date')] = trans('common.contract_start_date');
        $data[0][trans('common.contract_end_date')] = trans('common.contract_end_date');
        $data[0][trans('common.amount')] = trans('common.amount');
        if ($contractDetails)
        {
            $count = 1;
            foreach ($contractDetails as $value)
            {
                $contractPartyName = $value->counterParty == 1
                    ? ($value['contractUsers']['contractSupplierUser']['supplierName'] ?? '-')
                    : ($value['contractUsers']['contractCustomerUser']['CustomerName'] ?? '-');

                $data[$count] = [
                    trans('common.contract_code') => $value['contractCode'] ?? '-',
                    trans('common.title') => $value['title'] ?? '-',
                    trans('common.counter_party_name') => $contractPartyName,
                    trans('common.contract_type') => $value['contractTypes']['cm_type_name'] ?? '-',
                    trans('common.contract_start_date') => isset($value['startDate']) ?
                        preg_replace('/^=/', '-', Carbon::parse($value['startDate'])->toDateString()) : '-',
                    trans('common.contract_end_date') => isset($value['endDate']) ?
                        preg_replace('/^=/', '-', Carbon::parse($value['endDate'])->toDateString()) : '-',
                    trans('common.amount') => isset($value['contractAmount']) ?
                        number_format((float)preg_replace('/^=/', '-', $value['contractAmount']),
                            $decimalPlaces, '.', '') : '-',
                ];

                $count++;
            }
        }
        return $data;
    }

    public static function generateContractCode($companySystemID)
    {
        $lastSerialNumber = ContractMaster::where('companySystemID', $companySystemID)
            ->max('serial_no');

        $lastSerialNumber = $lastSerialNumber ? intval($lastSerialNumber) + 1 : 1;
        $codePattern = GeneralService::getDocumentCodePattern($companySystemID, 1);
        if($codePattern)
        {
            $companyId = General::getCompanyById($companySystemID);
            $contractCode = self::generateDocumentCode($codePattern, $companyId, $lastSerialNumber);
        } else
        {
            $contractCode =  ContractManagementUtils::generateCode($lastSerialNumber,'CO',4);
        }

        return ['contractCode' => $contractCode, 'lastSerialNumber' => $lastSerialNumber];

    }

    public function generateDocumentCode($pattern, $companyId, $serialNumber = 1)
    {
        $year = Carbon::now()->year;
        $serialNumberFormatted = str_pad($serialNumber, 4, '0', STR_PAD_LEFT);

        $replacements = [
            'Company ID' => $companyId,
            'Year' => $year
        ];

        $code = preg_replace_callback('/#([^#]+)#/', function ($matches) use ($replacements)
        {
            $placeholder = $matches[1];
            return $placeholder[0] === '_'
                ? strtoupper(substr($placeholder, 1))
                : ($replacements[$placeholder] ?? '');
        }, $pattern);

        if (str_contains($code, '#SN'))
        {
            $code = str_replace('#SN', $serialNumberFormatted, $code);
        }

        return $code;
    }

    public function getSupplierContactDetails($request)
    {
        $uuid = $request->input('supUuid');

        $contactDetails = ContractUsers::getSupplierContactDetails($uuid);
        $contactDetailsList = $contactDetails->contractSupplierUser->supplierContactDetails ?? collect([]);
        $isDefaultDetail = $contactDetailsList->firstWhere('isDefault', -1);
        $notDefaultDetails = $contactDetailsList->filter(function ($detail)
        {
            return $detail->isDefault === 0;
        });

        $primaryDetail = $isDefaultDetail ?? $notDefaultDetails->first() ?? null;
        $secondaryDetail = $notDefaultDetails->skip(1)->first() ?? null;

        if ($isDefaultDetail)
        {
            if ($notDefaultDetails->count() === 0)
            {
                $secondaryDetail = null;
            }
            if ($notDefaultDetails->count() > 0)
            {
                $secondaryDetail = $notDefaultDetails->first();
            }
        }

        return [
            'primaryDetails' => $primaryDetail ?? null,
            'secondaryDetails' => $secondaryDetail ?? null,
        ];
    }
    public static function updateContractMaster($id, $field)
    {
        return ContractMaster::where('id', $id)->update($field);
    }

    public static function getAddendumCode($contractID, $category, $contractMasterCode)
    {
        $contractAddendumCount = ContractHistory::getContractHistoryCategoryWise($contractID, $category)->count();
        $addCode = ContractManagementUtils::generateCode($contractAddendumCount + 1, 'Add', 2);
        return $contractMasterCode . ' | ' . $addCode;
    }
    public static function getContractLastSerialNumber($companySystemID)
    {
        $lastSerialNumber = ContractMaster::where('companySystemID', $companySystemID)
            ->max('serial_no');
        return $lastSerialNumber ? $lastSerialNumber + 1 : 1;
    }
}
