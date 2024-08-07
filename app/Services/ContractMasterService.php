<?php

namespace App\Services;

use App\Models\CMContractTypes;
use App\Models\CMContractTypeSections;
use App\Models\ContractBoqItems;
use App\Models\ContractDeliverables;
use App\Models\ContractHistory;
use App\Exceptions\CommonException;
use App\Models\ContractMilestone;
use App\Models\ContractMilestoneRetention;
use App\Models\MilestonePaymentSchedules;
use App\Repositories\ContractMasterRepository;
use App\Models\ContractMaster;
use App\Models\CurrencyMaster;
use App\Models\Company;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class ContractMasterService
{
    protected $contractMasterRepository;

    public function __construct(ContractMasterRepository $contractMasterRepository)
    {
        $this->contractMasterRepository = $contractMasterRepository;
    }

    public function getContractViewData($contractUUid, $selectedCompanyID, $historyUuid)
    {
        $contractMaster = ContractMaster::select('id', 'uuid', 'contractCode', 'title', 'description', 'contractType',
                'counterParty', 'counterPartyName', 'referenceCode', 'contractOwner', 'contractAmount', 'startDate',
                'endDate', 'agreementSignDate', 'contractTermPeriod', 'contractRenewalDate',
                'contractExtensionDate', 'contractTerminateDate', 'contractRevisionDate', 'primaryCounterParty',
                'primaryEmail', 'primaryPhoneNumber', 'secondaryCounterParty', 'secondaryEmail', 'secondaryPhoneNumber'
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
                }
            ])
            ->where('uuid', $contractUUid)
            ->where('companySystemID', $selectedCompanyID)
            ->first();
        $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = CurrencyMaster::getCurrencyCode($currencyId);
        $history = ContractHistory::getContractHistory($historyUuid, $selectedCompanyID);

        return [
            'viewData' => $contractMaster,
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces,
            'history' => $history
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
        $contractTypes = ContractManagementUtils::getContractTypes();
        $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        return [
            'decimalPlace' => $decimalPlaces,
            'contractTypes' => $contractTypes
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

        $contractCode =  ContractManagementUtils::generateCode($lastSerialNumber,'CO',4);

        return ['contractCode' => $contractCode, 'lastSerialNumber' => $lastSerialNumber];

    }
}
