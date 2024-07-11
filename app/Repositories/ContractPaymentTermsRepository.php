<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\ContractPaymentTerms;
use App\Repositories\BaseRepository;
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
            $contract = ContractManagementUtils::checkContractExist($input['contract_id'], $companySystemID);
            if(empty($contract))
            {
                throw new CommonException('Contract ID not found.');
            }
            $postData = [
                'uuid' => ContractManagementUtils::generateUuid(),
                'contract_id' => $contract['id'],
                'title' => $input['title'],
                'description' => $input['description'],
                'company_id' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];

            ContractPaymentTerms::insert($postData);

        });
    }

    public function updatePaymentTerms($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
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


            ContractPaymentTerms::where('id', $id)->update($postData);
        });
    }
}
