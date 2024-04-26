<?php

namespace Database\Factories;

use App\Models\ContractOverallRetention;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractOverallRetentionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractOverallRetention::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'contractId' => $this->faker->randomDigitNotNull,
        'contractAmount' => $this->faker->randomDigitNotNull,
        'retentionPercentage' => $this->faker->randomDigitNotNull,
        'retentionAmount' => $this->faker->randomDigitNotNull,
        'startDate' => $this->faker->date('Y-m-d H:i:s'),
        'dueDate' => $this->faker->date('Y-m-d H:i:s'),
        'retentionWithholdPeriod' => $this->faker->date('Y-m-d H:i:s'),
        'companySystemId' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
