<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractMileStoneAmd;
use App\Models\CMContractStatusHistoryAmd;
use App\Models\Company;
use App\Models\ContractHistory;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\CurrencyMaster;
use App\Models\MilestoneStatusHistory;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\CrudOperations;
use Illuminate\Support\Facades\Log;
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

    public function getContractMilestones($id, Request $request)
    {
        $companySystemID = $request->input('selectedCompanyID');
        $contractMaster = ContractMaster::where('uuid', $id)
            ->where('companySystemID', $companySystemID)
            ->select('*')
            ->first();

        if (empty($contractMaster)) {
            return ['status' => false, 'message' => trans('common.contract_not_found')];
        }

        $contractMilestones = ContractMilestone::getContractMilestone($contractMaster['id'], $companySystemID);

        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);

        $response['contractMilestones'] = $contractMilestones;
        $response['decimalPlaces'] = $decimalPlaces;
        $response['contractMaster'] = $contractMaster;

        return [
            'status' => true,
            'message' => trans('common.contract_milestone_retrieved_successfully'),
            'data' => $response
        ];
    }

    public function createMilestone(Request $request)
    {
        $companySystemID = $request->input('companySystemID');
        $contractUuid = $request->input('contractUuid');
        $formData = $request->input('formData');
        $title = $formData['title'];
        $description = $formData['description'];
        $dueDate = $request->input('formattedDueDate');
        $status = $formData['status'];
        $uuid = bin2hex(random_bytes(16));
        $amendment = $request->input('amendment');
        $model = $amendment ? CMContractMileStoneAmd::class : ContractMilestone::class;
        $contractHistoryId = 0;


        if ($model::where('uuid', $uuid)->exists())
        {
            return [
                'status' => false,
                'message' => trans('common.contract_document_uuid_already_exists')
            ];
        }

        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();

        if (empty($contractMaster))
        {
            return [
                'status' => false,
                'message' => trans('common.contract_code_not_found')
            ];
        }

        if($amendment)
        {
            $contractHistory = ContractManagementUtils::getContractHistoryData($request->input('historyUuid'));
            if (empty($contractHistory))
            {
                return [
                    'status' => false,
                    'message' => trans('common.contract_history_not_found')
                ];
            }
            $contractHistoryId = $contractHistory['id'];
        }



        $contractId = $contractMaster['id'];
        $validationInsert = self::checkMilestoneValidation($title, 0, $companySystemID, $contractId, $amendment, $contractHistoryId);
        if (!$validationInsert['status'])
        {
            return $validationInsert;
        }
        try
        {
            DB::beginTransaction();
            $insertMilestone = [
                'uuid' => $uuid,
                'contractID' => $contractId,
                'title' => $title,
                'description' => $description,
                'due_date' => $dueDate ? Carbon::parse($dueDate) : null,
                'status' => $status,
                'companySystemID' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];

            if($amendment)
            {
                $insertMilestone['contract_history_id'] = $contractHistory->id;
            }


            $model::insert($insertMilestone);

            DB::commit();
            return ['status' => true, 'message' => trans('common.milestone_created_successfully')];
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }

    public function updateMilestone($input, $contractMilestone): array
    {
        $companySystemID = $input['companySystemID'];
        $amendment = $input['amendment'];
        $formData = $input['formData'];
        $contractUuid = $input['contractUuid'] ?? null;
        $title = $formData['title'];
        $description = $formData['description'];
        $dueDate = $input['formattedDueDate'];
        $status = $formData['status'];
        $statusYN = $input['statusYN'];
        $id = $amendment ? $contractMilestone['amd_id'] :$contractMilestone['id'];
        $previousStatus = $statusYN && $contractMilestone['status'] ? $contractMilestone['status'] : 0;

        $contractHistoryId = 0;
        $contractMaster = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();
        $modelName = $amendment ? CMContractMileStoneAmd::class : ContractMilestone::class;
        $colName = $amendment ? 'amd_id' : 'id';
        $modelNameStatus = $amendment ? CMContractStatusHistoryAmd::class : MilestoneStatusHistory::class;
        if(empty($contractMaster))
        {
            return [
                'status' => false,
                'message' => trans('common.contract_code_not_found')
            ];
        }

        if($amendment)
        {
            $contractHistory = ContractManagementUtils::getContractHistoryData($input['historyUuid']);
            if (empty($contractHistory))
            {
                return [
                    'status' => false,
                    'message' => trans('common.contract_history_not_found')
                ];
            }

            $contractHistoryId = $contractHistory['id'];
        }

        $validationUpdate = self::checkMilestoneValidation
        (
            $title, $id, $companySystemID, $contractMaster['id'] ,$amendment,$contractHistoryId
        );

        if(!$validationUpdate['status'])
        {
            return $validationUpdate;
        }
        try
        {
            DB::beginTransaction();
            $insertMilestone = [
                'title' => $title,
                'description' => $description,
                'due_date' => $dueDate ? Carbon::parse($dueDate) : null,
                'status' => $status,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            $modelName::where($colName, $id)->update($insertMilestone);

            if($statusYN)
            {
                $historyInsert = [
                    'uuid' => bin2hex(random_bytes(16)),
                    'contractID' => $contractMaster['id'],
                    'milestoneID' => $id,
                    'changedFrom' => $previousStatus,
                    'changedTo' => $status,
                    'companySystemID' => $companySystemID,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];

                if($amendment)
                {
                    $historyInsert['contract_history_id'] = $contractHistoryId;
                }

                $modelNameStatus::insert($historyInsert);
            }

            DB::commit();
            return ['status' => true, 'message' => trans('common.milestone_updated_successfully')];
        } catch (\Exception $ex)
        {
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }

    }
    public function checkMilestoneValidation
    (
        $title, $id, $companySystemID, $contractID ,$amendment,$contractHistoryId
    ): array
    {

        if($amendment)
        {
            $milestoneExistsAmd = CMContractMileStoneAmd::where('title', $title)
                ->where('contractID', $contractID)
                ->where('companySystemID', $companySystemID)
                ->where('contract_history_id', $contractHistoryId)
                ->when($id > 0, function ($q) use ($id)
                {
                    $q->where('amd_id', '!=', $id);
                })
                ->exists();
            if ($milestoneExistsAmd)
            {
                return [
                    'status' => false,
                    'message' => trans('common.milestone_already_exists')
                ];
            }
        }
        else
        {
            $milestoneExists = ContractMilestone::where('title', $title)
                ->where('contractID', $contractID)
                ->where('companySystemID', $companySystemID)
                ->when($id > 0, function ($q) use ($id) {
                    $q->where('id', '!=', $id);
                })
                ->exists();
            if ($milestoneExists) {
                return [
                    'status' => false,
                    'message' => trans('common.milestone_already_exists')
                ];
            }
        }

        return [
            'status' => true,
            'message' => trans('common.validation_checked_successfully')
        ];
    }

    public function getMilestoneExcelData(Request $request): array
    {
        $contractUuid = $request->input('contractUUid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;

        $contractMaster = ContractMaster::select('id')
            ->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();
        if (empty($contractMaster)) {
            return [
                'status' => false,
                'message' => trans('common.contract_code_not_found')
            ];
        }
        $contractID = $contractMaster['id'];
        $deliverables = ContractMilestone::getContractMilestone($contractID, $companySystemID);

        $data[0]['Milestone Title'] = "Milestone Title";
        $data[0]['Milestone Description'] = "Milestone Description";
        $data[0]['Milestone Due Date'] = "Milestone Due Date";
        $data[0]['Status'] = "Status";

        if ($deliverables) {
            foreach ($deliverables as $key => $deliverable) {
                $status = 'Pending';
                if ($deliverable['status'] == 1)
                {
                    $status = 'In Progress';
                }
                if ($deliverable['status'] == 2)
                {
                    $status = 'Completed';
                }
                if ($deliverable['status'] == -1)
                {
                    $status = 'Not Started';
                }
                $data[$key + 1]['Milestone Title'] = $deliverable['title'] ?? '-';
                $data[$key + 1]['Milestone Description'] = $deliverable['description'] ?? '-';
                $data[$key + 1]['Milestone Due Date'] = $deliverable['due_date'] ?
                    Carbon::parse($deliverable['due_date'])->format('Y-m-d') : '-';
                $data[$key + 1]['Status'] = $status;
            }
        }
        return [
            'status' => true,
            'message' => trans('common.successfully_data_loaded'),
            'milestones' => $data
        ];
    }

    public function getMileStone($contractId)
    {
        return $this->model->getMileStone($contractId);
    }

    public function getMilestoneDueDate($request)
    {
        $uuid = $request->input('uuid');
        return  ContractMilestone::getMilestoneDueDate($uuid);
    }
}
