<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractTypes;
use App\Models\ContractMaster;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

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
    protected $fieldSearchable = [
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

            $insertResponse = ContractMaster::insert($insertArray);
            if($insertResponse) {
                DB::commit();
                return ['status' => true, 'message' => trans('common.contract_master_created_successfully')];
            }

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }
}
