<?php

namespace Database\Factories;

use App\Models\WebEmployeeProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebEmployeeProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WebEmployeeProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employeeSystemID' => $this->faker->randomDigitNotNull,
        'empID' => $this->faker->word,
        'profileImage' => $this->faker->word,
        'modifiedDate' => $this->faker->date('Y-m-d H:i:s'),
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
