<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\ContractPaymentTerms;
use App\Models\ContractPaymentTermsAmd;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use App\Traits\CrudOperations;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ContractPaymentTermsRepository
 * @package App\Repositories
 * @version July 2, 2024, 8:37 am +04
*/

class ContractPaymentTermsRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
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
        return ContractPaymentTerms::class;
    }

    protected function getModel()
    {
        return new ContractPaymentTerms();
    }

    public function getContractPaymentTerms($id, Request $request)
    {
        $companySystemID = $request->input('selectedCompanyID');
        $contractMaster = ContractManagementUtils::checkContractExist($id, $companySystemID);

        if(empty($contractMaster))
        {
            return ['status' => false, 'message' => 'Contract Master not found'];
        }

        $paymentTerms = ContractPaymentTerms::getContractPaymentTerms($contractMaster['id'], $companySystemID);

        return [
            'status' => true,
            'message' => 'Contract payment term retrieved successfully',
            'data' => $paymentTerms
        ];
    }

    public function createPaymentTerm($input, $companySystemID)
    {
        return DB::transaction(function () use ($input, $companySystemID)
        {
            $amendment = $input['amendment'] ?? false;
            $model = $amendment ? ContractPaymentTermsAmd::class : ContractPaymentTerms::class;
            $uuid = ContractManagementUtils::generateUuid();

            $contract = ContractManagementUtils::checkContractExist($input['contract_id'], $companySystemID);
            if(empty($contract))
            {
                GeneralService::sendException('Contract ID not found.');
            }
            $uuidExists = $model::checkUuidExists($uuid);
            if ($uuidExists)
            {
                GeneralService::sendException(trans('common.contract_document_uuid_already_exists'));
            }
            $contractHistory = null;
            if($amendment)
            {
                $contractHistory = ContractManagementUtils::getContractHistoryData($input['contract_history_uuid']);
                if(empty($contractHistory))
                {
                    GeneralService::sendException('Contract history not found');
                }
            }

            $postData = [
                'uuid' => $uuid,
                'contract_id' => $contract['id'],
                'title' => $input['title'],
                'description' => $input['description'],
                'company_id' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            if($amendment)
            {
                $postData['id'] = null;
                $postData['contract_history_id'] = $contractHistory['id'];
                $postData['level_no'] = 1;
            }

            $model::insert($postData);

        });
    }

    public function updatePaymentTerms($input, $contractTerm)
    {
        $id = $contractTerm['id'];
        return DB::transaction(function () use ($input, $id, $contractTerm)
        {
            $amendment = $input['amendment'];
            $postData = [
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            if($input['field'] == 'title')
            {
                $data = [
                    'title' => $input['value'],
                ];
                $postData = array_merge($postData, $data);
            }

            if($input['field'] == 'description')
            {
                $data = [
                    'description' => $input['value'],
                    ];
                $postData = array_merge($postData, $data);
            }

            $amendment ? ContractPaymentTermsAmd::where('amd_id', $contractTerm['amd_id'])->update($postData)
                : ContractPaymentTerms::where('id', $id)->update($postData);
        });
    }
    public function getPaymentTerms($contractId)
    {
        return $this->model->getPaymentTerms($contractId);
    }
}
