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
            "contract-setions" => ContractManagementUtils::getContractSetions()
        ];
        return $allTypes;
    }

    public function saveContractType(Request $request)
    {
        DB::beginTransaction();

        $input = $request->all();
        $empId = General::currentEmployeeId();

        $existingContractType = CMContractTypes::where('cm_type_name', 'LIKE', $input['cm_type_name'])
            ->where('companySystemID', $input['selectedCompanyID'])
            ->when(isset($input['contract_typeId']), function ($query) use ($input) {
                $query->where('contract_typeId', '!=', $input['contract_typeId']);
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
            $contractSections = $this->getAllContractFilters($request)['contract-setions'];

            if (isset($input['contract_typeId'])) {
                $storeContractType['updated_by'] = $empId;

                $insertResp = CMContractTypes::where('contract_typeId', $input['contract_typeId'])
                    ->update($storeContractType);
                $ContractTypeId = $input['contract_typeId'];
                $message = "Update successfully.";
            } else {
                $storeContractType['created_by'] = $empId;

                $insertResp = CMContractTypes::create($storeContractType);
                $ContractTypeId = $insertResp->id;
                $message = "Saved successfully.";
            }

            if ($insertResp) {
                foreach ($contractSections as $dynamicField) {
                    $ContractSec = CMContractTypeSections::where('contract_typeId', $ContractTypeId)
                        ->where('cmSection_id', $dynamicField['cmSection_id'])
                        ->where('companySystemID', $input['selectedCompanyID'])
                        ->first();

                    if (!$ContractSec) {
                        $dynamicArray = [
                            'contract_typeId' => $ContractTypeId,
                            'cmSection_id' => $dynamicField['cmSection_id'],
                            'companySystemID' => $input['selectedCompanyID'],
                            'created_by' => $empId,
                            'created_at' => now(),
                        ];
                        CMContractTypeSections::create($dynamicArray);
                    }
                }

                DB::commit();
                return ['status' => true, 'message' => $message, 'contract_typeId' => $ContractTypeId];
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    public function deleteContractType(Request $request)
    {
        $input = $request->all();

        $result = CMContractTypeSections::where('contract_typeId', $input['id'])->delete();
        $result3 = CMContractTypes::where('contract_typeId', $input['id'])->delete();

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
        $ContractTypeId = isset($input['contractTypeId']) ? $input['contractTypeId'] : 0;

        $query = CMContractTypeSections::with(['contratSectionWithtypes'])->where('companySystemID', $companySystemID)
            ->where('contract_typeId', $ContractTypeId)->get();
        return $query;
    }

    public function updateDynamicFieldDetail(Request $request)
    {
        $input = $request;
        $dynamicFieldDetailID = $input['ct_sectionId'] ?? 0;
        $isActive = isset($input['is_enabled']) && $input['is_enabled'] ? 1 : 0;

        DB::beginTransaction();
        try {
            $postData = [
                'is_enabled' => $isActive
            ];

            $updateResp = CMContractTypeSections::where('ct_sectionId', $dynamicFieldDetailID)->update($postData);
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
