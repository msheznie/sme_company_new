<?php

namespace Database\Factories;

use App\Models\EmployeesLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeesLanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeesLanguage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employeeID' => $this->faker->randomDigitNotNull,
        'languageID' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
