<?php

namespace App\Repositories;

use App\Models\AppearanceSettings;
use App\Repositories\BaseRepository;

/**
 * Class AppearanceSettingsRepository
 * @package App\Repositories
 * @version July 8, 2024, 3:29 pm +04
*/

class AppearanceSettingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'appearance_system_id',
        'appearance_element_id',
        'value'
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
        return AppearanceSettings::class;
    }

    public static function getBrandingData($appearanceSystemID)
    {
        return AppearanceSettings::getBrandingData($appearanceSystemID);
    }
}
