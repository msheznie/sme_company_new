<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Models\FinanceDocuments;
use App\Models\ErpDirectInvoiceDetails;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;

/**
 * Class FinanceDocumentsRepository
 * @package App\Repositories
 * @version August 8, 2024, 2:17 pm +04
*/

class FinanceDocumentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'document_type',
        'document_id',
        'document_system_id',
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
        return FinanceDocuments::class;
    }

    public function getFinanceDocumentFilters($contractUuid, $selectedCompanyID)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        return ErpDirectInvoiceDetails::getContractLinkedWithErp($contractUuid, $selectedCompanyID);
    }
}
