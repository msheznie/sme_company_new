<?php

namespace Database\Factories;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employees::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'empID' => $this->faker->word,
        'hrmsEmpID' => $this->faker->randomDigitNotNull,
        'serial' => $this->faker->randomDigitNotNull,
        'empLeadingText' => $this->faker->word,
        'empUserName' => $this->faker->word,
        'empTitle' => $this->faker->word,
        'empInitial' => $this->faker->word,
        'empName' => $this->faker->word,
        'empName_O' => $this->faker->word,
        'empFullName' => $this->faker->word,
        'empSurname' => $this->faker->word,
        'empSurname_O' => $this->faker->word,
        'empFirstName' => $this->faker->word,
        'empFirstName_O' => $this->faker->word,
        'empFamilyName' => $this->faker->word,
        'empFamilyName_O' => $this->faker->word,
        'empFatherName' => $this->faker->word,
        'empFatherName_O' => $this->faker->word,
        'empManagerAttached' => $this->faker->word,
        'empDateRegistered' => $this->faker->word,
        'empTelOffice' => $this->faker->word,
        'empTelMobile' => $this->faker->word,
        'empLandLineNo' => $this->faker->word,
        'extNo' => $this->faker->randomDigitNotNull,
        'empFax' => $this->faker->word,
        'empEmail' => $this->faker->word,
        'empLocation' => $this->faker->randomDigitNotNull,
        'empDateTerminated' => $this->faker->date('Y-m-d H:i:s'),
        'empLoginActive' => $this->faker->randomDigitNotNull,
        'empActive' => $this->faker->randomDigitNotNull,
        'userGroupID' => $this->faker->randomDigitNotNull,
        'empCompanySystemID' => $this->faker->randomDigitNotNull,
        'empCompanyID' => $this->faker->word,
        'religion' => $this->faker->randomDigitNotNull,
        'isLoggedIn' => $this->faker->randomDigitNotNull,
        'isLoggedOutFailYN' => $this->faker->randomDigitNotNull,
        'logingFlag' => $this->faker->randomDigitNotNull,
        'isSuperAdmin' => $this->faker->randomDigitNotNull,
        'discharegedYN' => $this->faker->randomDigitNotNull,
        'isFinalSettlementDone' => $this->faker->randomDigitNotNull,
        'hrusergroupID' => $this->faker->word,
        'employmentType' => $this->faker->randomDigitNotNull,
        'isConsultant' => $this->faker->randomDigitNotNull,
        'isTrainee' => $this->faker->randomDigitNotNull,
        'is3rdParty' => $this->faker->randomDigitNotNull,
        '3rdPartyCompanyName' => $this->faker->word,
        'gender' => $this->faker->randomDigitNotNull,
        'designation' => $this->faker->randomDigitNotNull,
        'nationality' => $this->faker->word,
        'isManager' => $this->faker->randomDigitNotNull,
        'isApproval' => $this->faker->randomDigitNotNull,
        'isDashBoard' => $this->faker->randomDigitNotNull,
        'isAdmin' => $this->faker->randomDigitNotNull,
        'isBasicUser' => $this->faker->randomDigitNotNull,
        'ActivationCode' => $this->faker->word,
        'ActivationFlag' => $this->faker->randomDigitNotNull,
        'isHR_admin' => $this->faker->randomDigitNotNull,
        'isLock' => $this->faker->randomDigitNotNull,
        'basicDataIngCount' => $this->faker->word,
        'opRptManagerAccess' => $this->faker->randomDigitNotNull,
        'isSupportAdmin' => $this->faker->randomDigitNotNull,
        'isHSEadmin' => $this->faker->randomDigitNotNull,
        'excludeObjectivesYN' => $this->faker->randomDigitNotNull,
        'machineID' => $this->faker->randomDigitNotNull,
        'timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'createdFrom' => $this->faker->randomDigitNotNull,
        'isNewPortal' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word
        ];
    }
}
