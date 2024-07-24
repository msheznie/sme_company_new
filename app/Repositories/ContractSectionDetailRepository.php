<?php

namespace App\Repositories;

use App\Models\ContractSectionDetail;
use App\Repositories\BaseRepository;

/**
 * Class ContractSectionDetailRepository
 * @package App\Repositories
 * @version March 29, 2024, 3:44 pm +04
*/

class ContractSectionDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sectionMasterId',
        'description',
        'inputType'
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
        return ContractSectionDetail::class;
    }
}
