<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Helpers\General;
use App\Models\TimeMaterialConsumptionAmd;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use Illuminate\Contracts\Foundation\Application;
use Exception;

/**
 * Class TimeMaterialConsumptionAmdRepository
 * @package App\Repositories
 * @version October 7, 2024, 1:36 pm +04
*/

class TimeMaterialConsumptionAmdRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $timeMaterialConsumptionRepo;
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function getTimeMaterialConsumptionRepo()
    {
        if (!$this->timeMaterialConsumptionRepo)
        {
            $this->timeMaterialConsumptionRepo = $this->app->make(TimeMaterialConsumptionRepository::class);
        }
        return $this->timeMaterialConsumptionRepo;
    }

    protected $fieldSearchable = [
        'id',
        'contract_history_id',
        'level_no',
        'uuid',
        'contract_id',
        'item',
        'description',
        'min_quantity',
        'max_quantity',
        'price',
        'quantity',
        'uom_id',
        'amount',
        'boq_id',
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
        return TimeMaterialConsumptionAmd::class;
    }
    public function save($historyId, $contractId)
    {
        try
        {
            $consumptions = $this->getTimeMaterialConsumptionRepo()->getTimeMaterialConsumptionToAmd($contractId);

            foreach ($consumptions as $record)
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
            GeneralService::sendException(trans('common.time_and_material_consumption_data_saving_failed') . $e->getMessage());
        }
    }
    public function saveInitialRecord($contractId)
    {
        try
        {
            $consumptions = $this->getTimeMaterialConsumptionRepo()->getTimeMaterialConsumptionToAmd($contractId);

            foreach ($consumptions as $record)
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
            GeneralService::sendException(trans('common.time_and_material_consumption_data_saving_failed') . $e->getMessage());
        }
    }
}
