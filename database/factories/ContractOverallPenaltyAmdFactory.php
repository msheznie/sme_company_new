<?php

namespace Database\Factories;

use App\Models\ContractOverallPenaltyAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractOverallPenaltyAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractOverallPenaltyAmd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'overall_penalty_id' => $this->faker->randomDigitNotNull,
        'contract_history_id' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contract_id' => $this->faker->randomDigitNotNull,
        'minimum_penalty_percentage' => $this->faker->randomDigitNotNull,
        'minimum_penalty_amount' => $this->faker->randomDigitNotNull,
        'maximum_penalty_percentage' => $this->faker->randomDigitNotNull,
        'maximum_penalty_amount' => $this->faker->randomDigitNotNull,
        'actual_percentage' => $this->faker->randomDigitNotNull,
        'actual_penalty_amount' => $this->faker->randomDigitNotNull,
        'penalty_circulation_start_date' => $this->faker->date('Y-m-d H:i:s'),
        'actual_penalty_start_date' => $this->faker->date('Y-m-d H:i:s'),
        'penalty_circulation_frequency' => $this->faker->word,
        'due_in' => $this->faker->randomDigitNotNull,
        'due_penalty_amount' => $this->faker->randomDigitNotNull,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
