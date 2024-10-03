<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Helpers\General;
use App\Models\CMContractOverallRetentionAmd;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use Illuminate\Contracts\Foundation\Application;
use Exception;
use Illuminate\Support\Facades\Log;
/**
 * Class CMContractOverallRetentionAmdRepository
 * @package App\Repositories
 * @version July 3, 2024, 3:33 pm +04
 */
class CMContractOverallRetentionAmdRepository extends BaseRepository
{
    protected $contractOverallRetentionRepo;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function contractOverallRepo()
    {
        if (!$this->contractOverallRetentionRepo)
        {
            $this->contractOverallRetentionRepo = $this->app->make(ContractOverallRetentionRepository::class);
        }
        return $this->contractOverallRetentionRepo;
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'retention_id',
        'uuid',
        'contractId',
        'contractAmount',
        'retentionPercentage',
        'retentionAmount',
        'startDate',
        'dueDate',
        'retentionWithholdPeriod',
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
        return CMContractOverallRetentionAmd::class;
    }

    public function save($historyId, $contractId, $input)
    {
        try
        {
           $getOverall = $this->contractOverallRepo()->getContractOverall($contractId, $input['selectedCompanyID']);
            if($getOverall)
            {
                $levelNo = $this->model->getLevelNo($getOverall['id'], $contractId);
                $recordData = $getOverall->toArray();
                $recordData['level_no'] = $levelNo;
                $recordData['contract_history_id'] = $historyId;
                $recordData['retention_id'] = $getOverall['id'];
                $this->model->create($recordData);
            }
        } catch
        (Exception $e)
        {
            GeneralService::sendException("Contract Retention failed :" . $e->getMessage());
        }
    }
    public function saveInitialRecord($contractId, $selectedCompanyID)
    {
        try
        {
            $getOverall = $this->contractOverallRepo()->getContractOverall($contractId, $selectedCompanyID);
            if($getOverall)
            {
                $levelNo = $this->model->getLevelNo($getOverall['id'], $contractId);
                $recordData = $getOverall->toArray();
                $recordData['level_no'] = $levelNo;
                $recordData['contract_history_id'] = null;
                $recordData['retention_id'] = $getOverall['id'];
                $recordData['created_by'] = General::currentEmployeeId();
                $this->model->create($recordData);
            }
        }
        catch
        (Exception $e)
        {
            GeneralService::sendException("Contract Retention failed :" . $e->getMessage());
        }
    }
}
