<?php

namespace App\Repositories;

use App\Models\ContractSettingMaster;
use App\Repositories\BaseRepository;

/**
 * Class ContractSettingMasterRepository
 * @package App\Repositories
 * @version March 29, 2024, 3:57 pm +04
*/

class ContractSettingMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contractId',
        'contractTypeSectionId',
        'isActive'
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
        return ContractSettingMaster::class;
    }
}
