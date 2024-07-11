<?php

namespace Database\Factories;

use App\Models\ContractAmendmentArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractAmendmentAreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractAmendmentArea::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contract_history_id' => $this->faker->randomDigitNotNull,
        'section_id' => $this->faker->randomDigitNotNull,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull
        ];
    }
}
