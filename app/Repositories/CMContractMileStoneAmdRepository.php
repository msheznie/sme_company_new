<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Models\CMContractMileStoneAmd;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Contracts\Foundation\Application;
use Exception;
/**
 * Class CMContractMileStoneAmdRepository
 * @package App\Repositories
 * @version July 3, 2024, 6:01 am +04
*/

class CMContractMileStoneAmdRepository extends BaseRepository
{
    protected $contractMilestoneRepo;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function getContractMilestoneRepo()
    {
        if (!$this->contractMilestoneRepo)
        {
            $this->contractMilestoneRepo = $this->app->make(ContractMilestoneRepository::class);
        }
        return $this->contractMilestoneRepo;
    }
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'contract_history_id',
        'uuid',
        'contractID',
        'title',
        'status',
        'companySystemID',
        'created_by',
        'updated_by',
        'description'
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
        return CMContractMileStoneAmd::class;
    }

    public function save($historyId,$contractId)
    {
        try
        {
            $milestoneData = $this->getContractMilestoneRepo()->getMileStone($contractId);
            foreach ($milestoneData as $record)
            {
                $recordData = $record->toArray();
                $recordData['id'] = $record['id'];
                $recordData['contract_history_id'] = $historyId;
                $recordData['contractID'] = $contractId;
                $this->model->create($recordData);
            }
        }
        catch
        (Exception $e)
        {
            throw new ContractCreationException(trans('common.user_assign_data_saving_failed') . $e->getMessage());
        }
    }

    public function getContractMilestoneData($input)
    {
        $uuid = $input['historyUuid'];
        $companyId = $input['selectedCompanyID'];
        $historyData = ContractManagementUtils::getContractHistoryData($uuid);

        if (!$historyData)
        {
            throw new ContractCreationException('Contract history data not found');
        }

        $milestoneData = $this->model->getMilestoneAmd($historyData->id,$companyId);

        $data['milestoneData'] = $milestoneData;

        return $data;

    }
}
