<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractDeliverables;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\CrudOperations;

/**
 * Class ContractDeliverablesRepository
 * @package App\Repositories
 * @version April 26, 2024, 2:39 pm +04
*/

class ContractDeliverablesRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;

    protected $fieldSearchable = [
        'uuid',
        'contractID',
        'milestoneID',
        'description',
        'startDate',
        'endDate',
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
        return ContractDeliverables::class;
    }

    protected function getModel(){
        return new ContractDeliverables();
    }

    public function getDeliverables($contractID, $companySystemID): array {
        $deliverables = ContractDeliverables::select('uuid', 'milestoneID', 'description', 'startDate', 'endDate')
            ->with([
                'milestone' => function ($q) {
                    $q->select('id', 'uuid');
                }
            ])
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->get();
        $deliverablesArray = [];
        if ($deliverables) {
            foreach($deliverables as $key => $value) {
                $deliverablesArray[$key]['uuid'] = $value['uuid'];
                $deliverablesArray[$key]['description'] = $value['description'];
                $deliverablesArray[$key]['startDate'] = $value['startDate'];
                $deliverablesArray[$key]['endDate'] = $value['endDate'];
                $deliverablesArray[$key]['milestoneUuid'] = $value['milestone']['uuid'] ?? null;
            }
        }
        return $deliverablesArray;
    }

    public function createDeliverables(Request $request): array {
        $companySystemID = $request->input('companySystemID');
        $contractUuid = $request->input('contractUuid');
        $description = $request->input('description');
        $milestone = $request->input('milestone') ?? null;
        $formattedStartDate = $request->input('formattedStartDate') ?? null;
        $formattedEndDate = $request->input('formattedEndDate') ?? null;
        $uuid = bin2hex(random_bytes(16));
        $milestoneID = 0;

        if(ContractDeliverables::where('uuid', $uuid)->exists()) {
            return [
                'status' => false,
                'message' => trans('common.deliverable_uuid_already_exists')
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
        if($milestone != null) {
            $milestoneExists = ContractMilestone::select('id')
                ->where('uuid', $milestone)
                ->where('companySystemID',$companySystemID)
                ->first();

            $milestoneID = $milestoneExists['id'] ?? 0;
        }
        $contractId = $contractMaster['id'];
        $validationInsert = self::checkDeliverableValidation($description, 0, $companySystemID, $contractId);
        if(!$validationInsert['status']) {
            return $validationInsert;
        }
        try{
            DB::beginTransaction();
            $insertDeliverables = [
                'uuid' => $uuid,
                'contractID' => $contractId,
                'description' => $description,
                'milestoneID' => $milestoneID,
                'startDate' => $formattedStartDate ? Carbon::parse($formattedStartDate) : null,
                'endDate' => $formattedEndDate ? Carbon::parse($formattedEndDate) : null,
                'companySystemID' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            ContractDeliverables::insert($insertDeliverables);

            DB::commit();
            return ['status' => true, 'message' => trans('common.deliverable_created_successfully')];
        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }
    public function updateDeliverable(Request $request, $id): array {
        $companySystemID = $request->input('companySystemID');
        $contractUuid = $request->input('contractUuid');
        $description = $request->input('description');
        $milestone = $request->input('milestone') ?? null;
        $formattedStartDate = $request->input('formattedStartDate') ?? null;
        $formattedEndDate = $request->input('formattedEndDate') ?? null;
        $milestoneID = 0;

        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();

        if(empty($contractMaster)) {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }
        $validationUpdate = self::checkDeliverableValidation($description, $id, $companySystemID, $contractMaster['id']);
        if(!$validationUpdate['status']) {
            return $validationUpdate;
        }
        if($milestone != null) {
            $milestoneExists = ContractMilestone::select('id')
                ->where('uuid', $milestone)
                ->where('companySystemID',$companySystemID)
                ->first();

            $milestoneID = $milestoneExists['id'] ?? 0;
        }
        try{
            DB::beginTransaction();
            $insertDeliverables = [
                'description' => $description,
                'milestoneID' => $milestoneID,
                'startDate' => $formattedStartDate ? Carbon::parse($formattedStartDate) : null,
                'endDate' => $formattedEndDate ? Carbon::parse($formattedEndDate) : null,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            ContractDeliverables::where('id', $id)->update($insertDeliverables);

            DB::commit();
            return ['status' => true, 'message' => trans('common.deliverable_updated_successfully')];
        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }
    public function checkDeliverableValidation($description, $id, $companySystemID, $contractID): array{
        $milestoneExists = ContractDeliverables::where('description', $description)
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->when($id > 0, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->exists();
        if($milestoneExists) {
            return [
                'status' => false,
                'message' => trans('common.deliverable_already_exists')
            ];
        }
        return [
            'status' => true,
            'message' => trans('common.validation_checked_successfully')
        ];
    }

    public function getDeliverablesExcelData(Request $request): array {
        $contractUuid = $request->input('contractUUid') ?? null;
        $companySystemID = $request->input('companySystemID') ?? 0;
        $startDateText = 'Start Date';
        $endDateText = 'End Date';

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
        $deliverables = ContractDeliverables::select('milestoneID', 'description', 'startDate', 'endDate')
            ->with([
                'milestone' => function ($q) {
                    $q->select('id', 'title');
                }
            ])
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->get();

        $data[0]['Description'] = "Description";
        $data[0]['Milestone'] = "Milestone";
        $data[0][$startDateText] = $startDateText;
        $data[0][$endDateText] = $endDateText;
        if($deliverables) {
            foreach ($deliverables as $key => $deliverable){
                $data[$key+1]['Description'] = $deliverable['description'];
                $data[$key+1]['Milestone'] = $deliverable['milestone']['title'] ?? '-';
                $data[$key+1][$startDateText] = $deliverable['startDate'] ?
                    Carbon::parse($deliverable['startDate'])->format('Y-m-d') : '-';
                $data[$key+1][$endDateText] = $deliverable['endDate'] ?
                    Carbon::parse($deliverable['endDate'])->format('Y-m-d') : '-';
            }
        }
        return [
            'status' => true,
            'message' => trans('common.successfully_data_loaded'),
            'deliverable' => $data
        ];
    }
}
