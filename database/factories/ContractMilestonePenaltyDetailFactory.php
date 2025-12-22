<?php

namespace Database\Factories;

use App\Models\ContractMilestonePenaltyDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractMilestonePenaltyDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractMilestonePenaltyDetail::class;

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
        'milestone_penalty_master_id' => $this->faker->randomDigitNotNull,
        'milestone_title' => $this->faker->randomDigitNotNull,
        'milestone_amount' => $this->faker->randomDigitNotNull,
        'penalty_percentage' => $this->faker->randomDigitNotNull,
        'penalty_amount' => $this->faker->randomDigitNotNull,
        'penalty_start_date' => $this->faker->date('Y-m-d H:i:s'),
        'penalty_frequency' => $this->faker->word,
        'due_in' => $this->faker->randomDigitNotNull,
        'due_penalty_amount' => $this->faker->randomDigitNotNull,
        'status' => $this->faker->randomDigitNotNull,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
