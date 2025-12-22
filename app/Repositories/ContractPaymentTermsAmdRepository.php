<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractPaymentTerms;
use App\Models\ContractPaymentTermsAmd;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Contracts\Foundation\Application;
use Exception;
use Illuminate\Http\Request;

/**
 * Class ContractPaymentTermsAmdRepository
 * @package App\Repositories
 * @version September 25, 2024, 3:12 pm +04
*/

class ContractPaymentTermsAmdRepository extends BaseRepository
{
    protected $contractPaymentTermsRepo;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function getContractPaymentTermsRepo()
    {
        if (!$this->contractPaymentTermsRepo)
        {
            $this->contractPaymentTermsRepo = $this->app->make(ContractPaymentTermsRepository::class);
        }
        return $this->contractPaymentTermsRepo;
    }
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'contract_history_id',
        'uuid',
        'contract_id',
        'title',
        'description',
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
        return ContractPaymentTermsAmd::class;
    }

    public function save($historyId, $contractId)
    {
        try
        {
            $paymentTermsData = $this->getContractPaymentTermsRepo()->getPaymentTerms($contractId);

            foreach ($paymentTermsData as $record)
            {
                $levelNo = $this->model->getLevelNo($record['uuid'], $contractId);
                $recordData = $record->toArray();
                $recordData['level_no'] = $levelNo;
                $recordData['id'] = $record['id'];
                $recordData['contract_history_id'] = $historyId;
                $recordData['contract_id'] = $contractId;
                $recordData['created_by'] = General::currentEmployeeId();
                $this->model->create($recordData);
            }
        }
        catch
        (Exception $e)
        {
            GeneralService::sendException(trans('common.payment_terms_data_saving_failed') . $e->getMessage());
        }
    }
    public function saveInitialRecord($contractId)
    {
        try
        {
            $paymentTermsData = $this->getContractPaymentTermsRepo()->getPaymentTerms($contractId);

            foreach ($paymentTermsData as $record)
            {
                $levelNo = $this->model->getLevelNo($record['uuid'], $contractId);
                $recordData = $record->toArray();
                $recordData['level_no'] = $levelNo;
                $recordData['id'] = $record['id'];
                $recordData['contract_history_id'] = null;
                $recordData['contract_id'] = $contractId;
                $recordData['created_by'] = General::currentEmployeeId();
                $this->model->create($recordData);
            }
        }
        catch
        (Exception $e)
        {
            GeneralService::sendException(trans('common.payment_terms_data_saving_failed') . $e->getMessage());
        }
    }
    public function getContractPaymentTermsAmd($historyUuid, Request $request)
    {
        $contractHistory = ContractManagementUtils::getContractHistoryData($historyUuid);
        if(empty($contractHistory))
        {
            GeneralService::sendException(trans('common.contract_history_not_found'));
        }
        return $this->model->getContractPaymentTermsAmd($contractHistory['id'], false);
    }
}
