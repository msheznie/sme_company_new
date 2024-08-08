<?php

namespace App\Repositories;

use App\Models\FinanceDocuments;
use App\Repositories\BaseRepository;

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
}
