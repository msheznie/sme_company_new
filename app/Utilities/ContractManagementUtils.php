<?php

namespace App\Utilities;

use App\Helper\General;
use App\Models\CMContractSectionsMaster;
use App\Models\CMContractsMaster;
use App\Models\CMContractTypes;
use App\Models\CMCounterPartiesMaster;
use App\Models\CMIntentsMaster;
use App\Models\CMPartiesMaster;
use App\Models\Company;
use App\Models\ContractUsers;

class ContractManagementUtils
{
    static function getContractsMasters()
    {
        return CMContractsMaster::select('cmMaster_id', 'cmMaster_description', 'ctm_active')->where('ctm_active', 1)->get();
    }

    static function getIntentMasters()
    {
        return CMIntentsMaster::select('cmIntent_id', 'cmIntent_detail', 'cim_active')->where('cim_active', 1)->get();
    }

    static function getPartiesMasters()
    {
        return CMPartiesMaster::select('cmParty_id', 'cmParty_name', 'cpm_active')->where('cpm_active', 1)->get();
    }

    static function getCounterParties()
    {
        return CMCounterPartiesMaster::select('cmCounterParty_id', 'cmCounterParty_name', 'cpt_active')->where('cpt_active', 1)->get();
    }

    static function getContractSetions()
    {
        return CMContractSectionsMaster::select('cmSection_id', 'cmSection_detail', 'csm_active')->where('csm_active', 1)->get();
    }

    static function getStatusDrop()
    {
        $result[0] = [
            'id' => 1,
            'value' => 'Active'
        ];
        $result[1] = [
            'id' => 0,
            'value' => 'In Active'
        ];
        return $result;
    }

    static function getCompanyCurrency($companySystemID)
    {
        return  Company::with(['reporting_currency'])->where('companySystemID', $companySystemID)->first();
    }

    static function getContractTypes()
    {
        return CMContractTypes::select('uuid', 'cm_type_name', 'ct_active')->where('ct_active', 1)->get();
    }

    static function counterPartyNames($counterPartyId)
    {

        $users = ContractUsers::where(function ($query) use ($counterPartyId) {
                $query->when($counterPartyId == 1, function ($q) {
                    $q->where('contractUserType', 2);
                })
                    ->when($counterPartyId == 2, function ($q) {
                        $q->where('contractUserType', 3);
                    });
            })
            ->when($counterPartyId == 1, function ($q) {
                $q->with([
                    'contractSupplierUser' => function ($q) {
                        $q->select('supplierCodeSystem','primarySupplierCode', 'supplierName');
                    }
                ]);
            })
            ->when($counterPartyId == 2, function ($q) {
                $q->with([
                    'contractCustomerUser' => function ($q) {
                        $q->select('customerCodeSystem','CutomerCode', 'CustomerName');
                    }
                ]);
            })
            ->get();

            $supplier = array();

            foreach ($users as $user){
                $uuid = $user->uuid;
                $name = '';

                if($counterPartyId == 1) {
                    $name = $user['contractSupplierUser']['supplierName'];
                }

                if($counterPartyId == 2) {
                    $name = $user['contractCustomerUser']['CustomerName'];
                }

                $supplier[] = [
                    "uuid" => $uuid,
                    'name' => $name
                ];

            }
            return $supplier;
    }
}
