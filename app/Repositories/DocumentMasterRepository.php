<?php

namespace App\Repositories;

use App\Models\DocumentMaster;
use App\Repositories\BaseRepository;

/**
 * Class DocumentMasterRepository
 * @package App\Repositories
 * @version May 6, 2024, 4:50 pm +04
*/

class DocumentMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'documentType',
        'description',
        'status',
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
        return DocumentMaster::class;
    }
}
