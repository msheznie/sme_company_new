<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\Company;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\ContractMilestoneRetention;
use App\Models\ContractMilestoneRetentionAmd;
use App\Models\ContractOverallRetention;
use App\Models\CurrencyMaster;
use App\Repositories\BaseRepository;
use App\Services\ContractAmendmentOtherService;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use App\Traits\CrudOperations;

/**
 * Class ContractMilestoneRetentionRepository
 * @package App\Repositories
 * @version April 29, 2024, 11:28 pm +04
*/

class ContractMilestoneRetentionRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
        'uuid',
        'contractId',
        'milestoneId',
        'retentionPercentage',
        'retentionAmount',
        'startDate',
        'dueDate',
        'withholdPeriod',
        'paymentStatus',
        'companySystemId',
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
        return ContractMilestoneRetention::class;
    }
    protected function getModel()
    {
        return new ContractMilestoneRetention();
    }

    public function getContractMilestoneRetentionData(Request $request)
    {

        $input = $request->all();
        $contractUuid = $input['contractId'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);
        $contractId = $contract['id'];

        $contractHistoryUuid = $input['contractHistoryUuid'] ?? null;
        if($contractHistoryUuid)
        {
            $contractHistory = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
            $contractHistoryID = $contractHistory['id'] ?? 0;
            $languages = ContractMilestoneRetentionAmd::getContractMilestoneRetentionAmd($contractHistoryID,
                $contractId, $companySystemID);
        } else
        {
            $languages =  $this->model->ContractMilestoneRetention($companySystemID, $contractId);
        }

        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }

    public function createMilestoneRetention(Request $request) {

        $input = $request->all();
        return DB::transaction(function () use ($input)
        {
            $contractUuid = $input['contractUuid'];
            $companySystemID = $input['selectedCompanyID'];
            $amendment = $input['amendment'] ?? false;
            $contractHistoryUuid = $input['contractHistoryUuid'] ?? null;
            $model = $amendment ? ContractMilestoneRetentionAmd::class : ContractMilestoneRetention::class;

            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);
            if(empty($contract))
            {
                GeneralService::sendException(trans('common.contract_id_not_found'));
            }
            $contractID = $contract['id'];
            $contractHistoryId = 0;
            if($amendment)
            {
                $contractHistory = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
                if(empty($contractHistory))
                {
                    GeneralService::sendException(trans('common.contract_history_not_found'));
                }
                $contractHistoryId = $contractHistory['id'];
            }

            $totalRecords = $amendment
                ? ContractAmendmentOtherService::getMilestoneAmountAmd($contractHistoryId)->count()
                : ContractManagementUtils::getMilestonesWithAmount($contractID, $companySystemID)->count();

            if($totalRecords == 0)
            {
                GeneralService::sendException(trans('common.add_new_milestones'));
            }
            $recordsWithMilestoneId = $amendment ? ContractMilestoneRetentionAmd::getMilestoneRetentionAmdCount(
                $contractHistoryId, $companySystemID) : ContractMilestoneRetention::getContractMilestoneRetentionCount(
                $contractID, $companySystemID);

            if($totalRecords == $recordsWithMilestoneId)
            {
                GeneralService::sendException(trans('common.existing_milestones_are_already_used_for_retentions'));
            }

            $uuid = ContractManagementUtils::generateUuid(16);
            $uuidExists = $model::checkUuidExists($uuid);
            if ($uuidExists)
            {
                GeneralService::sendException('Uuid already exists');
            }

            $data = [
                'uuid' => $uuid,
                'contractId' => $contractID,
                'companySystemId' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            if ($amendment)
            {
                $data['id'] = null;
                $data['contract_history_id'] = $contractHistoryId;
                $data['level_no'] = 1;
            }
            $milestoneRetention = $model::create($data);

            $milestoneRetentionId = $amendment ? $milestoneRetention->amd_id : $milestoneRetention->id;

            $firstRecord = $model::when($amendment, function ($q) use ($contractHistoryId)
            {
                $q->where('contract_history_id', $contractHistoryId);
            })->when(!$amendment, function ($q) use ($contractID)
            {
                $q->where('contractId', $contractID);
            })->where('companySystemId', $companySystemID)->first();


            if($firstRecord)
            {
                $model::when($amendment, function ($q) use ($milestoneRetentionId)
                {
                    $q->where('amd_id',$milestoneRetentionId);
                })->when(!$amendment, function ($q) use ($milestoneRetentionId)
                {
                    $q->where('id',$milestoneRetentionId);
                })
                    ->where('companySystemId', $companySystemID)
                    ->update(['retentionPercentage' => $firstRecord['retentionPercentage']]);
            }
        });
    }

    public function updateMilestoneRetention(Request $request)
    {
        $input = $request->all();
        return DB::transaction(function () use ($input)
        {
            $companySystemID = $input['selectedCompanyID'];
            $milestoneUuid = $input['data']['milestoneId'] ?? null;
            $milestoneRetentionUuid = $input['data']['uuid'];
            $amendment = $input['amendment'] ?? false;
            $contractHistoryUuid = $input['contractHistoryUuid'] ?? null;
            $model = $amendment ? ContractMilestoneRetentionAmd::class : ContractMilestoneRetention::class;

            $contractHistoryID = 0;
            if($amendment)
            {
                $contractHistory = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
                if(empty($contractHistory))
                {
                    GeneralService::sendException(trans('common.contract_not_found'));
                }
                $contractHistoryID = $contractHistory['id'];
            }
            $milestoneRetentionData = $amendment ? $model::getMilestoneRetentionAmdForUpdate($milestoneRetentionUuid,
                $contractHistoryID) : $model::getMilestoneRetentionForUpdate($milestoneRetentionUuid);
            if(empty($milestoneRetentionData))
            {
                GeneralService::sendException('Milestone retention not found.');
            }
            $retentionPercentage = $milestoneRetentionData['retentionPercentage'];

            $milestone = ContractMilestone::getContractMilestoneWithAmount($milestoneUuid);
            if(empty($milestone))
            {
                GeneralService::sendException(trans('common.milestone_not_found'));
            }
            $milestoneId = $milestone['id'];
            $contractId = $milestoneId['contractID'] ?? 0;
            $retentionAmount = $milestoneId
                ? $milestone['milestonePaymentSchedules']['amount'] * ($retentionPercentage / 100)
                : 0;

            if($input['value'] == 0)
            {
                $duplicateMilestone = $amendment ? $model::checkForDuplicateMilestoneRetentionAmd($milestoneId,
                    $contractHistoryID, $companySystemID) : $model::checkForDuplicateMilestoneRetention($milestoneId,
                    $contractId, $companySystemID);

                if ($duplicateMilestone)
                {
                    GeneralService::sendException(trans('common.milestone_titles_cannot_be_duplicated'));
                }
            }

            $startDate = $input['startDate'] ?? null;
            $dueDate = $input['dueDate'] ?? null;
            $withholdPeriod = 0;

            if ($startDate && $dueDate)
            {
                $startDate = (new Carbon($startDate))->format('Y-m-d');
                $dueDate = (new Carbon($dueDate))->format('Y-m-d');
                $newStartDate = new Carbon($startDate);
                $newDueDate = new Carbon($dueDate);

                $withholdPeriod = $newStartDate->diffInDays($newDueDate) . " Days";

                if ($newDueDate->lessThanOrEqualTo($newStartDate))
                {
                    GeneralService::sendException(trans('common.due_date_must_be_greater_than_start_date'));
                }
            } elseif ($startDate)
            {
                $startDate = (new Carbon($startDate))->format('Y-m-d');
            } else
            {
                $dueDate = $dueDate ? (new Carbon($dueDate))->format('Y-m-d') : null;
            }

            $data = [
                'milestoneId' => $milestoneId,
                'retentionAmount' => $retentionAmount,
                'startDate' =>  $startDate,
                'dueDate' =>  $dueDate,
                'withholdPeriod' =>  $withholdPeriod,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            $model::where('uuid',$milestoneRetentionUuid)
                ->when($amendment, function ($q) use ($contractHistoryID)
                {
                    $q->where('contract_history_id', $contractHistoryID);
                })
                ->where('companySystemId', $companySystemID)
                ->update($data);
        });
    }

    public function updateRetentionPercentage(Request $request)
    {
        $input = $request->all();
        return DB::transaction( function () use ($input)
        {
            $companySystemID = $input['selectedCompanyID'];
            $contractUuid = $input['contractUuid'];
            $retentionPercentage = $input['retentionPercentage'];
            $amendment = $input['amendment'] ?? false;
            $contractHistoryUuid = $input['contractHistoryUuid'] ?? false;
            $model = $amendment ? ContractMilestoneRetentionAmd::class : ContractMilestoneRetention::class;

            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);
            if(empty($contract))
            {
                GeneralService::sendException(trans('common.contract_not_found'));
            }
            $contractHistoryID = 0;
            if($amendment)
            {
                $contractHistory = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);
                if(empty($contractHistory))
                {
                    GeneralService::sendException(trans('common.contract_history_not_found'));
                }
                $contractHistoryID = $contractHistory['id'];
            }

            $milestoneRetentionData = $amendment
                ? $model::getContractMilestoneRetentionAmdData($contractHistoryID, $companySystemID)
                : $model::getContractMilestoneRetentionData($contract['id'], $companySystemID);

            $data = [
                'retentionPercentage' => $retentionPercentage,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            $amendment ? $model::updateMilestoneRetentionAmd($contractHistoryID, $companySystemID, $data)
                : $model::updateMilestoneRetention($contract['id'], $companySystemID, $data);

            foreach ($milestoneRetentionData as $milestoneRetention)
            {
                $retentionAmount = $milestoneRetention['milestone']['milestonePaymentSchedules']['amount'] ?? 0;
                if($retentionAmount)
                {
                    $model::where('id', $milestoneRetention['id'])
                        ->when($amendment, function ($q) use ($contractHistoryID)
                        {
                            $q->where('contract_history_id', $contractHistoryID);
                        })
                        ->update(['retentionAmount' => $retentionAmount * ($retentionPercentage / 100)]);
                }

            }
        });
    }

    public function exportMilestoneRetention(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contractUuid'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();

        $contractId = $contract['id'];

        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);


        $lotData = $this->model->ContractMilestoneRetention($companySystemID, $contractId)->get();
        $data[0]['Milestone Title'] = "Milestone Title";
        $data[0]['Milestone Amount'] = "Milestone Amount";
        $data[0]['Retention Percentage'] = "Retention Percentage";
        $data[0]['Retention Amount'] = "Retention Amount";
        $data[0]['Start Date'] = "Start Date";
        $data[0]['Due Date'] = "Due Date";
        $data[0]['Withhold Period'] = "Withhold Period";

        if ($lotData) {
            $count = 1;
            foreach ($lotData as $value) {
                $data[$count]['Milestone Title'] =
                    isset($value['milestoneId']) ? preg_replace('/^=/', '-', $value['milestone']['title']) : '-';
                $data[$count]['Milestone Amount'] =
                    isset($value['milestoneId']) ? number_format(
                        (float)preg_replace('/^=/', '-', $value['milestone']['milestonePaymentSchedules']['amount']),
                        $decimalPlaces, '.', '') : '-';
                $data[$count]['Retention Percentage'] =
                isset($value['retentionPercentage']) ? preg_replace('/^=/', '-', $value['retentionPercentage']) : '-';
                $data[$count]['Retention Amount'] =
                    isset($value['retentionAmount']) ? number_format(
                        (float)preg_replace('/^=/', '-', $value['retentionAmount']), $decimalPlaces, '.', '') : '-';
                $data[$count]['Start Date'] =
                    isset($value['startDate']) ? preg_replace('/^=/', '-', Carbon::parse($value['startDate'])) : '-';
                $data[$count]['Due Date'] =
                    isset($value['dueDate']) ? preg_replace('/^=/', '-', Carbon::parse($value['dueDate'])) : '-';
                $data[$count]['Withhold Period'] =
                    isset($value['withholdPeriod']) ? preg_replace('/^=/', '-', $value['withholdPeriod']) : '-';
                $count++;
            }
        }
        return $data;
    }

    public function checkMilestoneRetention(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contractId'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);

        if(empty($contract))
        {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }

        $totalRecords = ContractMilestoneRetention::checkRetentionExistWithMilestone($contract->id, $companySystemID);

        if($totalRecords != 0)
        {
            return [
                'status' => true,
                'message' => trans('common.milestone_payment_schedule')
            ];
        }

        return false;
    }
    public function getMilestoneRetention($contractID)
    {
        return $this->model->getMilestoneRetention($contractID);
    }
}
