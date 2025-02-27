<?php

namespace App\Utilities;

use App\Helper\General;
use App\Models\BillingFrequencies;
use App\Models\CMContractDeliverableAmd;
use App\Models\CMContractMileStoneAmd;
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
        return CMContractSectionsMaster::select('cmSection_id', 'cmSection_detail', 'csm_active')
            ->whereIn('csm_active', [0,1])
            ->get();
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

    public static function getContractTypes($companySystemID)
    {
        return CMContractTypes::select('uuid', 'cm_type_name', 'ct_active')
            ->where('companySystemID', $companySystemID)
            ->where('ct_active', 1)
            ->get();
    }

    public static function counterPartyNames($counterPartyId, $companySystemID, $isEdit = false, $uuid = null)
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
            ->when($isEdit, function ($q)
            {
                $q->where('isActive', 1);
            })
            ->when($uuid, function ($q) use ($uuid)
            {
                $q->where('uuid', $uuid);
            })
            ->where('companySystemId', $companySystemID)
            ->get();

            $supplier = array();

            foreach ($users as $user)
            {
                $uuid = $user->uuid;
                $name = '';

                if($counterPartyId == 1)
                {
                    $name = $user['contractSupplierUser']['name'] ?? null;
                } elseif($counterPartyId == 2)
                {
                    $name = $user['contractCustomerUser']['name'] ?? null;
                } else
                {
                    $name = $user['contractInternalUser']['name'] ?? null;
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
        return ContractMilestone::select('id', 'contractID', 'uuid', 'title')->with('milestonePaymentSchedules')
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
                'counterParties' => function ($q)
                {
                    $q->select('cmCounterParty_id', 'cmCounterParty_name');
                },
                'contractOwners' => function ($q)
                {
                    $q->select('id', 'contractUserName');
                },
                'contractUsers' => function ($q)
                {
                    $q->select('id', 'contractUserName');
                }
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
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    public static function getContractHistoryData($uuid)
    {
        return ContractHistory::where('uuid', $uuid)
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

    public static function getId($amendment,$uuid, $companyId)
    {
        $id = 0;
        if($amendment)
        {
            $data = self::getContractHistoryData($uuid);
            $id = $data;
        }else
        {
            $data = self::checkContractExist($uuid,$companyId);
            $id = $data->id;
        }
        return  $id;
    }

    public static function getMilestonesAmd($uuid,$milestoneUuid)
    {
        $historyData = self::getContractHistoryData($uuid);
        return CMContractMileStoneAmd::select('status','amd_id', 'uuid','id')
            ->where('contract_history_id', $historyData->id)
            ->where('uuid', $milestoneUuid)
            ->first();

    }

    public static function getDeliverableAmd($uuid,$deliverableUuid)
    {
        $historyData = self::getContractHistoryData($uuid);
        return CMContractDeliverableAmd::select('amd_id', 'uuid','id')
            ->where('contract_history_id', $historyData->id)
            ->where('uuid', $deliverableUuid)
            ->first();
    }

    public static function getContractUsers($companySystemId)
    {
        $contractUsers = ContractUsers::select
        ('uuid', 'contractUserId', 'contractUserCode', 'contractUserName', 'isActive')
            ->where('companySystemId', $companySystemId)
            ->where('isActive', 1)
            ->get();

        $supplier = array();

        foreach ($contractUsers as $user)
        {
            $uuid = $user->uuid;
            $name = $user->contractUserCode. ' | ' .$user->contractUserName;
            $supplier[] = [
                "uuid" => $uuid,
                'name' => $name
            ];
        }
        return $supplier;
    }
    public static function getContractStatus($status)
    {
        switch ($status)
        {
            case 0:
                return 'In-active';
            case -1:
                return 'Active';
            case 1:
                return 'Amended';
            case 2:
                return 'Addended';
            case 3:
                return 'Renewed';
            case 4:
                return 'Extended';
            case 5:
                return 'Revised';
            case 6:
                return 'Terminated';
            case 7:
                return 'Ended';
            default:
                return 'Ended';
        }
    }

    public static function getPenaltyMilestones($contractId, $companySystemID, $milestonePenaltyUuid, $isEdit = false)
    {
        return ContractMilestone::with('milestonePaymentSchedules', 'milestonePenalty')
            ->where('contractID', $contractId)
            ->where('companySystemID', $companySystemID)
            ->when($isEdit, function($query) use ($milestonePenaltyUuid)
            {
                $query->where(function($subQuery) use ($milestonePenaltyUuid)
                {
                    $subQuery->has('milestonePaymentSchedules')
                        ->where(function ($subSubQuery) use ($milestonePenaltyUuid)
                        {
                            $subSubQuery->doesntHave('milestonePenalty')
                                ->orWhereHas('milestonePenalty', function($penaltyQuery) use ($milestonePenaltyUuid)
                                {
                                    $penaltyQuery->where('uuid', $milestonePenaltyUuid);
                                });
                        });
                });
            }, function($query)
            {
                $query->has('milestonePaymentSchedules')
                    ->doesntHave('milestonePenalty');
            })
            ->get();
    }

}
