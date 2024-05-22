<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractSectionsMaster;
use App\Models\CMContractTypes;
use App\Models\CMContractTypeSections;
use App\Models\Company;
use App\Models\ContractBoqItems;
use App\Models\ContractDeliverables;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\ContractMilestoneRetention;
use App\Models\ContractOverallRetention;
use App\Models\ContractSectionDetail;
use App\Models\ContractSettingDetail;
use App\Models\ContractSettingMaster;
use App\Models\ContractUserAssign;
use App\Models\ContractUserGroup;
use App\Models\ContractUserGroupAssignedUser;
use App\Models\ContractUsers;
use App\Models\CurrencyMaster;
use App\Models\DocumentMaster;
use App\Models\Employees;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use App\Traits\CrudOperations;

/**
 * Class ContractMasterRepository
 * @package App\Repositories
 * @version March 7, 2024, 2:16 pm +04
 */

class ContractMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;

    protected $fieldSearchable = [
        'uuid',
        'contractCode',
        'title',
        'contractType',
        'counterParty',
        'counterPartyName',
        'referenceCode',
        'startDate',
        'endDate',
        'status',
        'companySystemID',
        'created_by',
        'updated_by'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContractMaster::class;
    }

    protected function getModel()
    {
        return new ContractMaster();
    }

    public function getContractMaster(Request $request)
    {
        $input  = $request->all();
        $search_keyword = $request->input('search.value');
        $companyId =  $input['companyId'];
        $filter = $input['filter'] ?? null;
        $languages =  $this->model->contractMaster($search_keyword, $companyId, $filter);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }

    public function getAllContractMasterFilters($request)
    {
        $allTypes = [
            "counter-parties" => ContractManagementUtils::getCounterParties(),
            "contract_types" => ContractManagementUtils::getContractTypes(),
            "default_user_group_count" => ContractManagementUtils::getContractDefaultUserGroup($request)
        ];
        return $allTypes;
    }

    public function getCounterPartyNames(Request $request)
    {
        $input = $request->all();
        $counterPartyId = $input['counterPartyId'];

        $allTypes = [
            "counter-party-names" => ContractManagementUtils::counterPartyNames($counterPartyId),
        ];
        return $allTypes;
    }

    public function exportContractMasterReport(Request $request)
    {
        $input = $request->all();
        $search = false;
        $companyId = $input['selectedCompanyID'];

        $lotData = $this->model->contractMaster($search, $companyId, $input)->get();
        $data[0]['Contract Code'] = "Contract Code";
        $data[0]['Title'] = "Title";
        $data[0]['Contract Type'] = "Contract Type";
        $data[0]['Counter Party'] = "Counter Party";
        $data[0]['Counter Party Name'] = "Counter Party Name";
        $data[0]['Reference Code'] = "Reference Code";
        $data[0]['Start Date'] = "Start Date";
        $data[0]['End Date'] = "End Date";
        $data[0]['Status'] = "Status";
        if ($lotData) {
            $count = 1;
            foreach ($lotData as $value) {
                $data[$count]['Contract Code'] = isset($value['contractCode']) ? preg_replace('/^=/', '-', $value['contractCode']) : '-';
                $data[$count]['Title'] = isset($value['title']) ? preg_replace('/^=/', '-', $value['title']) : '-';
                $data[$count]['Contract Type'] = isset($value['contractTypes']) ? preg_replace('/^=/', '-', $value['contractTypes']['cm_type_name']) : '-';
                $data[$count]['Contract Party'] = isset($value['counterParties']) ? preg_replace('/^=/', '-', $value['counterParties']['cmCounterParty_name']) : '-';
                $data[$count]['Counter Party Name'] = isset($value['counterPartyName']) ? preg_replace('/^=/', '-', $value['contractUsers']['contractUserName']) : '-';
                $data[$count]['Reference Code'] = isset($value['referenceCode']) ? preg_replace('/^=/', '-', $value['referenceCode']) : '-';
                $data[$count]['Start Date'] = Carbon::parse($value['startDate']) ? preg_replace('/^=/', '-', Carbon::parse($value['startDate'])) : '-';
                $data[$count]['End Date'] = Carbon::parse($value['endDate']) ? preg_replace('/^=/', '-', Carbon::parse($value['endDate'])) : '-';
                $data[$count]['Status'] = $value['status'] == 1 ? 'Active' : 'In-active';
                $count++;
            }
        }
        return $data;
    }

    public function createContractMaster($input)
    {

        $companySystemID = $input['companySystemID'];
        $title = $input['title'];

        $contractType = CMContractTypes::select('contract_typeId', 'cmCounterParty_id')
            ->where('uuid', $input['contractType'])
            ->first();

        $lastSerialNumber = 1;
        $lastId = ContractMaster::select('id')->orderBy('id', 'desc')->first();
        if ($lastId) {
            $lastSerialNumber = intval($lastId->id) + 1;
        }
        $contractCode =  ('CO'  . str_pad($lastSerialNumber, 4, '0', STR_PAD_LEFT));

        $insertArray = [];

        DB::beginTransaction();
        try{
            $insertArray = [
                'contractCode' => $contractCode,
                'title' => $title,
                'contractType' => $contractType["contract_typeId"],
                'counterParty' => $contractType["cmCounterParty_id"],
                'uuid' => bin2hex(random_bytes(16)),
                'companySystemID' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];

            $insertResponse = ContractMaster::create($insertArray);
            $contractMasterId = $insertResponse->id;

            if($insertResponse) {

                $contractTypeSections = CMContractTypeSections::where('contract_typeId', $contractType["contract_typeId"])
                    ->where('companySystemID', $companySystemID)
                    ->where('is_enabled', 1)
                    ->get();

                foreach ($contractTypeSections as $contractTypeSection) {

                    $contractSettingMasterArray = [
                        'uuid' => bin2hex(random_bytes(16)),
                        'contractId' => $contractMasterId,
                        'contractTypeSectionId' => $contractTypeSection['ct_sectionId'],
                        'isActive' => 0
                    ];
                    $contractSettingMaster = ContractSettingMaster::create($contractSettingMasterArray);
                }

                $contractTypeSectionDetail = ContractSettingMaster::with([
                    'contractTypeSection' => function ($q) {
                        $q->select('ct_sectionId', 'cmSection_id', 'contract_typeId', 'companySystemID')
                            ->with(['contractSectionWithTypes' => function ($q1) {
                                $q1->select('cmSection_id','cmSection_detail')
                                    ->with(['sectionDetail']);
                            }]);
                    }
                ])->where('contractId', $contractMasterId)
                    ->get();

                $contractSettingDetailArray = [];
                $i = 0;

                foreach ($contractTypeSectionDetail as $contractSectionDetail){
                    $sectionDetails = $contractSectionDetail['contractTypeSection']['contractSectionWithTypes']['sectionDetail'];

                    foreach ($sectionDetails as $sectionDetail){
                        $sectionDetailId = $sectionDetail['id'];
                        $contractSettingDetailArray[$i] = [
                            'uuid' => bin2hex(random_bytes(16)),
                            'settingMasterId' => $contractSectionDetail['id'],
                            'sectionDetailId' => $sectionDetailId,
                            'isActive' => 0,
                            'contractId' => $contractMasterId,
                            'created_at' => Carbon::now(),
                        ];
                        $i++;
                    }
                }

                ContractSettingDetail::insert($contractSettingDetailArray);

                $this->assignDefaultUserForContract($contractMasterId, $companySystemID);

                DB::commit();
                return [
                    'status' => true,
                    'message' => trans('common.contract_master_created_successfully'), 'data' => $insertResponse
                ];
            }

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }

    public function getEditFormData($counterPartyType): array {
        return [
            'contractType' => ContractManagementUtils::getContractTypes(),
            'contractOwners' => ContractManagementUtils::counterPartyNames($counterPartyType),
            'counterPartyType' => ContractManagementUtils::getCounterParty(),
            'counterPartyNames' => ContractManagementUtils::counterPartyNames($counterPartyType)
        ];
    }

    public function userFormData($value, $fromContractType){
        if($fromContractType) {
            $checkCounterParty = CMContractTypes::where('uuid', $value)->pluck('cmCounterParty_id')->first();
            if(empty($checkCounterParty)) {
                return ['status' => false, 'message' => trans('common.contract_type_not_found')];
            }
        } else {
            $checkCounterParty = $value;
        }

        $response = [
            'counterParty' =>  $checkCounterParty,
            'contractOwners' => ContractManagementUtils::counterPartyNames($checkCounterParty),
            'counterPartyNames' => ContractManagementUtils::counterPartyNames($checkCounterParty)
        ];

        return ['status' => true , 'message' => trans('common.contract_form_data_retrieved'), 'data' => $response];
    }

    public function updateContract($formData, $id, $selectedCompanyID): array {

        $contractOwner = $formData['contractOwner'] ?? '';

        $checkContractTypeID = CMContractTypes::select('contract_typeId')->where('uuid', $formData['contractType'])->where('companySystemID', $selectedCompanyID)->first();
        if(empty($checkContractTypeID)){
            return ['status' => false, 'message' => trans('common.contract_type_not_found')];
        }
        $checkOwnerID = ($contractOwner != '') ? ContractUsers::where('uuid', $formData['contractOwner'])->where('companySystemId', $selectedCompanyID)->pluck('id')->first() : null;
        if($contractOwner != '' && empty($checkOwnerID)){
            return ['status' => false, 'message' => trans('common.contract_owner_not_found')];
        }
        $checkContractPartyNameID = ContractUsers::where('uuid', $formData['counterPartyName'])->where('companySystemId', $selectedCompanyID)->pluck('id')->first();
        if(empty($checkContractPartyNameID)){
            return ['status' => false, 'message' => trans('common.counter_party_name_not_found')];
        }

        $checkValidation = $this->checkValidation($formData, $id, $selectedCompanyID);
        if(!$checkValidation['status']) {
            return ['status' => false, 'message' => $checkValidation['message']];
        }

        DB::beginTransaction();
        try{
            $updateData = [
                'title' => $formData['title'] ?? null,
                'description' => $formData['description'] ?? null,
                'contractType' => $checkContractTypeID['contract_typeId'],
                'counterParty' => $formData['counterParty'] ?? null,
                'counterPartyName' => $checkContractPartyNameID,
                'referenceCode' => $formData['referenceCode'] ?? null,
                'contractOwner' => $checkOwnerID,
                'contractAmount' => $formData['contractAmount'] ?? 0,
                'startDate' => $formData['formatStartDate'] ? Carbon::parse($formData['formatStartDate'])->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second) : null,
                'endDate' => $formData['formatEndDate'] ? Carbon::parse($formData['formatEndDate'])->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second) : null,
                'agreementSignDate' => $formData['formatAgreementSignDate'] ? Carbon::parse($formData['formatAgreementSignDate'])->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second) : null,
                'contractTermPeriod' => $formData['contractTermPeriod'] ?? null,
                'notifyDays' => $formData['notifyDays'] ?? null,
                'primaryCounterParty' => $formData['primaryCounterParty'] ?? null,
                'primaryEmail' => $formData['primaryEmail'] ?? null,
                'primaryPhoneNumber' => $formData['primaryPhoneNumber'] ?? null,
                'secondaryCounterParty' => $formData['secondaryCounterParty'] ?? null,
                'secondaryEmail' => $formData['secondaryEmail'] ?? null,
                'secondaryPhoneNumber' => $formData['secondaryPhoneNumber'] ?? null,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            $updateResponse = ContractMaster::where('id', $id)->update($updateData);
            if($updateResponse) {
                DB::commit();
                return ['status' => true, 'message' => trans('common.contract_updated_successfully')];
            }

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }

    public function checkValidation($formData, $id, $selectedCompanyID){
        $primaryEmail = $formData['primaryEmail'] ?? null;
        $secondaryEmail = $formData['secondaryEmail'] ?? null;

        if($primaryEmail != null) {
            if(ContractMaster::where('id', '!=' ,$id)->where('primaryEmail', $primaryEmail)->orWhere('secondaryEmail', $primaryEmail)->where('companySystemID', $selectedCompanyID)->exists()) {
                return ['status' => false, 'message' => trans('common.primary_email_already_exists')];
            }
            if(Employees::where('empEmail', $primaryEmail)->where('empCompanySystemID', $selectedCompanyID)->exists()){
                return ['status' => false, 'message' => trans('common.primary_email_already_exists_in_employees')];
            }
        }
        if($secondaryEmail != null) {
            if(ContractMaster::where('id', '!=' ,$id)->where('primaryEmail', $secondaryEmail)->orWhere('secondaryEmail', $secondaryEmail)->where('companySystemID', $selectedCompanyID)->exists()) {
                return ['status' => false, 'message' => trans('common.secondary_email_already_exists')];
            }
            if(Employees::where('empEmail', $secondaryEmail)->where('empCompanySystemID', $selectedCompanyID)->exists()){
                return ['status' => false, 'message' => trans('common.secondary_email_already_exists_in_employees')];
            }
        }

        return ['status' => true, 'message' => 'Validation checked successfully'];
    }

    public function unsetValues($contract) {
        $contract['contractTypeUuid'] = $contract['contractTypes']['uuid'] ?? null;
        unset($contract['contractTypes']);
        $contract['counterPartyNameUuid'] =  $contract['contractUsers']['uuid'] ?? null;
        unset($contract['contractUsers']);
        $contract['contractOwnerUuid'] =  $contract['contractOwners']['uuid'] ?? null;
        unset($contract['contractOwners']);
        unset($contract['contractOwner']);
        unset($contract['contractType']);
        unset($contract['counterPartyName']);

        return $contract;
    }

    public function getContractTypeSectionData(Request $request)
    {
        $input = $request->all();
        $contractuuid = $input['contractId'];

        $contractId = ContractMaster::select('id')->where('uuid', $contractuuid)->first();

        $settingMaster = ContractSettingMaster::select('id', 'uuid', 'contractId', 'contractTypeSectionId', 'isActive')
            ->with([
                'contractTypeSection' => function ($q) {
                    $q->select('ct_sectionId', 'uuid', 'contract_typeId', 'cmSection_id', 'is_enabled');
                    $q->with([
                        'contractSectionWithTypes'
                    ]);
                },
                'contractSettingDetails' => function ($q) {
                    $q->select('id', 'uuid', 'contractId', 'settingMasterId', 'sectionDetailId', 'isActive')
                        ->with([
                            'contractSectionDetails' => function ($q) {
                                $q->select('id', 'sectionMasterId', 'description', 'inputType');
                            }
                        ]);
                }
            ])
            ->where('contractId', $contractId->id)
            ->get();
        $masterData = [];
        if($settingMaster) {
            foreach($settingMaster as $key => $master) {
                $masterData[$key] = [
                    'masterUUid' => $master['uuid'],
                    'isActive' => $master['isActive'],
                    'masterDescription' => $master['contractTypeSection']['contractSectionWithTypes']['cmSection_detail'] ?? null,
                    'details' => []
                ];
                if($master['contractSettingDetails']) {
                    foreach($master['contractSettingDetails'] as $details) {
                        $masterData[$key]['details'][] = [
                            'settingDetailUuid' => $details['uuid'],
                            'isActive' => $details['isActive'],
                            'description' => $details['contractSectionDetails']['description'],
                            'inputType' => $details['contractSectionDetails']['inputType']
                        ];
                    }
                }
            }
        }
        return $masterData;
    }

    public function updateContractSettingDetails(Request $request)
    {
        $contractUuid = $request->input('contractId') ?? 0;
        $formData = $request->input('formData') ?? null;
        $settingMasters = $formData['settingMasters'] ?? [];

        $contractID = ContractMaster::select('id')->where('uuid', $contractUuid)->pluck('id')->first();
        if(empty($contractID)) {
            return ['status' => false, 'message' => trans('common.contract_not_found'), 'line' => __LINE__];
        }

        if(empty($settingMasters)){
            return ['status' => false, 'message' => 'Cannot update, no record found', 'line' => __LINE__];
        }

        try {
            DB::beginTransaction();

            foreach ($settingMasters as $master) {
                $settingMaster = ContractSettingMaster::where('uuid', $master['id'])->first();

                if (!$settingMaster) {
                    return [
                        'status' => false,
                        'message' => 'Contract setting master not found for UUID: ' . $master['id'],
                        'line' => __LINE__
                    ];
                }

                $masterActive = $master['isActive'] ?? 0;
                $settingMaster->update(['isActive' => $masterActive ? 1 : 0]);

                if (!empty($master['settingDetail'])) {
                    foreach ($master['settingDetail'] as $details) {
                        $settingDetail = ContractSettingDetail::where('uuid', $details['settingDetailUuid'])->first();

                        if (!$settingDetail) {
                            return [
                                'status' => false,
                                'message' => 'Contract setting detail not found for UUID: ' . $details['settingDetailUuid'],
                                'line' => __LINE__
                            ];
                        }

                        $isActive = $details['isActive'] ?? 0;
                        $settingDetail->update(['isActive' => $isActive ? 1 : 0]);
                    }
                }
            }

            DB::commit();
            return ['status' => true, 'message' => trans('common.contract_updated_successfully')];
        } catch(\Exception $ex) {
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }

    public function getActiveContractSectionDetails(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contractId'];

        $contractId = ContractMaster::select('id')->where('uuid', $contractUuid)->first();

        $activeSetting = ContractSettingMaster::where('contractId', $contractId['id'])
            ->where('isActive', 1)
            ->with([
                'contractTypeSection' => function ($q) {
                    $q->select('ct_sectionId', 'cmSection_id');
                }
            ])
            ->get();
        $pluckedData = [];
        foreach ($activeSetting as $setting) {
            $pluckedData[] = $setting['contractTypeSection']['cmSection_id'];
        }

        return [
            'status' => true ,
            'message' => trans('common.active_contract_section_details'), 'data' => $pluckedData
        ];
    }

    public function getContractOverallRetentionData(Request $request){
        $input = $request->all();
        $contractUuid = $input['contractId'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractMaster::select('id', 'contractAmount')->where('uuid', $contractUuid)->first();

        $activeSections = ContractSettingDetail::select('sectionDetailId')
            ->where('contractId', $contract['id'])
            ->where('isActive', 1)
            ->get();

        $pluckedData = [];
        foreach ($activeSections as $activeSection) {
            $pluckedData[] = $activeSection['sectionDetailId'];
        }

        $overallRetention = ContractOverallRetention::where('contractId', $contract['id'])
            ->where('companySystemId', $companySystemID)
            ->with([
                'contract' => function ($q) {
                    $q->select('contractAmount', 'id');
                }
            ])
            ->first();

        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);

        $response['activeRetention'] = $pluckedData;
        $response['overallRetention'] = $overallRetention;
        $response['decimalPlaces'] = $decimalPlaces;
        $response['contractAmount'] =  $contract['contractAmount'];

        return [
            'status' => true ,
            'message' => trans('common.contract_overall_retention_retrieved'),
            'data' => $response];
    }

    public function updateOverallRetention(Request $request){
        $input = $request->all();
        $contractUuid = $input['contractId'];
        $companySystemID = $input['selectedCompanyID'];
        $formData = $input['formValue'];

        $contract = ContractMaster::select('id')->where('uuid', $contractUuid)->first();

        $overallRetention = ContractOverallRetention::where('contractId', $contract['id'])
            ->where('companySystemId', $companySystemID)
            ->first();

        DB::beginTransaction();
        try{

            $data = [
                'retentionPercentage' => $formData['retentionPercentage'] ?? null,
                'retentionAmount' => $formData['retentionAmount'] ?? null,
                'startDate' =>
                    $formData['formatStartDate'] ? Carbon::parse($formData['formatStartDate'])
                        ->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second) : null,
                'dueDate' =>
                    $formData['formatDueDate'] ? Carbon::parse($formData['formatDueDate'])
                        ->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second) : null,
                'retentionWithholdPeriod' => $formData['retentionWithholdPeriod'] ?? null,
            ];

            if (!$overallRetention){
                $data['uuid'] = bin2hex(random_bytes(16));
                $data['contractId'] = $contract['id'];
                $data['contractAmount'] = $formData['contractAmount'] ?? null;
                $data['companySystemId'] = $companySystemID;
                $data['created_by'] = General::currentEmployeeId();
                $data['created_at'] = Carbon::now();

            }else{
                $data['updated_by'] = General::currentEmployeeId();
                $data['updated_at'] = Carbon::now();
            }


            ContractOverallRetention::updateOrCreate(
                ['contractId' => $contract['id'], 'companySystemId' => $companySystemID],$data);

            DB::commit();
            return ['status' => true, 'message' => trans('common.overall_retention_updated_successfully')];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }

    public function getContractConfirmationData(Request $request){
        $input = $request->all();
        $contractUuid = $input['contractUuid'];

        $contractConfirmation = ContractMaster::select('confirmed_yn', 'confirmed_date', 'confirm_by')
            ->with(['confirmedBy'])
            ->where('uuid', $contractUuid)->first();

        return [
            'status' => true ,
            'message' => trans('common.contract_confirmation_data_retrieved'),
            'data' => $contractConfirmation
        ];
    }

    public function confirmContract(Request $request){
        $input = $request->all();
        $contractUuid = $input['contractUuid'];
        $confirmed_yn = $input['confirmed_yn'];
        $companySystemID = $input['selectedCompanyID'];
        $message = null;

        $contractId = ContractMaster::select('id')->where('uuid', $contractUuid)->first();

        $message = $this->checkActiveMasters($contractId['id'], $companySystemID);
        if ($message) {
            return ['status' => false, 'message' => $message];
        }

        $message = $this->checkOverallAndMilestoneRetention($contractId['id'], $companySystemID);
        if ($message) {
            return ['status' => false, 'message' => $message];
        }

        $message = $this->checkMandatoryDetails($contractId['id'], $companySystemID);

        if($message){
            return [
                'status' => false,
                'message' => $message
            ];
        }else{
            DB::beginTransaction();
            try{
                $data = [
                    'confirmed_yn' => $confirmed_yn,
                    'confirm_by' => General::currentEmployeeId(),
                    'confirmed_date' => Carbon::now()
                ];

                ContractMaster::where('uuid', $contractUuid)->update($data);

                DB::commit();
                return ['status' => true, 'message' => trans('common.contract_confirmation_updated_successfully')];

            } catch (\Exception $ex){
                DB::rollBack();
                return ['status' => false, 'message' => $ex->getMessage()];
            }
        }
    }

    private function checkActiveMasters($contractId, $companySystemID){
        $activeMasters = ContractSettingMaster::select('contractTypeSectionId')
            ->where('contractId', $contractId)
            ->where('isActive', 1)
            ->get();

        $activeSections = ContractSettingDetail::select('sectionDetailId')
            ->where('contractId', $contractId)
            ->where('isActive', 1)
            ->get();

        $existRetention = $activeSections->pluck('sectionDetailId')->toArray();

        foreach ($activeMasters as $activeMaster){
            if($activeMaster['contractTypeSectionId'] == 1){
                $existBoq = ContractBoqItems::select('qty')->where('contractId', $contractId)
                    ->where('companyId', $companySystemID)
                    ->first();
                if(empty($existBoq)) {
                    return  trans('common.at_least_one_boq_item_should_be_available');
                }
                if(empty($existBoq['qty'])){
                    return  trans('common.qty_is_a_mandatory_field');
                }
            }
            if($activeMaster['contractTypeSectionId'] == 2){
                $existMilestone = ContractMilestone::where('contractID', $contractId)
                    ->where('companySystemID', $companySystemID)
                    ->first();
                if(empty($existMilestone)){
                    return trans('common.at_least_one_milestone_should_be_available');
                }
            }
            if(($activeMaster['contractTypeSectionId'] == 4 && !in_array(4, $existRetention) &&
                !in_array(5, $existRetention)) || ($activeMaster['contractTypeSectionId'] == 4 &&
                    $existRetention == null)){
                return trans('common.at_least_one_retention_should_be_available');
            }
        }

        return null;
    }

    private function checkOverallAndMilestoneRetention($contractId, $companySystemID){
        $activeSections = ContractSettingDetail::select('sectionDetailId')
            ->where('contractId', $contractId)
            ->where('isActive', 1)
            ->get();

        foreach ($activeSections as $activeSection) {
            if($activeSection['sectionDetailId'] == 4){
                $existOverallRetention = ContractOverallRetention::where('contractId', $contractId)
                    ->where('companySystemId', $companySystemID)
                    ->first();
                if(empty($existOverallRetention)) {
                    return trans('common.at_least_one_overall_retention_should_be_available');
                }
            }
            if($activeSection['sectionDetailId'] == 5){
                $existMilestoneRetention = ContractMilestoneRetention::where('contractId', $contractId)
                    ->where('companySystemId', $companySystemID)
                    ->first();
                if(empty($existMilestoneRetention)) {
                    return trans('common.at_least_one_milestone_retention_should_be_available');
                }
            }
        }

        return null;
    }

    private function checkMandatoryDetails($contractId, $companySystemID)
    {
        $totalRecords = ContractMilestoneRetention::where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)->count();
        $recordsWithMilestoneId = ContractMilestoneRetention::whereNotNull('milestoneId')
            ->where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)
            ->count();
        $recordsWithRetentionPercentage = ContractMilestoneRetention::whereNotNull('retentionPercentage')
            ->where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)
            ->count();
        $recordsWithStartDate = ContractMilestoneRetention::whereNotNull('startDate')
            ->where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)
            ->count();
        $recordsWithDueDate = ContractMilestoneRetention::whereNotNull('dueDate')
            ->where('contractId', $contractId)
            ->where('companySystemId', $companySystemID)
            ->count();

        $contract = ContractMaster::select('contractAmount')->where('id', $contractId)->first();

        if($totalRecords != $recordsWithMilestoneId){
            return trans('common.milestone_title_is_a_mandatory_field');
        }
        if($totalRecords != $recordsWithRetentionPercentage){
            return trans('common.retention_percentage_is_a_mandatory_field');
        }
        if($totalRecords != $recordsWithStartDate){
            return trans('common.start_date_is_a_mandatory_field');
        }
        if($totalRecords != $recordsWithDueDate){
            return trans('common.due_date_is_a_mandatory_field');
        }
        if($contract['contractAmount'] == 0){
            return trans('common.contract_amount_is_a_mandatory_field');
        }

        return null;
    }

    private function assignDefaultUserForContract($contractId, $companySystemID)
    {
        $defaultUserIds = ContractUserGroup::where('companySystemID', $companySystemID)
            ->where('isDefault', 1)
            ->pluck('id')
            ->toArray();

        $userIdsAssignedUserGroup = ContractUserGroupAssignedUser::select('contractUserId', 'userGroupId')
            ->whereIn('userGroupId', $defaultUserIds)
            ->where('status', 1)
            ->get();
        foreach ($userIdsAssignedUserGroup as $user) {
            $userGroupId = $user['userGroupId'];
            $userId = $user['contractUserId'];
            $existingRecord = ContractUserAssign::where('contractId', $contractId)
                ->where('userGroupId', $userGroupId)
                ->where('userId', $userId)
                ->where('status', 1)
                ->first();

            if (!$existingRecord) {
                $input = [
                    'uuid' => bin2hex(random_bytes(16)),
                    'contractId' => $contractId,
                    'userGroupId' => $userGroupId,
                    'userId' => $userId,
                    'status' => 1,
                    'createdBy' => General::currentEmployeeId(),
                    'updated_at' => null
                ];

                ContractUserAssign::create($input);
            }
        }
    }

}
