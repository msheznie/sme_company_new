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
use Illuminate\Support\Facades\Log;

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
                'endDate', 'agreementSignDate', 'notifyDays', 'contractTermPeriod', 'contractRenewalDate',
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

}
