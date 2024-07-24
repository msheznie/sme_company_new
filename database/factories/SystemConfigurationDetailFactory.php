<?php

namespace Database\Factories;

use App\Models\SystemConfigurationDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemConfigurationDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SystemConfigurationDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attributeId' => $this->faker->randomDigitNotNull,
        'value' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
