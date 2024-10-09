<?php

namespace App\Repositories;

use App\Models\SupplierContactDetails;
use App\Repositories\BaseRepository;

/**
 * Class SupplierContactDetailsRepository
 * @package App\Repositories
 * @version August 26, 2024, 10:32 am +04
*/

class SupplierContactDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'supplierID',
        'contactTypeID',
        'contactPersonName',
        'contactPersonTelephone',
        'contactPersonFax',
        'contactPersonEmail',
        'isDefault',
        'timestamp'
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
        return SupplierContactDetails::class;
    }
}
