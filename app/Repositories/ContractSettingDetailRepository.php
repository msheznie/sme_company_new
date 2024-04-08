<?php

namespace App\Repositories;

use App\Models\ContractSettingDetail;
use App\Repositories\BaseRepository;

/**
 * Class ContractSettingDetailRepository
 * @package App\Repositories
 * @version March 30, 2024, 12:58 am +04
*/

class ContractSettingDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'settingMasterId',
        'sectionDetailId',
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
        return ContractSettingDetail::class;
    }
}
