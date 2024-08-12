<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\BaseRepository;

/**
 * Class CompanyRepository
 * @package App\Repositories
 * @version February 16, 2024, 5:54 pm +04
*/

class DashboardRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Company::class;
    }

    public function getFieldsSearchable()
    {
        // TODO: Implement getFieldsSearchable() method.
    }
}
