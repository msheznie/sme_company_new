<?php

namespace Database\Factories;

use App\Models\ContractMilestoneRetentionAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractMilestoneRetentionAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractMilestoneRetentionAmd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->word,
        'contract_history_id' => $this->faker->randomDigitNotNull,
        'level_no' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contractId' => $this->faker->randomDigitNotNull,
        'milestoneId' => $this->faker->randomDigitNotNull,
        'retentionPercentage' => $this->faker->randomDigitNotNull,
        'retentionAmount' => $this->faker->randomDigitNotNull,
        'startDate' => $this->faker->word,
        'dueDate' => $this->faker->word,
        'withholdPeriod' => $this->faker->word,
        'paymentStatus' => $this->faker->word,
        'companySystemId' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'deleted_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
