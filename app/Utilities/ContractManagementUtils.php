<?php

namespace App\Utilities;

use App\Helper\General;
use App\Models\CMContractSectionsMaster;
use App\Models\CMContractsMaster;
use App\Models\CMCounterPartiesMaster;
use App\Models\CMIntentsMaster;
use App\Models\CMPartiesMaster;
use App\Models\Company;

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
}