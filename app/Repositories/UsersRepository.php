<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\EmployeesLanguage;
use App\Models\Users;
use App\Models\WebEmployeeProfile;
use App\Repositories\BaseRepository;
use AWS\CRT\Log;
use Illuminate\Support\Facades\Auth;

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

    public function getUserLanguage($employeeId)
    {
        $userLangData = EmployeesLanguage::getUserLanguage($employeeId);
        $language =  ($userLangData && $userLangData->languages)
        ? $userLangData->languages->languageShortCode
        : 'en';


        $userLanguage = [
            'languageShortCode' => $language
        ];

        return $userLanguage;
    }

    public function updateUserLanguage($request)
    {
        $input = $request->all();
        $user = Auth::user();
        try {
            EmployeesLanguage::updateOrCreate(
                ['employeeID' => $user->employee_id],
                ['languageID' => $input['languageID']]
            );

            return [
                'status' => true,
                'message' => 'User language updated successfully.',
                'data' => $user
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Error updating user language: ' . $e->getMessage()
            ];
        }
    }
}
