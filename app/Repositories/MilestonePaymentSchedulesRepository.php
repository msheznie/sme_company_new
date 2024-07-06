<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\Company;
use App\Models\ContractMilestone;
use App\Models\CurrencyMaster;
use App\Models\MilestonePaymentSchedules;
use App\Repositories\BaseRepository;
use App\Traits\CrudOperations;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

/**
 * Class MilestonePaymentSchedulesRepository
 * @package App\Repositories
 * @version June 27, 2024, 9:13 am +04
*/

class MilestonePaymentSchedulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'milestone_id',
        'description',
        'percentage',
        'amount',
        'payment_due_date',
        'actual_payment_date',
        'milestone_due_date',
        'currency_id',
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
        return MilestonePaymentSchedules::class;
    }
    protected function getModel()
    {
        return new MilestonePaymentSchedules();
    }
    public function getPaymentScheduleFormData($contractUuid, $companyID, $uuid)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companyID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $milestones = ContractManagementUtils::getPaymentScheduleMilestone($contract['id'], $companyID, $uuid);
        $currencyId = Company::getLocalCurrencyID($companyID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);
        $currencyCode = CurrencyMaster::getCurrencyCode($currencyId);
        $milestoneExists = ContractMilestone::checkContractHasMilestone($contract['id']);
        $totalPSAmount = MilestonePaymentSchedules::getTotalAmount($contract['id']);
        $totalPSPercentage = MilestonePaymentSchedules::getTotalPercentage($contract['id']);
        return [
            'milestones' => $milestones,
            'currencyCode' => $currencyCode,
            'decimalPlace' => $decimalPlaces,
            'milestoneExists' => $milestoneExists,
            'contractAmount' => $contract['contractAmount'] ?? 0,
            'totalPSAmount' => $totalPSAmount ?? 0,
            'totalPSPercentage' => $totalPSPercentage ?? 0
        ];
    }

    public function createPaymentSchedule($input, $contractUuid, $selectedCompanyID)
    {
        return DB::transaction(function () use ($input, $contractUuid, $selectedCompanyID)
        {
            $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
            if(empty($contract))
            {
                throw new CommonException('Contract ID not found.');
            }
            $milestone = ContractMilestone::checkContractMilestoneExists($input['milestone_id']);
            if(empty($milestone))
            {
                throw new CommonException('Contract milestone not found.');
            }

            $currencyId = Company::getLocalCurrencyID($selectedCompanyID);
            $postData = [
                'uuid' => ContractManagementUtils::generateUuid(),
                'contract_id' => $contract['id'],
                'milestone_id' => $milestone['id'],
                'description' => $input['description'],
                'percentage' => $input['percentage'],
                'amount' => (float) $input['amount'],
                'payment_due_date' => $input['payment_due_date'] ? Carbon::parse($input['payment_due_date']) : null,
                'actual_payment_date' => $input['actual_payment_date'] ?
                    Carbon::parse($input['actual_payment_date']) : null,
                'milestone_due_date' => $input['milestone_due_date'] ?
                    Carbon::parse($input['milestone_due_date']) : null,
                'milestone_status' => $input['milestone_status'] ?? 0,
                'currency_id' => $currencyId,
                'company_id' => $selectedCompanyID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            MilestonePaymentSchedules::insert($postData);
        });
    }
    public function getMilestonePaymentSchedules($request)
    {
        $input = $request->all();
        $searchKeyword = $request->input('search.value');
        $companyId =  $input['selectedCompanyID'];
        $contractUuid = $input['contractUuid'] ?? null;
        $languages =  $this->model->milestonePaymentSchedules($searchKeyword, $companyId, $contractUuid);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }
    public function updateMilestonePaymentSchedule($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
            $milestone = ContractMilestone::checkContractMilestoneExists($input['milestone_id']);
            if(empty($milestone))
            {
                throw new CommonException('Contract milestone not found.');
            }

            $postData = [
                'milestone_id' => $milestone['id'],
                'description' => $input['description'],
                'percentage' => $input['percentage'],
                'amount' => (float) $input['amount'],
                'payment_due_date' => $input['payment_due_date'] ? Carbon::parse($input['payment_due_date']) : null,
                'actual_payment_date' => $input['actual_payment_date'] ?
                    Carbon::parse($input['actual_payment_date']) : null,
                'milestone_due_date' => $input['milestone_due_date'] ?
                    Carbon::parse($input['milestone_due_date']) : null,
                'milestone_status' => $input['milestone_status'] ?? 0,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            MilestonePaymentSchedules::where('id', $id)->update($postData);
        });
    }
}
