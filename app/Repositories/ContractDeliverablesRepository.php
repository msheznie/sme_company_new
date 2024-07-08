<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractDeliverableAmd;
use App\Models\CMContractMileStoneAmd;
use App\Models\ContractDeliverables;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
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
        'companySystemID',
        'created_by',
        'updated_by',
        'dueDate'
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

    protected function getModel()
    {
        return new ContractDeliverables();
    }

    public function getDeliverables($contractID, $companySystemID): array
    {
        $deliverables = ContractDeliverables::getDeliverables($contractID, $companySystemID);
        $deliverablesArray = [];
        if ($deliverables)
        {
            foreach($deliverables as $key => $value)
            {
                $deliverablesArray[$key]['uuid'] = $value['uuid'];
                $deliverablesArray[$key]['title'] = $value['title'];
                $deliverablesArray[$key]['description'] = $value['description'];
                $deliverablesArray[$key]['dueDate'] = $value['dueDate'];
                $deliverablesArray[$key]['milestoneUuid'] = $value['milestone']['uuid'] ?? null;
            }
        }
        return $deliverablesArray;
    }

    public function createDeliverables(Request $request): array
    {
        $companySystemID = $request->input('companySystemID');
        $contractUuid = $request->input('contractUuid');
        $description = $request->input('description');
        $title = $request->input('title');
        $milestone = $request->input('milestone') ?? null;
        $formattedDueDate = $request->input('formattedDueDate') ?? null;
        $uuid = bin2hex(random_bytes(16));
        $milestoneID = 0;

        if(ContractDeliverables::where('uuid', $uuid)->exists())
        {
            return [
                'status' => false,
                'message' => trans('common.deliverable_uuid_already_exists')
            ];
        }

        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();
        if(empty($contractMaster))
        {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }
        if($milestone != null)
        {
            $milestoneExists = ContractMilestone::select('id')
                ->where('uuid', $milestone)
                ->where('companySystemID',$companySystemID)
                ->first();

            $milestoneID = $milestoneExists['id'] ?? 0;
        }
        $contractId = $contractMaster['id'];
        $validationInsert = self::checkDeliverableValidation($title, $description, 0, $companySystemID, $contractId);
        if(!$validationInsert['status'])
        {
            return $validationInsert;
        }
        try
        {
            DB::beginTransaction();
            $insertDeliverables = [
                'uuid' => $uuid,
                'contractID' => $contractId,
                'title' => $title,
                'description' => $description,
                'milestoneID' => $milestoneID,
                'dueDate' => $formattedDueDate ? Carbon::parse($formattedDueDate) : null,
                'companySystemID' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            ContractDeliverables::insert($insertDeliverables);

            DB::commit();
            return ['status' => true, 'message' => trans('common.deliverable_created_successfully')];
        } catch (\Exception $ex)
        {
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }
    public function updateDeliverable(Request $request, $id): array
    {
        $companySystemID = $request->input('companySystemID');
        $contractUuid = $request->input('contractUuid');
        $description = $request->input('description');
        $title = $request->input('title');
        $milestone = $request->input('milestone') ?? null;
        $formattedDueDate = $request->input('formattedDueDate') ?? null;
        $milestoneID = 0;
        $amendment = $request->input('amendment');
        $historyId = 0;
        $modelMilestone = $amendment ? CMContractMileStoneAmd::class : ContractMilestone::class;
        $model = $amendment ? CMContractDeliverableAmd::class : ContractDeliverables::class;

        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();

        if(empty($contractMaster))
        {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }


        if($amendment)
        {
            $contractHistory =ContractManagementUtils::getContractHistoryData($request->input('historyUuid'));
            $historyId = $contractHistory['id'];
        }

        $validationUpdate =
            self::checkDeliverableValidation($title, $description, $id, $companySystemID, $contractMaster['id']);
        if(!$validationUpdate['status'])
        {
            return $validationUpdate;
        }

        if($milestone != null)
        {
            $milestoneExists = $modelMilestone::select('id')
                ->where('uuid', $milestone)
                ->where('companySystemID',$companySystemID)
                ->first();

            $milestoneID = $milestoneExists['id'] ?? 0;
        }

        try
        {
            DB::beginTransaction();
            $insertDeliverables = [
                'title' => $title,
                'description' => $description,
                'milestoneID' => $milestoneID,
                'dueDate' => $formattedDueDate ? Carbon::parse($formattedDueDate) : null,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            if($amendment)
            {
                CMContractDeliverableAmd::where('uuid',$request->input('uuid'))
                ->where('contract_history_id', $historyId)->update($insertDeliverables);
            }else
            {
                ContractDeliverables::where('id', $id)->update($insertDeliverables);
            }


            DB::commit();
            return ['status' => true, 'message' => trans('common.deliverable_updated_successfully')];
        } catch (\Exception $ex)
        {
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }
    public function checkDeliverableValidation($title, $description, $id, $companySystemID, $contractID): array
    {
        $exists = ContractDeliverables::checkDeliverableExist($title, $description, $id, $companySystemID, $contractID);

        if($exists)
        {
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

    public function getDeliverablesExcelData(Request $request): array
    {
        $contractUuid = $request->input('contractUUid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        $dueDateText = 'Due Date';

        $contractMaster = ContractMaster::select('id')
            ->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();
        if(empty($contractMaster))
        {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }
        $contractID = $contractMaster['id'];
        $deliverables = ContractDeliverables::getDeliverables($contractID, $companySystemID);

        $data[0]['Milestone'] = "Milestone";
        $data[0]['Title'] = "Title";
        $data[0]['Description'] = "Description";
        $data[0][$dueDateText] = $dueDateText;
        if($deliverables)
        {
            foreach ($deliverables as $key => $deliverable)
            {
                $data[$key+1]['Milestone'] = $deliverable['milestone']['title'] ?? '-';
                $data[$key+1]['Title'] = $deliverable['title'];
                $data[$key+1]['Description'] = $deliverable['description'];
                $data[$key+1][$dueDateText] = $deliverable['dueDate'] ?
                    Carbon::parse($deliverable['dueDate'])->format('Y-m-d') : '-';
            }
        }
        return [
            'status' => true,
            'message' => trans('common.successfully_data_loaded'),
            'deliverable' => $data
        ];
    }

    public function getContractDeliverableRepo($contractId)
    {
        return $this->model->getContractDeliverable($contractId);
    }
}
