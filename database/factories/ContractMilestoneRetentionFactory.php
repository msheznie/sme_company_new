<?php

namespace Database\Factories;

use App\Models\ContractMilestoneRetention;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractMilestoneRetentionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractMilestoneRetention::class;

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
        'milestoneId' => $this->faker->randomDigitNotNull,
        'retentionPercentage' => $this->faker->randomDigitNotNull,
        'retentionAmount' => $this->faker->randomDigitNotNull,
        'startDate' => $this->faker->date('Y-m-d H:i:s'),
        'dueDate' => $this->faker->date('Y-m-d H:i:s'),
        'withholdPeriod' => $this->faker->word,
        'paymentStatus' => $this->faker->word,
        'companySystemId' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
