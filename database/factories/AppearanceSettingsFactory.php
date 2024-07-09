<?php

namespace Database\Factories;

use App\Models\AppearanceSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppearanceSettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppearanceSettings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'appearance_system_id' => $this->faker->randomDigitNotNull,
        'appearance_element_id' => $this->faker->randomDigitNotNull,
        'value' => $this->faker->word,
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
