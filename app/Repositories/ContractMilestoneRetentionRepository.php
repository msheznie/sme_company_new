<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\Company;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\ContractMilestoneRetention;
use App\Models\ContractOverallRetention;
use App\Models\CurrencyMaster;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

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

    public function getContractMilestoneRetentionData(Request $request){

        $input = $request->all();
        $contractUuid = $input['contractId'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractMaster::select('id', 'contractAmount')->where('uuid', $contractUuid)->first();
        $contractId = $contract['id'];

        $languages =  $this->model->ContractMilestoneRetention($companySystemID, $contractId);
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

    public function createMilestoneRetention(Request $request) {

        $input = $request->all();
        $contractUuid = $input['contractUuid'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();

        if(empty($contract)) {
            return [
                'status' => false,
                'message' => trans('common.contract_id_not_found')
            ];
        }

        $totalRecords = ContractMilestone::where('contractID', $contract['id'])
            ->where('companySystemID', $companySystemID)->count();
        $recordsWithMilestoneId = ContractMilestoneRetention::
            where('contractId', $contract['id'])
            ->where('companySystemId', $companySystemID)
            ->count();

        if($totalRecords == $recordsWithMilestoneId){
            return [
                'status' => false,
                'message' => trans('common.milestone_titles_cannot_be_duplicated')
            ];
        }else{
            try{
                DB::beginTransaction();
                $data = [
                    'uuid' => bin2hex(random_bytes(16)),
                    'contractId' => $contract['id'],
                    'companySystemId' => $companySystemID,
                    'created_by' => General::currentEmployeeId(),
                    'created_at' => Carbon::now()
                ];
                $milestoneRetention = ContractMilestoneRetention::create($data);

                $milestoneRetentionId = $milestoneRetention->id;

                $firstRecord = ContractMilestoneRetention::where('contractId', $contract['id'])
                    ->where('companySystemId', $companySystemID)
                    ->first();

                if($firstRecord){
                    ContractMilestoneRetention::where('id',$milestoneRetentionId)
                        ->where('companySystemId', $companySystemID)
                        ->update(['retentionPercentage' => $firstRecord['retentionPercentage']]);
                }

                DB::commit();
                return [
                    'status' => true,
                    'message' => trans('common.contract_milestone_retention_created_successfully')
                ];
            } catch (\Exception $ex){
                DB::rollBack();
                return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
            }
        }
    }

    public function updateMilestoneRetention(Request $request){
        $input = $request->all();
        $companySystemID = $input['selectedCompanyID'];
        $milestoneUuid = $input['data']['milestoneId'] ?? null;
        $milestoneRetentionUuid = $input['data']['uuid'];

        $milestoneRetentionData = ContractMilestoneRetention::where('uuid', $milestoneRetentionUuid)->first();
        $retentionPercentage = $milestoneRetentionData['retentionPercentage'];
        $milestone = ContractMilestone::where('uuid', $milestoneUuid)->first();

        if($input['value'] == 0){
            $duplicateMilestone = ContractMilestoneRetention::where('milestoneId', $milestone['id'])
                ->where('contractId', $milestoneRetentionData['contractId'])
                ->where('companySystemId', $companySystemID)
                ->first();

            if ($duplicateMilestone) {
                return ['status' => false, 'message' => trans('common.milestone_titles_cannot_be_duplicated')];
            }
        }

        if($milestoneUuid){
            $milestoneId = $milestone['id'];
            $retentionAmount = $milestone['amount'] * ($retentionPercentage / 100);
        }else{
            $milestoneId = null;
            $retentionAmount = 0;
        }

        if($input['data']['startDate'] == null && $input['data']['dueDate'] == null){
            $startDate = $input['startDate'];
            $dueDate = $input['dueDate'];
            $withholdPeriod = 0;
        }

        if($input['startDate'] && $input['data']['dueDate'] == null){
            $startDate = (new Carbon($input['startDate']))->format('Y-m-d');
            $dueDate = $input['dueDate'];
            $withholdPeriod = 0;
        }

        if($input['dueDate'] && $input['data']['startDate'] == null){
            $startDate = $input['startDate'];
            $dueDate = (new Carbon($input['dueDate']))->format('Y-m-d');
            $withholdPeriod = 0;
        }

        if ($input['startDate'] && $input['dueDate']) {
            $startDate = (new Carbon($input['startDate']))->format('Y-m-d');
            $dueDate = (new Carbon($input['dueDate']))->format('Y-m-d');
            $newStartDate = new Carbon($startDate);
            $newDueDate = new Carbon($dueDate);
            $withholdPeriod = $newStartDate->diffInDays($newDueDate) . " Days";

            if ($newDueDate->lessThanOrEqualTo($newStartDate)) {
                return ['status' => false, 'message' => trans('common.due_date_must_be_greater_than_start_date')];
            }
        }

        DB::beginTransaction();
        try{

            $data = [
                'milestoneId' => $milestoneId,
                'retentionAmount' => $retentionAmount,
                'startDate' =>  $startDate,
                'dueDate' =>  $dueDate,
                'withholdPeriod' =>  $withholdPeriod,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            ContractMilestoneRetention::where('uuid',$milestoneRetentionUuid)
                ->where('companySystemId', $companySystemID)
                ->update($data);

            DB::commit();
            return ['status' => true, 'message' => trans('common.milestone_retention_updated_successfully')];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }

    public function updateRetentionPercentage(Request $request){
        $input = $request->all();
        $companySystemID = $input['selectedCompanyID'];
        $contractUuid = $input['contractUuid'];
        $retentionPercentage = $input['retentionPercentage'];

        $contract = ContractMaster::select('id')->where('uuid', $contractUuid)
            ->where('companySystemID', $companySystemID)
            ->first();

        $milestoneRetentionData = ContractMilestoneRetention::with(['milestone'])
            ->where('contractId', $contract['id'])
            ->where('companySystemId', $companySystemID)
            ->get();

        DB::beginTransaction();
        try{

            $data = [
                'retentionPercentage' => $retentionPercentage,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            ContractMilestoneRetention::where('contractId', $contract['id'])
                ->where('companySystemId', $companySystemID)
                ->update($data);

            foreach ($milestoneRetentionData as $milestoneRetention){
                $retentionAmount = $milestoneRetention['milestone']['amount'];

                ContractMilestoneRetention::where('id', $milestoneRetention['id'])
                    ->update(['retentionAmount' => $retentionAmount * ($retentionPercentage / 100)]);
            }

            DB::commit();
            return ['status' => true, 'message' => trans('common.retention_percentage_updated_successfully')];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }

    public function exportMilestoneRetention(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contractUuid'];
        $companySystemID = $input['companySystemID'];

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
                        (float)preg_replace('/^=/', '-', $value['milestone']['amount']), $decimalPlaces) : '-';
                $data[$count]['Retention Percentage'] =
                isset($value['retentionPercentage']) ? preg_replace('/^=/', '-', $value['retentionPercentage']) : '-';
                $data[$count]['Retention Amount'] =
                    isset($value['retentionAmount']) ? number_format(
                        (float)preg_replace('/^=/', '-', $value['retentionAmount']), $decimalPlaces) : '-';
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
}
