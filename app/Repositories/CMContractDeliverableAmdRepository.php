<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Models\CMContractDeliverableAmd;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Foundation\Application;
use Exception;
/**
 * Class CMContractDeliverableAmdRepository
 * @package App\Repositories
 * @version July 3, 2024, 10:33 am +04
*/

class CMContractDeliverableAmdRepository extends BaseRepository
{
    /**
     * @var array
     */

    protected $contractDeliverableRepo;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function contractDeliverableRepo()
    {
        if (!$this->contractDeliverableRepo)
        {
            $this->contractDeliverableRepo = $this->app->make(ContractDeliverablesRepository::class);
        }
        return $this->contractDeliverableRepo;
    }

    protected $fieldSearchable = [
        'id',
        'contract_history_id',
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
        return CMContractDeliverableAmd::class;
    }

    public function save($historyId,$contractId)
    {
        try
        {
            $getDeliverableData = $this->contractDeliverableRepo()->getContractDeliverableRepo($contractId);
            foreach ($getDeliverableData as $record)
            {
                $recordData = $record->toArray();
                $recordData['contract_history_id'] = $historyId;
                $this->model->create($recordData);
            }
        }
        catch
        (Exception $e)
        {
            throw new ContractCreationException(trans('common.user_assign_data_saving_failed') . $e->getMessage());
        }
    }
}
