<?php

namespace Database\Factories;

use App\Models\ThirdPartySystems;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThirdPartySystemsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ThirdPartySystems::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->word,
        'status' => $this->faker->word,
        'moduleID' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'isDefault' => $this->faker->randomDigitNotNull,
        'comment' => $this->faker->word
        ];
    }
}
