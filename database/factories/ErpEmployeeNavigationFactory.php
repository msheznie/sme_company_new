<?php

namespace Database\Factories;

use App\Models\ErpEmployeeNavigation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpEmployeeNavigationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpEmployeeNavigation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employeeSystemID' => $this->faker->randomDigitNotNull,
        'userGroupID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->randomDigitNotNull,
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
