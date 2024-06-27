<?php

namespace Database\Factories;

use App\Models\BillingFrequencies;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingFrequenciesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillingFrequencies::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dateFormat = 'Y-m-d H:i:s';
        return [
            'description' => $this->faker->word,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date($dateFormat),
        'created_at' => $this->faker->date($dateFormat),
        'updated_at' => $this->faker->date($dateFormat)
        ];
    }
}
