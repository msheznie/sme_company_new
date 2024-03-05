<?php

namespace App\Repositories;

use App\Models\CMContractTypeSections;
use App\Repositories\BaseRepository;

/**
 * Class CMContractTypeSectionsRepository
 * @package App\Repositories
 * @version February 22, 2024, 2:43 pm +04
*/

class CMContractTypeSectionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contract_typeId',
        'cmSection_id',
        'is_enabled',
        'companySystemID',
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
        return CMContractTypeSections::class;
    }
}
