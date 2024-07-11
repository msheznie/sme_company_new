<?php

namespace Database\Factories;

use App\Models\TimeMaterialConsumption;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeMaterialConsumptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeMaterialConsumption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'contract_id' => $this->faker->randomDigitNotNull,
        'item' => $this->faker->word,
        'description' => $this->faker->word,
        'min_quantity' => $this->faker->randomDigitNotNull,
        'max_quantity' => $this->faker->randomDigitNotNull,
        'price' => $this->faker->randomDigitNotNull,
        'amount' => $this->faker->randomDigitNotNull,
        'boq_id' => $this->faker->randomDigitNotNull,
        'currency_id' => $this->faker->randomDigitNotNull,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
