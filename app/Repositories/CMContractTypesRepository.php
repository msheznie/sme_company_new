<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractTypes;
use App\Models\CMContractTypeSections;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

/**
 * Class CMContractTypesRepository
 * @package App\Repositories
 * @version February 22, 2024, 2:41 pm +04
 */

class CMContractTypesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cm_type_name',
        'cmMaster_id',
        'cmIntent_id',
        'cmPartyA_id',
        'cmPartyB_id',
        'cmCounterParty_id',
        'cm_type_description',
        'ct_active',
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
        return CMContractTypes::class;
    }


    public function getAllContractFilters(Request $request)
    {
        $allTypes = [
            "categories" => ContractManagementUtils::getContractsMasters(),
            "intents" => ContractManagementUtils::getIntentMasters(),
            "parties" => ContractManagementUtils::getPartiesMasters(),
            "counter-parties" => ContractManagementUtils::getCounterParties(),
            "contract-setions" => ContractManagementUtils::getContractSetions(),
            "contract-all-setions" => ContractManagementUtils::getAllContractSetions()
        ];
        return $allTypes;
    }

    public function saveContractType(Request $request)
    {
        DB::beginTransaction();

        $input = $request->all();
        $empId = General::currentEmployeeId();
        $uuid = $input['uuid'] ?? null;
        $contractTypeID = 0;
        if(!empty($uuid)){
            $contractTypeID = CMContractTypes::where('uuid', $uuid)->pluck('contract_typeId')->first();
        }

        $existingContractType = CMContractTypes::where('cm_type_name', 'LIKE', $input['cm_type_name'])
            ->where('companySystemID', $input['selectedCompanyID'])
            ->when($contractTypeID > 0, function ($query) use ($contractTypeID) {
                $query->where('contract_typeId', '!=', $contractTypeID);
            });

        if ($existingContractType->exists()) {
            return ['status' => false, 'message' => 'Type Name: ' . $input['cm_type_name'] . ' already exists'];
        }

        $storeContractType = [
            'cm_type_name' => $input['cm_type_name'],
            'cmMaster_id' => $input['cmMaster_id'],
            'cmIntent_id' => $input['cmIntent_id'],
            'cmPartyA_id' => $input['cmPartyA_id'],
            'cmPartyB_id' => $input['cmPartyB_id'],
            'cmCounterParty_id' => $input['cmCounterParty_id'],
            'cm_type_description' => $input['cm_type_description'] ?? null,
            'ct_active' => isset($input['ct_active']) && $input['ct_active'] ? 1 : 0,
            'companySystemID' => $input['selectedCompanyID'],
        ];

        try {
            $contractSections = $this->getAllContractFilters($request)['contract-all-setions'];

            if ($contractTypeID > 0) {
                $storeContractType['updated_by'] = $empId;

                $insertResp = CMContractTypes::where('contract_typeId', $contractTypeID)
                    ->update($storeContractType);

                $message = "Update successfully.";
            } else {
                $storeContractType['created_by'] = $empId;
                $storeContractType['uuid'] = bin2hex(random_bytes(16));

                $insertResp = CMContractTypes::create($storeContractType);
                $contractTypeID = $insertResp->id;
                $message = "Saved successfully.";
            }

            if ($insertResp) {
                foreach ($contractSections as $dynamicField) {
                    $ContractSec = CMContractTypeSections::where('contract_typeId', $contractTypeID)
                        ->where('cmSection_id', $dynamicField['cmSection_id'])
                        ->where('companySystemID', $input['selectedCompanyID'])
                        ->first();

                    $csm_active = $dynamicField['csm_active'] == 0 ? 1 : 0;

                    if (!$ContractSec) {
                        $dynamicArray = [
                            'uuid' => bin2hex(random_bytes(16)),
                            'contract_typeId' => $contractTypeID,
                            'cmSection_id' => $dynamicField['cmSection_id'],
                            'is_enabled' => $csm_active,
                            'companySystemID' => $input['selectedCompanyID'],
                            'created_by' => $empId,
                            'created_at' => now(),
                        ];
                        CMContractTypeSections::create($dynamicArray);
                    }
                }

                DB::commit();
                return ['status' => true, 'message' => $message];
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    public function deleteContractType(Request $request)
    {
        $input = $request->all();
        $contractID = $input['id'] ?? null;

        $existContractID = CMContractTypes::where('uuid', $contractID)->pluck('contract_typeId')->first();
        if(empty($existContractID)) {
            return ['status' => false, 'code' => 404, 'message' => 'Contract Type not found.'];
        }

        $result = CMContractTypeSections::where('contract_typeId', $existContractID)->delete();
        $result3 = CMContractTypes::where('contract_typeId', $existContractID)->delete();

        if ($result3) {
            return ['status' => true, 'code' => 422, 'message' => 'Contract Type has been deleted successfully.'];
        } else {
            return ['status' => false, 'code' => 422, 'message' => 'Contract Type Deletion Failed'];
        }
    }

    public function retrieveContractTypes(Request $request)
    {
        $search_keyword = $request->input('search.value');
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $filter = $input['filter'] ?? null;
        $languages =  $this->model->listOfContractTypes($search_keyword, $companyId, $filter);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->order(function ($query) use ($input) {
                if (request()->has('order')) {
                    if ($input['order'][0]['column'] == 0) {
                        $query->orderBy('contract_typeId', $input['order'][0]['dir']);
                    }
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function exportContractTypesReport(Request $request)
    {
        $input = $request->all();
        $search = false;
        $companyId = $input['companySystemID'];

        $lotData = $this->model->listOfContractTypes($search, $companyId, $input)->get();
        $data[0]['Name'] = "Name";
        $data[0]['Category'] = "Category";
        $data[0]['Intent'] = "Intent";
        $data[0]['Date and Created By'] = "Created By";
        $data[0]['Status'] = "Status";
        if ($lotData) {
            $count = 1;
            foreach ($lotData as $value) {
                $data[$count]['Name'] = isset($value['cm_type_name']) ? preg_replace('/^=/', '-', $value['cm_type_name']) : '-';
                $data[$count]['Category'] = isset($value['contratMasterWithtypes']) ? preg_replace('/^=/', '-', $value['contratMasterWithtypes']['cmMaster_description']) : '-';
                $data[$count]['Intent'] = isset($value['intentMasterWithtypes']) ? preg_replace('/^=/', '-', $value['intentMasterWithtypes']['cmIntent_detail']) : '-';
                $data[$count]['Date and Created By'] = Carbon::parse($value['created_at']) . '|' . (isset($value['createdUserWithtypes']) ? $value['createdUserWithtypes']['empName'] : '-');
                $data[$count]['Status'] = $value['ct_active'] == 1 ? 'Active' : 'In-active';
                $count++;
            }
        }
        return $data;
    }

    public function getSectionsFilterDrop(Request $request)
    {
        $input = $request->all();
        $companySystemID = $input['selectedCompanyID'];
        $ContractTypeId = $input['contractTypeId'] ?? null;

        $existContractTypeID = CMContractTypes::where('uuid', $ContractTypeId)->pluck('contract_typeId')->first();
        if(empty($existContractTypeID)) {
            return ['status' => flase, 'message' => 'Contract Types not found'];
        }

        $contractTypeSection = CMContractTypeSections::with([
            'contractSectionWithTypes' => function ($q) {
                $q->select('cmSection_id', 'cmSection_detail', 'csm_active');
            }
        ])->where('companySystemID', $companySystemID)
            ->where('contract_typeId', $existContractTypeID)
            ->select('uuid', 'contract_typeId', 'cmSection_id', 'is_enabled')
            ->get();

        return ['status' => true, 'message' => 'Contract Type Section retrieved successfully', 'data' => $contractTypeSection];
    }

    public function updateDynamicFieldDetail(Request $request)
    {
        $input = $request;
        $sectionID = $input['sectionID'] ?? null;
        $isActive = isset($input['isEnabled']) && $input['isEnabled'] ? 1 : 0;

        $checkDetailExistID = CMContractTypeSections::where('uuid', $sectionID)->pluck('ct_sectionId')->first();
        if(empty($checkDetailExistID)) {
            return ['status' => flase, 'message' => 'Contract Type Section not found'];
        }

        DB::beginTransaction();
        try {
            $postData = [
                'is_enabled' => $isActive
            ];

            $updateResp = CMContractTypeSections::where('ct_sectionId', $checkDetailExistID)->update($postData);
            if ($updateResp) {
                DB::commit();
                return ['status' => true, 'message' => "Successfully updated!"];
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}
