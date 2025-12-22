<?php

namespace Database\Factories;

use App\Models\EmployeeDepartments;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeDepartmentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeDepartments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmpID' => $this->faker->randomDigitNotNull,
        'DepartmentMasterID' => $this->faker->randomDigitNotNull,
        'isPrimary' => $this->faker->randomDigitNotNull,
        'date_from' => $this->faker->word,
        'date_to' => $this->faker->word,
        'Erp_companyID' => $this->faker->randomDigitNotNull,
        'SchMasterID' => $this->faker->randomDigitNotNull,
        'BranchID' => $this->faker->randomDigitNotNull,
        'AcademicYearID' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->randomDigitNotNull,
        'CreatedUserName' => $this->faker->word,
        'CreatedDate' => $this->faker->date('Y-m-d H:i:s'),
        'CreatedPC' => $this->faker->word,
        'ModifiedUserName' => $this->faker->word,
        'Timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'ModifiedPC' => $this->faker->word
        ];
    }
}
