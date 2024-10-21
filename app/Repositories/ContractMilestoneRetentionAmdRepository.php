<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractMilestoneRetentionAmd;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use Illuminate\Contracts\Foundation\Application;
use Exception;
use Illuminate\Http\Request;

/**
 * Class ContractMilestoneRetentionAmdRepository
 * @package App\Repositories
 * @version October 1, 2024, 11:59 am +04
*/

class ContractMilestoneRetentionAmdRepository extends BaseRepository
{
    protected $contractMilestoneRetentionRepo;
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }
    public function getContractMilestoneRetentionRepo()
    {
        if (!$this->contractMilestoneRetentionRepo)
        {
            $this->contractMilestoneRetentionRepo = $this->app->make(ContractMilestoneRetentionRepository::class);
        }
        return $this->contractMilestoneRetentionRepo;
    }
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'contract_history_id',
        'level_no',
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
        'updated_by',
        'deleted_by'
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
        return ContractMilestoneRetentionAmd::class;
    }
    public function save($historyId, $contractId)
    {
        try
        {
            $milestoneRetentions = $this->getContractMilestoneRetentionRepo()->getMilestoneRetention($contractId);

            foreach ($milestoneRetentions as $record)
            {
                $levelNo = $this->model->getLevelNo($record['uuid'], $contractId);
                $recordData = $record->toArray();
                $recordData['level_no'] = $levelNo;
                $recordData['id'] = $record['id'];
                $recordData['contract_history_id'] = $historyId;
                $recordData['contractId'] = $contractId;
                $recordData['created_by'] = General::currentEmployeeId();
                $this->model->create($recordData);
            }
        }
        catch
        (Exception $e)
        {
            GeneralService::sendException("Milestone retention data saving failed " . $e->getMessage());
        }
    }
    public function saveInitialRecord($contractId)
    {
        try
        {
            $milestoneRetentions = $this->getContractMilestoneRetentionRepo()->getMilestoneRetention($contractId);

            foreach ($milestoneRetentions as $record)
            {
                $levelNo = $this->model->getLevelNo($record['uuid'], $contractId);
                $recordData = $record->toArray();
                $recordData['level_no'] = $levelNo;
                $recordData['id'] = $record['id'];
                $recordData['contract_history_id'] = null;
                $recordData['contractId'] = $contractId;
                $recordData['created_by'] = General::currentEmployeeId();
                $this->model->create($recordData);
            }
        }
        catch
        (Exception $e)
        {
            GeneralService::sendException("Milestone retention data saving failed " . $e->getMessage());
        }
    }
}
