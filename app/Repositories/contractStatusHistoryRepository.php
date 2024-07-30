<?php

namespace App\Repositories;

use App\Models\contractStatusHistory;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
/**
 * Class contractStatusHistoryRepository
 * @package App\Repositories
 * @version June 26, 2024, 10:27 am +04
*/

class contractStatusHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contract_id',
        'contract_history_id',
        'status',
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
        return contractStatusHistory::class;
    }

    public function getContractListStatus(Request $request)
    {
        $input = $request->all();
        $getContractData = ContractManagementUtils::checkContractExist
        ($input['contractUuid'],$input['selectedCompanyID']);


        return $this->model->getContractListStatus($getContractData->id);
    }

    public function getContractStatusHistory($request)
    {
        try
        {
            $input = $request->all();
            $contractId = $input['contractId'];
            $companyId = $input['selectedCompanyID'];
            $contractData = ContractManagementUtils::checkContractExist(
                $contractId, $companyId
            );

            if (!$contractData)
            {
                GeneralService::sendException('Contract Not Found');
            }


            return $this->model->getContractStatusHistory($contractData->id,$companyId);
        }
        catch (\Exception $ex)
        {
            GeneralService::sendException('Failed to get Document tracing data', $ex);
        }
    }
}
