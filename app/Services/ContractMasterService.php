<?php

namespace App\Services;

use App\Models\ContractHistory;
use App\Exceptions\CommonException;
use App\Repositories\ContractMasterRepository;
use App\Models\ContractMaster;
use App\Models\CurrencyMaster;
use App\Models\Company;
use App\Utilities\ContractManagementUtils;

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
}
