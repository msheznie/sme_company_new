<?php

namespace App\Utilities;

use App\Helper\General;
use App\Models\BillingFrequencies;
use App\Models\CMContractSectionsMaster;
use App\Models\CMContractsMaster;
use App\Models\CMContractTypes;
use App\Models\CMCounterPartiesMaster;
use App\Models\CMIntentsMaster;
use App\Models\CMPartiesMaster;
use App\Models\Company;
use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\ContractUserGroup;
use App\Models\ContractUsers;
use App\Models\DocumentMaster;
use App\Models\DocumentReceivedFormat;
use App\Models\Unit;
use Carbon\Carbon;

class ContractManagementUtils
{
    public static function getContractsMasters()
    {
        return CMContractsMaster::select('cmMaster_id', 'cmMaster_description', 'ctm_active')
            ->where('ctm_active', 1)
            ->get();
    }

    public static function getIntentMasters()
    {
        return CMIntentsMaster::select('cmIntent_id', 'cmIntent_detail', 'cim_active')->where('cim_active', 1)->get();
    }

    public static function getPartiesMasters()
    {
        return CMPartiesMaster::select('cmParty_id', 'cmParty_name', 'cpm_active')->where('cpm_active', 1)->get();
    }

    public static function getCounterParties()
    {
        return CMCounterPartiesMaster::select('cmCounterParty_id', 'cmCounterParty_name', 'cpt_active')
            ->where('cpt_active', 1)
            ->get();
    }

    public static function getContractSetions()
    {
        return CMContractSectionsMaster::select('cmSection_id', 'cmSection_detail', 'csm_active')
            ->where('csm_active', 1)
            ->get();
    }

    public static function getAllContractSetions()
    {
        return CMContractSectionsMaster::select('cmSection_id', 'cmSection_detail', 'csm_active')->get();
    }

    public static function getContractDefaultUserGroup($request)
    {
        $input = $request->all();
        return ContractUserGroup::where('isDefault', 1)
            ->where('status',1)
            ->where('companySystemID', $input['selectedCompanyID'])
            ->count();
    }


    public static function getStatusDrop()
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

    public static function getCompanyCurrency($companySystemID)
    {
        return  Company::with(['reporting_currency'])->where('companySystemID', $companySystemID)->first();
    }

    public static function getContractTypes()
    {
        return CMContractTypes::select('uuid', 'cm_type_name', 'ct_active')->where('ct_active', 1)->get();
    }

    public static function counterPartyNames($counterPartyId)
    {

        $users = ContractUsers::where('contractUserType', $counterPartyId)
            ->when($counterPartyId == 1, function ($q)
            {
                $q->with([
                    'contractSupplierUser' => function ($q)
                    {
                        $q->selectRaw("supplierCodeSystem, CONCAT(primarySupplierCode, ' | ', supplierName) as name");
                    }
                ]);
            })
            ->when($counterPartyId == 2, function ($q)
            {
                $q->with([
                    'contractCustomerUser' => function ($q)
                    {
                        $q->selectRaw("customerCodeSystem, CONCAT(CutomerCode, ' | ', CustomerName) as name");
                    }
                ]);
            })
            ->when($counterPartyId == 3, function ($q)
            {
                $q->with([
                    'contractInternalUser' => function ($q)
                    {
                        $q->selectRaw("employeeSystemID, CONCAT(empID, ' | ', empName) as name");
                    }
                ]);
            })
            ->get();

            $supplier = array();

            foreach ($users as $user)
            {
                $uuid = $user->uuid;
                $name = '';

                if($counterPartyId == 1)
                {
                    $name = $user['contractSupplierUser']['name'];
                } elseif($counterPartyId == 2)
                {
                    $name = $user['contractCustomerUser']['name'];
                } else
                {
                    $name = $user['contractInternalUser']['name'];
                }

                $supplier[] = [
                    "uuid" => $uuid,
                    'name' => $name
                ];

            }
            return $supplier;
    }

    public static function getCounterParty()
    {
        return CMCounterPartiesMaster::select('cmCounterParty_id', 'cmCounterParty_name')
            ->where('cpt_active', 1)->get();
    }

    public static function getContractMilestones($contractId, $companySystemID)
    {
        return ContractMilestone::select('uuid', 'title')
            ->where('contractID', $contractId)
            ->where('companySystemID', $companySystemID)
            ->get();
    }

    public static function getMilestonesWithAmount($contractId, $companySystemID)
    {
        return ContractMilestone::with('milestonePaymentSchedules')
            ->where('contractID', $contractId)
            ->where('companySystemID', $companySystemID)
            ->has('milestonePaymentSchedules')
            ->get();
    }

    public static function getDocumentTypeMasters($companySystemID)
    {
        return DocumentMaster::select('uuid', 'documentType')
            ->where([
                'status' => 1,
                'companySystemID' => $companySystemID
            ])->get();
    }

    public static function checkContractExist($contractUuid, $companySystemID)
    {
        return ContractMaster::where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->with([
                "contractTypes" => function ($q)
                {
                    $q->select('contract_typeId', 'uuid', 'cm_type_name', 'cmPartyA_id', 'cmPartyB_id');
                    $q->with([
                        'partyA' => function ($q)
                        {
                            $q->select('cmParty_id', 'cmParty_name');
                        },
                        'partyB' => function ($q)
                        {
                            $q->select('cmParty_id', 'cmParty_name');
                        }
                    ]);
                },
                "createdUser" => function ($q)
                {
                    $q->select('employeeSystemID', 'empName');
                },
            ])
            ->first();
    }
    public static function getDocumentReceivedFormat()
    {
        return DocumentReceivedFormat::select('id', 'description')->get();
    }

    public static function generateUuid($length=16) : string
    {
        return bin2hex(random_bytes($length));
    }

    public static function generateCode($lastSerialNumber, $documentCode, $length=4) : string
    {
        return $documentCode . str_pad($lastSerialNumber, $length, '0', STR_PAD_LEFT);
    }

    public static function convertDate($date,$isTimeFormat=false)
    {
        if ($isTimeFormat)
        {
            $formattedDate = Carbon::parse($date)->setTime(
                Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second
            );

        } else
        {
            $formattedDate = Carbon::parse($date);
        }

        return $formattedDate;
    }

    public static function getBillingFrequencies()
    {
        return BillingFrequencies::select('id', 'description')
            ->get();
    }

    public static function getContractHistoryData($uuid)
    {
        return ContractHistory::Where('uuid', $uuid)
            ->first();
    }
    public static function getPaymentScheduleMilestone($contractID, $companySystemID, $editMilestoneID = null)
    {
        return ContractMilestone::select('uuid', 'title')
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->where(function ($query) use ($contractID, $editMilestoneID)
            {
                $query->whereDoesntHave('checkMilestoneInPayment', function ($q) use ($contractID)
                {
                    $q->where('contract_id', $contractID);
                });
                if ($editMilestoneID)
                {
                    $query->orWhereHas('checkMilestoneInPayment', function ($q) use ($contractID, $editMilestoneID)
                    {
                        $q->where('contract_id', $contractID);
                        $q->where('uuid', $editMilestoneID);
                    });
                }
            })
            ->get();
    }
    public static function getUomList()
    {
        return Unit::select('UnitID', 'UnitShortCode', 'UnitDes')
            ->where('is_active', 1)
            ->where('createdFrom', 0)
            ->get();
    }
    public static function checkContractMilestoneExists($contractID)
    {
        return ContractMilestone::where('contractID', $contractID)->exists();
    }
}
