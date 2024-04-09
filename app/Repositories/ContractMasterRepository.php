<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractSectionsMaster;
use App\Models\CMContractTypes;
use App\Models\CMContractTypeSections;
use App\Models\ContractMaster;
use App\Models\ContractSectionDetail;
use App\Models\ContractSettingDetail;
use App\Models\ContractSettingMaster;
use App\Models\ContractUsers;
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
            ->order(function ($query) use ($input) {
                if (request()->has('order')) {
                    if ($input['order'][0]['column'] == 0) {
                        $query->orderBy('id', $input['order'][0]['dir']);
                    }
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function getAllContractMasterFilters()
    {
        $allTypes = [

            "counter-parties" => ContractManagementUtils::getCounterParties(),
            "contract_types" => ContractManagementUtils::getContractTypes()
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
        $companyId = $input['companySystemID'];

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
                $data[$count]['Counter Party Name'] = isset($value['counterPartyName']) ? preg_replace('/^=/', '-', $value['counterPartyName']) : '-';
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
                        $isActiveStatus = ($sectionDetailId == 1 || $sectionDetailId == 2) ? 1 : 0;
                            $contractSettingDetailArray[$i] = [
                                'uuid' => bin2hex(random_bytes(16)),
                                'settingMasterId' => $contractSectionDetail['id'],
                                'sectionDetailId' => $sectionDetailId,
                                'isActive' => $isActiveStatus,
                                'contractId' => $contractMasterId,
                                'created_at' => Carbon::now(),
                            ];
                            $i++;
                        }
                    }

                ContractSettingDetail::insert($contractSettingDetailArray);

                DB::commit();
                return ['status' => true, 'message' => trans('common.contract_master_created_successfully'), 'data' => $insertResponse];
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
                'contractCode' => $formData['contractCode'] ?? null,
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
                'contractTimePeriod' => $formData['formatContractTimePeriod'] ? Carbon::parse($formData['formatContractTimePeriod'])->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second) : null,
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
        $companySystemID = $input['selectedCompanyID'];
        $contractTypeId = $input['contractTypeId'] ?? null;
        $contractuuid = $input['contractId'];

        $contractId = ContractMaster::select('id')->where('uuid', $contractuuid)->first();

        $existContractTypeID = CMContractTypes::where('uuid', $contractTypeId)->pluck('contract_typeId')->first();
        if(empty($existContractTypeID)) {
            return ['status' => flase, 'message' => 'Contract Types not found'];
        }

        $contractTypeSection = ContractSettingMaster::with([
            'contractTypeSection' => function ($q) {
                $q->select('ct_sectionId', 'cmSection_id', 'contract_typeId', 'companySystemID')
                ->with(['contractSectionWithTypes' => function ($q1) {
                    $q1->select('cmSection_id','cmSection_detail')
                        ->with(['sectionDetail']);
                }]);
            }
           ])->where('contractId', $contractId['id'])
            ->get();

        $contractSectionDetail = ContractSectionDetail::get();

        $isActive =  ContractSettingDetail::select('sectionDetailId')
            ->where('isActive', 1)
            ->where('contractId', $contractId['id'])
            ->get();

        $contractSectionMaster = CMContractSectionsMaster::whereIn('cmSection_id', [3, 4, 5])->get();

        $activeSectionMasters = ContractSettingMaster::with([
            'contractTypeSection' => function ($q) {
                $q->select('ct_sectionId', 'cmSection_id', 'contract_typeId', 'companySystemID')
                    ->with(['contractSectionWithTypes' => function ($q1) {
                        $q1->select('cmSection_id','cmSection_detail');
                    }]);
            }
        ])->where('isActive', 1)
            ->where('contractId', $contractId['id'])
            ->get();

        $activeSectionId = [];

        foreach ($activeSectionMasters as $activeSectionMasters){
            $sectionId = $activeSectionMasters['contractTypeSection']['contractSectionWithTypes']['cmSection_id'];
            $activeSectionId[] = $sectionId;
        }

        $response['data'] = $contractTypeSection;
        $response['sectionDetail'] = $contractSectionDetail;
        $response['isActive'] = $isActive;
        $response['contractSectionMaster'] = $contractSectionMaster;
        $response['sectionId'] = $activeSectionId;

        return $response;
    }

    public function updateContractSettingDetails(Request $request)
    {
        $input = $request->all();
        $contractuuid = $input['contractId'];
        $sectionDetails = $input['sectionDetails'];
        $sectionMaster = $input['sectionMaster'];

        $contract = ContractMaster::select('id', 'contractType')->where('uuid', $contractuuid)->first();

        $settingMasterIds = ContractSettingDetail::select('settingMasterId')
            ->where('contractId', $contract['id'])
            ->whereIn('sectionDetailId', [1,2])
            ->get();
        $settingMasterIdData = CMContractTypeSections::select('ct_sectionId', 'cmSection_id')
            ->where('contract_typeId', $contract['contractType'])
            ->whereIn('cmSection_id', [3, 4, 5])
            ->get();

        $newSectionMaster = [];
        foreach ($sectionMaster as $firstItem) {
            foreach ($settingMasterIdData as $secondItem) {
                if ($firstItem['id'] === $secondItem['cmSection_id']) {
                    $newSectionMaster[] = [
                        'ct_sectionId' => $secondItem['ct_sectionId'],
                        'checked' => $firstItem['checked']
                    ];
                    break;
                }
            }
        }

        DB::beginTransaction();
        try{

            foreach ($sectionDetails as $sectionDetail) {

                $contractSettingMasterArray = [
                    'isActive' => $sectionDetail['checked'],
                ];

                $updateResponse = ContractSettingDetail::where('sectionDetailId', $sectionDetail['id'])
                    ->where('contractId', $contract['id'])
                    ->update($contractSettingMasterArray);


                if($sectionDetail['id'] == 1 || $sectionDetail['id'] == 2){

                    foreach ($settingMasterIds as $settingMasterId) {
                        ContractSettingMaster::where('id', $settingMasterId['settingMasterId'])
                            ->update($contractSettingMasterArray);
                    }
                }
            }

            foreach ($newSectionMaster as $sectionMasterData) {

                $contractSettingMasterNewArray = [
                    'isActive' => $sectionMasterData['checked'],
                ];

                ContractSettingMaster::where('contractTypeSectionId', $sectionMasterData['ct_sectionId'])
                    ->where('contractId', $contract['id'])
                    ->update($contractSettingMasterNewArray);

            }
            if($updateResponse) {
                DB::commit();
                return ['status' => true, 'message' => trans('common.contract_setting_updated_successfully')];
            }

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }




    }
}
