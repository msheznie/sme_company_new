<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Helpers\General;
use App\Models\CMContractBoqItemsAmd;
use App\Models\CMContractUserAssignAmd;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use Illuminate\Contracts\Foundation\Application;
use Exception;
/**
 * Class CMContractBoqItemsAmdRepository
 * @package App\Repositories
 * @version July 2, 2024, 12:20 pm +04
 */
class CMContractBoqItemsAmdRepository extends BaseRepository
{
    /**
     * @var array
     */

    protected $contractBoqItemRepo;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function getContractBoqItemRepo()
    {
        if (!$this->contractBoqItemRepo)
        {
            $this->contractBoqItemRepo = $this->app->make(ContractBoqItemsRepository::class);
        }
        return $this->contractBoqItemRepo;
    }

    protected $fieldSearchable = [
        'id',
        'contract_history_id',
        'uuid',
        'contractId',
        'companyId',
        'itemId',
        'description',
        'minQty',
        'maxQty',
        'qty',
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
        return CMContractBoqItemsAmd::class;
    }

    public function save($historyId, $contractId)
    {
        try
        {
            $boqData = $this->getContractBoqItemRepo()->getBoqData($contractId);

            foreach ($boqData as $record)
            {
                $levelNo = $this->model->getLevelNo($record['uuid'], $contractId);
                $recordData = $record->toArray();
                $recordData['id'] = $record['id'];
                $recordData['level_no'] = $levelNo;
                $recordData['contract_history_id'] = $historyId;
                $this->model->create($recordData);
            }
        } catch
        (Exception $e)
        {
            throw new ContractCreationException(trans('common.boq_data_saving_failed') . $e->getMessage());
        }
    }
    public function saveInitialRecord($contractId)
    {
        try
        {
            $boqData = $this->getContractBoqItemRepo()->getBoqData($contractId);

            foreach ($boqData as $record)
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
            GeneralService::sendException(trans('common.boq_data_saving_failed') . $e->getMessage());
        }
    }

}
