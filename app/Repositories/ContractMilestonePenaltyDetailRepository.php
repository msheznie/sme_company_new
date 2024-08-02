<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\Company;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\ContractMilestonePenaltyDetail;
use App\Models\ContractMilestonePenaltyMaster;
use App\Models\ContractMilestoneRetention;
use App\Models\ContractOverallPenalty;
use App\Models\CurrencyMaster;
use App\Models\MilestonePaymentSchedules;
use App\Repositories\BaseRepository;
use App\Traits\CrudOperations;
use App\Utilities\ContractManagementUtils;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

/**
 * Class ContractMilestonePenaltyDetailRepository
 * @package App\Repositories
 * @version July 28, 2024, 10:45 am +04
*/

class ContractMilestonePenaltyDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'milestone_penalty_master_id',
        'milestone_title',
        'milestone_amount',
        'penalty_percentage',
        'penalty_amount',
        'penalty_start_date',
        'penalty_frequency',
        'due_in',
        'due_penalty_amount',
        'status',
        'company_id',
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
        return ContractMilestonePenaltyDetail::class;
    }

    protected function getModel()
    {
        return new ContractMilestonePenaltyDetail();
    }

    public function createMilestonePenaltyDetail(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contract_id'] ?? null;
        $companyId = $input['selectedCompanyID'] ?? 0;

        return DB::transaction(function () use ($contractUuid, $companyId, $input)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if(empty($contract))
            {
                throw new CommonException('Contract ID not found.');
            }

            $milestonePenaltyMaster = ContractMilestonePenaltyMaster::getMilestonePenaltyMaster(
                $contract['id'], $companyId);

            $totalRecords = ContractManagementUtils::getMilestonesWithAmount($contract['id'], $companyId)->count();
            if($totalRecords == 0)
            {
                throw new CommonException(trans('common.add_new_milestones'));
            }

            $recordsWithMilestoneId = ContractMilestonePenaltyDetail::getRecordsWithMilestone(
                $contract['id'],$companyId)->count();
            if($totalRecords == $recordsWithMilestoneId)
            {
                throw new CommonException(trans('common.existing_milestones_are_already_used_for_penalties'));
            }

            $milestoneUuid = $input['milestone_title'];
            $milestone = ContractMilestone::getContractMilestoneWithAmount($milestoneUuid);
            $duplicateMilestone = ContractMilestonePenaltyDetail::getMilestoneTitle(
                $milestone['id'], $contract['id'], $companyId);

            if ($duplicateMilestone)
            {
                throw new CommonException(trans('common.milestone_titles_cannot_be_duplicated'));
            }

            if(empty($milestonePenaltyMaster))
            {
                throw new CommonException('Please fill all the mandatory fields in milestone master.');
            }

            if($milestonePenaltyMaster['minimum_penalty_percentage'] > $input['penalty_percentage'] ||
                $milestonePenaltyMaster['maximum_penalty_percentage'] < $input['penalty_percentage'])
            {
                throw new CommonException('Penalty percentage should be within the min and max penalty percentages.');
            }
            else
            {
                $data = [
                    'uuid' => ContractManagementUtils::generateUuid(),
                    'contract_id' => $contract['id'],
                    'milestone_penalty_master_id' => $milestonePenaltyMaster['id'],
                    'milestone_title' => $milestone['id'],
                    'milestone_amount' => (float) $input['milestone_amount'],
                    'penalty_percentage' => (float) $input['penalty_percentage'],
                    'penalty_amount' => (float) $input['penalty_amount'],
                    'penalty_start_date' => $input['penalty_start_date'] ?
                        Carbon::parse($input['penalty_start_date']) : null,
                    'penalty_frequency' => $input['penalty_frequency'],
                    'due_in' => $input['due_in'] ?? 0,
                    'status' => 0,
                    'company_id' => $companyId,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];
                ContractMilestonePenaltyDetail::create($data);
            }
        });
    }

    public function getMilestonePenaltyDetails(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contractUuid'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }

        $penaltyMaster = ContractMilestonePenaltyMaster::getMilestonePenaltyMaster(
            $contract['id'], $companySystemID);
        $milestonePenaltyMasterId = $penaltyMaster['id'] ?? null;

        $duePenaltyAmount = [];
        if($penaltyMaster)
        {
            $duePenaltyAmount = $this->duePenaltyAmountCalculation($penaltyMaster, $contract['id'], $companySystemID);
        }

        $penaltyAmountDict = [];
        foreach ($duePenaltyAmount as $penalty)
        {
            $penaltyAmountDict[$penalty['id']] = $penalty['duePenaltyAmount'];
        }

        $languages =  $this->model->ContractMilestonePenaltyDetail(
            $contract['id'], $companySystemID, $milestonePenaltyMasterId);
        return DataTables::eloquent($languages)
            ->addColumn('duePenaltyAmount', function ($row) use ($penaltyAmountDict)
            {
                return isset($penaltyAmountDict[$row->id]) ? $penaltyAmountDict[$row->id] : null;
            })
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }

    public function getMilestonePenaltyDetailFormData($contractUuid, $companyID)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $milestones = ContractManagementUtils::getMilestonesWithAmount($contract['id'], $companyID);
        $currencyId = Company::getLocalCurrencyID($companyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = CurrencyMaster::getCurrencyCode($currencyId);
        $milestoneExists = MilestonePaymentSchedules::checkContractHasMilestone($contract['id'], $companyID);
        $milestonePenaltyMaster = ContractMilestonePenaltyMaster::getMilestonePenaltyMaster(
            $contract['id'], $companyID);
        $billingFrequencies = ContractManagementUtils::getBillingFrequencies();

        return [
            'milestones' => $milestones,
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces,
            'milestoneExists' => $milestoneExists,
            'contractAmount' => $contract['contractAmount'] ?? 0,
            'milestonePenaltyDetailMaster' => $milestonePenaltyMaster['id'] ?? null,
            'billing_frequencies' => $billingFrequencies,
            'startDate' => $contract['startDate'],
        ];
    }

    public function getMilestoneAmount($contractUuid, $companyID, $milestoneUuid)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }

        $milestone = ContractMilestone::getContractMilestoneWithAmount($milestoneUuid);
        if(empty($milestone))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }

        if($milestone)
        {
            $duplicateMilestone = ContractMilestoneRetention::where('milestoneId', $milestone['id'])
                ->where('contractId', $contract['id'])
                ->where('companySystemId', $companyID)
                ->first();

            if ($duplicateMilestone)
            {
                return ['status' => false, 'message' => trans('common.milestone_titles_cannot_be_duplicated')];
            }
        }
        return  $milestone['milestonePaymentSchedules']['amount'];

    }

    private function duePenaltyAmountCalculation($penaltyMaster, $contractId, $companySystemID)
    {

        $duePenaltyAmounts = [];

        $milestonePenaltyDetails = ContractMilestonePenaltyDetail::getRecordsWithMilestone(
            $contractId, $companySystemID);
        foreach ($milestonePenaltyDetails as $penaltyDetails)
        {
            $penaltyStartDate = Carbon::parse($penaltyDetails['penalty_start_date']);
            $today = Carbon::now();
            $daysDifference = $penaltyStartDate->diffInDays($today);
            $penaltyCirculationFrequency = $penaltyDetails['penalty_frequency'];

            if($penaltyCirculationFrequency == 1)
            {
                $noOfInstallments = $daysDifference / 14;
            }
            if($penaltyCirculationFrequency == 2)
            {
                $noOfInstallments = $daysDifference / 7;
            }
            if($penaltyCirculationFrequency == 3)
            {
                $noOfInstallments = $daysDifference / 30;
            }
            if($penaltyCirculationFrequency == 4)
            {
                $noOfInstallments = $daysDifference / 365;
            }
            if($penaltyCirculationFrequency == 5)
            {
                $noOfInstallments = $daysDifference / 90;
            }
            if($penaltyCirculationFrequency == 6)
            {
                $noOfInstallments = $daysDifference / 180;
            }
            if($penaltyCirculationFrequency == 7)
            {
                $noOfInstallments = $daysDifference / $penaltyDetails['due_in'];
            }

            $noOfInstallments = floor($noOfInstallments);
            $calculatedAmount = $noOfInstallments * $penaltyDetails['penalty_amount'];
            $status = $penaltyDetails['status'];

            if($status == 1)
            {
                $duePenaltyAmount = $penaltyDetails['due_penalty_amount'];
            } else
            {
                if($penaltyMaster['maximum_penalty_amount'] == $calculatedAmount)
                {
                    $duePenaltyAmount = $calculatedAmount;
                }
                if($penaltyMaster['maximum_penalty_amount'] < $calculatedAmount)
                {
                    $duePenaltyAmount = $penaltyMaster['maximum_penalty_amount'];
                }
                if($penaltyMaster['maximum_penalty_amount'] > $calculatedAmount)
                {
                    $duePenaltyAmount = $calculatedAmount;
                }
            }

            $duePenaltyAmounts[] = [
                'id' => $penaltyDetails['id'],
                'duePenaltyAmount' => $duePenaltyAmount
            ];
        }

        return $duePenaltyAmounts;

    }

    public function updateMilestonePenaltyDetail($input, $id)
    {
        $contractUuid = $input['contract_id'];
        $companyId = $input['selectedCompanyID'];

        return DB::transaction(function () use ($input, $id, $contractUuid, $companyId)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if(empty($contract))
            {
                throw new CommonException(trans('common.contract_not_found'));
            }

            $milestone = ContractMilestone::checkContractMilestoneExists($input['milestone_title']);
            if(empty($milestone))
            {
                throw new CommonException('Contract milestone not found.');
            }

            $milestoneUuid = $input['milestone_title'];
            $milestone = ContractMilestone::getContractMilestoneWithAmount($milestoneUuid);

            $milestonePenaltyMaster = ContractMilestonePenaltyMaster::getMilestonePenaltyMaster(
                $contract['id'], $companyId);

            if($milestonePenaltyMaster['minimum_penalty_percentage'] > $input['penalty_percentage'] ||
                $milestonePenaltyMaster['maximum_penalty_percentage'] < $input['penalty_percentage'])
            {
                throw new CommonException('Penalty percentage should be within the min and max penalty percentages.');
            }
            else
            {
                $postData = [
                    'milestone_title' => $milestone['id'],
                    'milestone_amount' => (float) $input['milestone_amount'],
                    'penalty_percentage' => (float) $input['penalty_percentage'],
                    'penalty_amount' => (float) $input['penalty_amount'],
                    'penalty_start_date' => $input['penalty_start_date'] ?
                        Carbon::parse($input['penalty_start_date']) : null,
                    'penalty_frequency' => $input['penalty_frequency'],
                    'due_in' => $input['due_in'] ?? 0,
                    'status' => 0,
                    'updated_by' => General::currentEmployeeId(),
                    'updated_at' => Carbon::now()
                ];
                ContractMilestonePenaltyDetail::where('id', $id)->update($postData);
            }
        });

    }

    public function updateMilestonePenaltyStatus($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
            $postData = [
                'due_penalty_amount' => $input['duePenaltyAmount'],
                'status' => $input['status'],
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            ContractMilestonePenaltyDetail::where('id', $id)->update($postData);
        });
    }

    public function exportMilestonePenalty(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contractUuid'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);
        $contractId = $contract['id'];

        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);

        $milestonePenaltyMaster = ContractMilestonePenaltyMaster::getMilestonePenaltyMaster(
            $contract['id'], $companySystemID);

        $milestonePenaltyMasterId = $milestonePenaltyMaster['id'] ?? null;


        $lotData = $this->model->ContractMilestonePenaltyDetail(
            $contractId, $companySystemID, $milestonePenaltyMasterId)->get();
        $data[0]['Milestone Title'] = "Milestone Title";
        $data[0]['Milestone Amount'] = "Milestone Amount";
        $data[0]['Penalty Percentage'] = "Penalty Percentage";
        $data[0]['Penalty Amount'] = "Penalty Amount";
        $data[0]['Start Date'] = "Start Date";
        $data[0]['Penalty Frequency'] = "Penalty Frequency";
        $data[0]['Due In'] = "Due In";
        $data[0]['Due Penalty Amount'] = "Due Penalty Amount";
        $data[0]['Status'] = "Status";

        if ($lotData) {
            $count = 1;
            foreach ($lotData as $value) {
                $data[$count]['Milestone Title'] =
                    isset($value['milestone_title']) ? preg_replace('/^=/', '-', $value['milestone']['title']) : '-';
                $data[$count]['Milestone Amount'] =
                    isset($value['milestone_title']) ? number_format(
                        (float)preg_replace('/^=/', '-', $value['milestone']['milestonePaymentSchedules']['amount']),
                        $decimalPlaces, '.', '') : '-';
                $data[$count]['Penalty Percentage'] =
                    isset($value['penalty_percentage']) ? preg_replace('/^=/', '-', $value['penalty_percentage']) : '-';
                $data[$count]['Penalty Amount'] =
                    isset($value['penalty_amount']) ? number_format(
                        (float)preg_replace('/^=/', '-', $value['penalty_amount']), $decimalPlaces, '.', '') : '-';
                $data[$count]['Start Date'] =
                    isset($value['penalty_start_date']) ? preg_replace(
                        '/^=/', '-', Carbon::parse($value['penalty_start_date'])) : '-';
                $data[$count]['Penalty Frequency'] =
                    isset($value['penalty_frequency']) ? preg_replace(
                        '/^=/', '-', $value['billingFrequencies']['description']) : '-';
                $data[$count]['Due In'] =
                    isset($value['due_in']) ? preg_replace('/^=/', '-', $value['due_in']) : '-';
                $data[$count]['Due Penalty Amount'] =
                    isset($value['due_penalty_amount']) ? preg_replace('/^=/', '-', $value['due_penalty_amount']) : '-';
                $data[$count]['Status'] =
                    isset($value['status']) ? preg_replace('/^=/', '-', $value['status']) : '-';
                $count++;
            }
        }
        return $data;
    }
}
