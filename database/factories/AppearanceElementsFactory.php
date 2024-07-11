<?php

namespace Database\Factories;

use App\Models\AppearanceElements;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppearanceElementsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppearanceElements::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'elementName' => $this->faker->word
        ];
    }
}
