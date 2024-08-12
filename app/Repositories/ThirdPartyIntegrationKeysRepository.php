<?php

namespace App\Repositories;

use App\Models\ThirdPartyIntegrationKeys;
use App\Repositories\BaseRepository;

/**
 * Class ThirdPartyIntegrationKeysRepository
 * @package App\Repositories
 * @version July 24, 2024, 12:10 pm +04
*/

class ThirdPartyIntegrationKeysRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'third_party_system_id',
        'api_key',
        'api_external_key',
        'api_external_url',
        'expiryDate'
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
        return ThirdPartyIntegrationKeys::class;
    }
}
