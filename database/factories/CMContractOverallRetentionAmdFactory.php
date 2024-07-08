<?php

namespace Database\Factories;

use App\Models\CMContractOverallRetentionAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractOverallRetentionAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractOverallRetentionAmd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'retention_id' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contractId' => $this->faker->randomDigitNotNull,
        'contractAmount' => $this->faker->randomDigitNotNull,
        'retentionPercentage' => $this->faker->randomDigitNotNull,
        'retentionAmount' => $this->faker->randomDigitNotNull,
        'startDate' => $this->faker->date('Y-m-d H:i:s'),
        'dueDate' => $this->faker->date('Y-m-d H:i:s'),
        'retentionWithholdPeriod' => $this->faker->word,
        'companySystemId' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
