<?php

namespace App\Repositories;

use App\Models\DocumentReceivedFormat;
use App\Repositories\BaseRepository;

/**
 * Class DocumentReceivedFormatRepository
 * @package App\Repositories
 * @version May 10, 2024, 6:15 am +04
*/

class DocumentReceivedFormatRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
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
        return DocumentReceivedFormat::class;
    }
}
