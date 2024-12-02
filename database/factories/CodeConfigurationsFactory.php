<?php

namespace Database\Factories;

use App\Models\CodeConfigurations;
use Illuminate\Database\Eloquent\Factories\Factory;

class CodeConfigurationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CodeConfigurations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'serialization_based_on' => $this->faker->word,
        'code_pattern' => $this->faker->word,
        'company_id' => $this->faker->word,
        'company_system_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
