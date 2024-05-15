<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\CrudOperations;

/**
 * Class ContractMilestoneRepository
 * @package App\Repositories
 * @version April 26, 2024, 10:07 am +04
*/

class ContractMilestoneRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;

    protected $fieldSearchable = [
        'uuid',
        'contractID',
        'title',
        'percentage',
        'amount',
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
        return ContractMilestone::class;
    }

    protected function getModel()
    {
        return new ContractMilestone();
    }

    public function getContractMilestones($id, Request $request) {
        $companySystemID = $request->input('selectedCompanyID');
        $contractMaster = ContractMaster::where('uuid', $id)
            ->where('companySystemID', $companySystemID)
            ->select('*')
            ->first();

        if(empty($contractMaster)) {
            return ['status' => false, 'message' => 'Contract Master not found'];
        }

        $contractMilestones = ContractMilestone::select('uuid', 'title', 'percentage', 'amount', 'status')
            ->where('contractID', $contractMaster['id'])->get();

        return [
            'status' => true,
            'message' => 'Contract Milestone retrieved successfully',
            'data' => $contractMilestones
        ];
    }

    public function createMilestone(Request $request) {
        $companySystemID = $request->input('companySystemID');
        $contractUuid = $request->input('contractUuid');
        $formData = $request->input('formData');
        $title = $formData['title'];
        $percentage = $formData['percentage'];
        $amount = $formData['amount'];
        $status = $formData['status'];
        $uuid = bin2hex(random_bytes(16));

        if(ContractMilestone::where('uuid', $uuid)->exists()) {
            return [
                'status' => false,
                'message' => trans('common.contract_document_uuid_already_exists')
            ];
        }

        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();
        if(empty($contractMaster)) {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }
        $contractId = $contractMaster['id'];

        try{
            DB::beginTransaction();
            $insertMilestone = [
                'uuid' => $uuid,
                'contractID' => $contractId,
                'title' => $title,
                'percentage' => $percentage,
                'amount' => $amount,
                'status' => $status,
                'companySystemID' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            ContractMilestone::insert($insertMilestone);

            DB::commit();
            return ['status' => true, 'message' => trans('common.milestone_created_successfully')];
        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }

    public function updateMilestone($input, $id): array{
        $companySystemID = $input['companySystemID'];
        $formData = $input['formData'];
        $contractUuid = $input['contractUuid'] ?? null;
        $title = $formData['title'];
        $percentage = $formData['percentage'];
        $amount = $formData['amount'];
        $status = $formData['status'];

        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();
        if(empty($contractMaster)) {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }

        try{
            DB::beginTransaction();
            $insertMilestone = [
                'title' => $title,
                'percentage' => $percentage,
                'amount' => $amount,
                'status' => $status,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            ContractMilestone::where('id', $id)->update($insertMilestone);

            DB::commit();
            return ['status' => true, 'message' => trans('common.milestone_updated_successfully')];
        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }

    }

    public function getMilestoneExcelData(Request $request): array {
        $contractUuid = $request->input('contractUUid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;

        $contractMaster = ContractMaster::select('id')
            ->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();
        if(empty($contractMaster)) {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }
        $contractID = $contractMaster['id'];
        $deliverables = ContractMilestone::select('title', 'percentage', 'amount', 'status')
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->get();

        $data[0]['Title'] = "Title";
        $data[0]['Percentage'] = "Percentage";
        $data[0]['Amount'] = "Amount";
        $data[0]['Status'] = "Status";

        if($deliverables) {
            foreach ($deliverables as $key => $deliverable){
                $status = 'Pending';
                if($deliverable['status'] == 1) {
                    $status = 'In Progress';
                } elseif ($deliverable['status'] == 2) {
                    $status = 'Completed';
                }
                $data[$key+1]['Title'] = $deliverable['title'] ?? '-';
                $data[$key+1]['Percentage'] = $deliverable['percentage'] ?? '-';
                $data[$key+1]['Amount'] = $deliverable['amount'] ?? '-';
                $data[$key+1]['Status'] = $status;
            }
        }
        return [
            'status' => true,
            'message' => trans('common.successfully_data_loaded'),
            'milestones' => $data
        ];
    }
}
