<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Models\CMContractUserAssignAmd;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Exception;
/**
 * Class CMContractUserAssignAmdRepository
 * @package App\Repositories
 * @version July 2, 2024, 4:20 am +04
 */
class CMContractUserAssignAmdRepository extends BaseRepository
{
    protected $contractUserAssignRepo;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function getContractUserAssignRepo()
    {
        if (!$this->contractUserAssignRepo) {
            $this->contractUserAssignRepo = $this->app->make(ContractUserAssignRepository::class);
        }
        return $this->contractUserAssignRepo;
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'uuid',
        'contractId',
        'userGroupId',
        'userId',
        'status',
        'createdBy',
        'updatedBy'
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
        return CMContractUserAssignAmd::class;
    }

    public function save($historyId, $contractId)
    {
        $userAssignData = $this->getContractUserAssignRepo()->getUserAssignData($contractId);

        try
        {
            foreach ($userAssignData as $record)
            {
                $recordData = $record->toArray();
                $recordData['id'] = $record['id'];
                $recordData['userGroupId'] = $record['userGroupId'];
                $recordData['userId'] = $record['userId'];
                $recordData['contract_history_id'] = $historyId;
                $recordData['createdBy'] = $record['createdBy'];
                $recordData['updatedBy'] = $record['updatedBy'];
                $recordData['updated_at'] = $record['updated_at'];
                CMContractUserAssignAmd::create($recordData);
            }
        }
        catch
            (Exception $e)
        {
            throw new ContractCreationException("User assign data saving failed " . $e->getMessage());
        }
    }

    public function userAssignedData($companyId, $uuid)
    {
        $historyData =  ContractManagementUtils::getContractHistoryData($uuid);
        return $this->model->userAssignedData($historyData->id);
    }
}
