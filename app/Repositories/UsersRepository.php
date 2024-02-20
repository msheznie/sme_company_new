<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\Users;
use App\Models\WebEmployeeProfile;
use App\Repositories\BaseRepository;

/**
 * Class UsersRepository
 * @package App\Repositories
 * @version February 13, 2024, 6:08 pm +04
*/

class UsersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'employee_id',
        'empID',
        'name',
        'email',
        'username',
        'password',
        'remember_token',
        'login_token',
        'uuid'
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
        return Users::class;
    }

    public function getEmployeeImage($employee_id)
    {
        $profile = WebEmployeeProfile::where('employeeSystemID',$employee_id)->first();

        if(!empty($profile)){
            return General::getFileUrlFromS3($profile['profileImage']);
        }else{
            return 'assets/images/dashboard/user.png';
        }
    }
}
