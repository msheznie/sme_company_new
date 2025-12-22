<?php

namespace App\Repositories;

use App\Models\ErpDocumentMaster;
use App\Repositories\BaseRepository;

/**
 * Class ErpDocumentMasterRepository
 * @package App\Repositories
 * @version May 10, 2024, 3:22 pm +04
*/

class ErpDocumentMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documentID',
        'documentDescription',
        'departmentSystemID',
        'departmentID',
        'isPrintable',
        'timeStamp'
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
        return ErpDocumentMaster::class;
    }
}
