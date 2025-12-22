<?php

namespace App\Repositories;

use App\Models\ThirdPartySystems;
use App\Repositories\BaseRepository;

/**
 * Class ThirdPartySystemsRepository
 * @package App\Repositories
 * @version July 24, 2024, 12:07 pm +04
*/

class ThirdPartySystemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
        'status',
        'moduleID',
        'isDefault',
        'comment'
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
        return ThirdPartySystems::class;
    }
}
